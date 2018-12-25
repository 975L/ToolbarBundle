<?php declare(strict_types=1);

namespace Symplify\CodingStandard\Fixer\Naming;

use Closure;
use Nette\Utils\Strings;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;
use Symplify\CodingStandard\Fixer\AbstractSymplifyFixer;
use Symplify\PackageBuilder\Php\TypeAnalyzer;
use Symplify\TokenRunner\Wrapper\FixerWrapper\ArgumentWrapper;
use Symplify\TokenRunner\Wrapper\FixerWrapper\ClassWrapper;
use Symplify\TokenRunner\Wrapper\FixerWrapper\ClassWrapperFactory;
use Symplify\TokenRunner\Wrapper\FixerWrapper\PropertyWrapper;

final class PropertyNameMatchingTypeFixer extends AbstractSymplifyFixer implements ConfigurableFixerInterface
{
    /**
     * @var string[]
     */
    private $skippedClasses = [
        'self',
        'static',
        'this',
        Closure::class,
        '*DateTime*',
        '*Spl*',
        '*FileInfo',
        'std*',
        'Iterator*',
        'SimpleXML*',
        '*|*', // union types
        '*[]', // arrays
        'PhpParser\Node\*',
        Token::class,
        '*_', // anything that ends with underscore
    ];

    /**
     * @var string[]
     */
    private $extraSkippedClasses = [];

    /**
     * @var ClassWrapperFactory
     */
    private $classWrapperFactory;

    /**
     * @var TypeAnalyzer
     */
    private $typeAnalyzer;

