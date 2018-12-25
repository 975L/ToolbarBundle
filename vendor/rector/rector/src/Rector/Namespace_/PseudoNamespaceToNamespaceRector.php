<?php declare(strict_types=1);

namespace Rector\Rector\Namespace_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\Attribute;
use Rector\PhpParser\Node\Maintainer\ClassMaintainer;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\ConfiguredCodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class PseudoNamespaceToNamespaceRector extends AbstractRector
{
    /**
     * @var string|null
     */
    private $newNamespace;

    /**
     * @var string[][]|null[]
     */
    private $namespacePrefixWithExcludedClasses = [];

    /**
     * @var ClassMaintainer
     */
    private $classMaintainer;

    /**
     * @param string[][]|null[] $namespacePrefixesWithExcludedClasses
     */
    public function __construct(ClassMaintainer $classMaintainer, array $namespacePrefixesWithExcludedClasses)
    {
        $this->classMaintainer = $classMaintainer;
        $this->namespacePrefixWithExcludedClasses = $namespacePrefixesWithExcludedClasses;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Replaces defined Pseudo_Namespaces by Namespace\Ones.', [
            new ConfiguredCodeSample(
                '$someService = new Some_Object;',
                '$someService = new Some\Object;',
                [
                    ['Some_' => []],
                ]
            ),
            new ConfiguredCodeSample(
<<<'CODE_SAMPLE'
$someService = new Some_Object;
$someClassToKeep = new Some_Class_To_Keep;
CODE_SAMPLE
                ,
<<<'CODE_SAMPLE'
$someService = new Some\Object;
$someClassToKeep = new Some_Class_To_Keep;
CODE_SAMPLE
                ,
                [
                    ['Some_' => ['Some_Class_To_Keep']],
                ]
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Name::class, Identifier::class];
    }

    /**
     * @param Name|Identifier $node
     */
    public function refactor(Node $node): ?Node
    {
        // no name → skip
        if (! $node->toString()) {
            return null;
        }

        foreach ($this->namespacePrefixWithExcludedClasses as $namespacePrefix => $excludedClasses) {
            if (! $this->nameStartsWith($node, $namespacePrefix)) {
                continue;
            }

            if (is_array($excludedClasses) && $this->isNames($node, $excludedClasses)) {
                return null;
            }

            if ($node instanceof Name) {
                return $this->processName($node);
            }

            return $this->processIdentifier($node);
        }

        return null;
    }

    /**
     * @param Stmt[] $nodes
     * @return Node[]
     */
    public function afterTraverse(array $nodes): array
    {
        if ($this->newNamespace === null) {
            return $nodes;
        }

        $namespaceNode = new Namespace_(new Name($this->newNamespace));
        foreach ($nodes as $key => $node) {
            if ($node instanceof Class_) {
                $nodes = $this->classMaintainer->insertBeforeAndFollowWithNewline($nodes, $namespaceNode, $key);

                break;
            }
        }

        $this->newNamespace = null;

        return $nodes;
    }

    private function processName(Name $nameNode): Name
    {
        $nameNode->parts = explode('_', $this->getName($nameNode));

        return $nameNode;
    }

    private function processIdentifier(Identifier $identifierNode): ?Identifier
    {
        $parentNode = $identifierNode->getAttribute(Attribute::PARENT_NODE);
        if (! $parentNode instanceof Class_) {
            return null;
        }

        $newNameParts = explode('_', $this->getName($identifierNode));
        $lastNewNamePart = $newNameParts[count($newNameParts) - 1];

        $namespaceParts = $newNameParts;
        array_pop($namespaceParts);

        $newNamespace = implode('\\', $namespaceParts);
        if ($this->newNamespace !== null && $this->newNamespace !== $newNamespace) {
            throw new ShouldNotHappenException('There woulde are 2 different namespaces in one file');
        }

        $this->newNamespace = $newNamespace;

        $identifierNode->name = $lastNewNamePart;

        return $identifierNode;
    }
}