    public function __construct(ClassWrapperFactory $classWrapperFactory, TypeAnalyzer $typeAnalyzer)
    {
        $this->classWrapperFactory = $classWrapperFactory;
        $this->typeAnalyzer = $typeAnalyzer;
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('Property and argument name should match its type, if possible.', [
            new CodeSample(
                '<?php
class SomeClass
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
}'
            ),
        ]);
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound([T_STRING, T_VARIABLE])
            && $tokens->isAnyTokenKindsFound(Token::getClassyTokenKinds());
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($this->getReversedClassyPositions($tokens) as $position) {
            $classWrapper = $this->classWrapperFactory->createFromTokensArrayStartPosition($tokens, $position);

            if ($classWrapper->isGivenKind([T_CLASS, T_TRAIT])) {
                $this->fixClassProperties($classWrapper);
            }

            $this->fixClassMethods($classWrapper);
        }
    }

    /**
     * @param mixed[]|null $configuration
     */
    public function configure(?array $configuration = null): void
    {
        $this->extraSkippedClasses = $configuration['extra_skipped_classes'] ?? [];
    }

    private function fixClassProperties(ClassWrapper $classWrapper): void
    {
        $changedPropertyNames = $this->resolveWrappers($classWrapper->getPropertyWrappers());

        foreach ($changedPropertyNames as $oldName => $newName) {
            $classWrapper->renameEveryPropertyOccurrence($oldName, $newName);
        }
    }

    private function fixClassMethods(ClassWrapper $classWrapper): void
    {
        foreach ($classWrapper->getMethodWrappers() as $methodWrapper) {
            /** @var ArgumentWrapper[] $argumentWrappers */
            $argumentWrappers = array_reverse($methodWrapper->getArguments());

            $changedVariableNames = $this->resolveWrappers($argumentWrappers);

            foreach ($changedVariableNames as $oldName => $newName) {
                $methodWrapper->renameEveryVariableOccurrence($oldName, $newName);
            }
        }
    }

    /**
     * @param ArgumentWrapper[]|PropertyWrapper[] $typeWrappers
     * @return string[]
     */
    private function resolveWrappers(array $typeWrappers): array
    {
        $changedNames = [];

        foreach ($typeWrappers as $typeWrapper) {
            if ($this->shouldSkipWrapper($typeWrapper)) {
                continue;
            }

            $oldName = $typeWrapper->getName();

            $types = $typeWrapper->getTypes();

            // unable to resolve correctly
            if (count($types) > 1) {
                continue;
            }

            /** @var string $type */
            $type = array_pop($types);
            $expectedName = $this->getExpectedNameFromTypes($type);
            if ($expectedName === '') {
                continue;
            }

            $typeWrapper->changeName($expectedName);

            $changedNames[$oldName] = $expectedName;
        }

        return $changedNames;
    }

    /**
     * @param ArgumentWrapper|PropertyWrapper $typeWrapper
     */
    private function shouldSkipWrapper($typeWrapper): bool
    {
        if ($typeWrapper->getTypes() === [] || $typeWrapper->isClassType() === false) {
            return true;
        }

        $oldName = $typeWrapper->getName();

        if ($this->isAllowedNameOrType($oldName, $typeWrapper->getTypes(), (string) $typeWrapper->getFqnType())) {
            return true;
        }

        foreach ($typeWrapper->getTypes() as $type) {
            $expectedName = $this->getExpectedNameFromTypes($type);
            if ($oldName === $expectedName) {
                return true;
            }
        }

        return false;
    }

    private function getExpectedNameFromTypes(string $type): string
    {
        $rawName = $type;

        // is FQN namespace
        if (Strings::contains($rawName, '\\')) {
            $rawNameParts = explode('\\', $rawName);
            $rawName = array_pop($rawNameParts);
        }

        $rawName = $this->removePrefixesAndSuffixes($rawName);

        // if all is upper-cased, it should be lower-cased
        if ($rawName === strtoupper($rawName)) {
            $rawName = strtolower($rawName);
        }

        // remove "_"
        $rawName = Strings::replace($rawName, '#_#', '');

        // turns $SOMEUppercase => $someUppercase
        for ($i = 0; $i <= strlen($rawName); ++$i) {
            if (ctype_upper($rawName[$i]) && ctype_upper($rawName[$i + 1])) {
                $rawName[$i] = strtolower($rawName[$i]);
            } else {
                break;
            }
        }

        return lcfirst($rawName);
    }

    /**
     * @param string[] $types
     */
    private function isAllowedNameOrType(string $name, array $types, string $fqnType): bool
    {
        if ($this->shouldSkipClass($fqnType)) {
            return true;
        }

        // unable to determine correctly
        if (count($types) > 1) {
            return true;
        }

        /** @var string $type */
        $type = array_pop($types);

        if ($this->typeAnalyzer->isPhpReservedType($type)) {
            return true;
        }

        // starts with adjective, e.g. (Post $firstPost, Post $secondPost)
        $expectedName = $this->getExpectedNameFromTypes($type);

        return Strings::contains($name, ucfirst($expectedName)) && Strings::endsWith($name, ucfirst($expectedName));
    }

    private function removePrefixesAndSuffixes(string $rawName): string
    {
        // is SomeInterface
        if (Strings::endsWith($rawName, 'Interface')) {
            $rawName = Strings::substring($rawName, 0, -strlen('Interface'));
        }

        // is ISomeClass
        if ($this->isPrefixedInterface($rawName)) {
            $rawName = Strings::substring($rawName, 1);
        }

        // is AbstractClass
        if (Strings::startsWith($rawName, 'Abstract')) {
            $rawName = Strings::substring($rawName, strlen('Abstract'));
        }

        return $rawName;
    }

    private function shouldSkipClass(string $class): bool
    {
        $skippedClasses = array_merge($this->skippedClasses, $this->extraSkippedClasses);

        foreach ($skippedClasses as $skippedClass) {
            if (fnmatch($skippedClass, $class, FNM_NOESCAPE)) {
                return true;
            }
        }

        return false;
    }

    private function isPrefixedInterface(string $rawName): bool
    {
        return strlen($rawName) > 3
            && Strings::startsWith($rawName, 'I')
            && ctype_upper($rawName[1])
            && ctype_lower($rawName[2]);
    }
}
