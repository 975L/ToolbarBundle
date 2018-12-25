<?php declare(strict_types=1);

namespace Rector\Php\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeTypeResolver\Node\Attribute;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see https://wiki.php.net/rfc/remove_php4_constructors
 */
final class Php4ConstructorRector extends AbstractRector
{
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;

    public function __construct(BetterNodeFinder $betterNodeFinder)
    {
        $this->betterNodeFinder = $betterNodeFinder;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Changes PHP 4 style constructor to __construct.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function SomeClass()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct()
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $namespace = $node->getAttribute(Attribute::NAMESPACE_NAME);
        // catch only classes without namespace
        if ($namespace) {
            return null;
        }

        /** @var Class_ $classNode */
        $classNode = $node->getAttribute(Attribute::CLASS_NODE);

        // anonymous class → skip
        if ($classNode->name === null || $node->isAbstract() || $node->isStatic()) {
            return null;
        }

        // process parent call references first
        $this->processClassMethodStatementsForParentConstructorCalls($node);

        // not PSR-4 constructor
        if (! $this->isNameInsensitive($classNode, (string) $node->name)) {
            return null;
        }

        // does it already have a __construct method?
        if (! in_array('__construct', $this->getClassMethodNames($classNode), true)) {
            $node->name = new Identifier('__construct');
        }

        if ($node->stmts === null) {
            return null;
        }

        if (count($node->stmts) === 1) {
            /** @var Expression $stmt */
            $stmt = $node->stmts[0];

            if ($this->isThisConstructCall($stmt->expr)) {
                $this->removeNode($node);

                return null;
            }
        }

        return $node;
    }

    private function processClassMethodStatementsForParentConstructorCalls(ClassMethod $classMethodNode): void
    {
        if (! is_iterable($classMethodNode->stmts)) {
            return;
        }

        /** @var Expression $methodStmt */
        foreach ($classMethodNode->stmts as $methodStmt) {
            if ($methodStmt instanceof Expression) {
                $methodStmt = $methodStmt->expr;
            }

            if (! $methodStmt instanceof StaticCall) {
                continue;
            }

            $this->processParentPhp4ConstructCall($methodStmt);
        }
    }

    /**
     * @return string[]
     */
    private function getClassMethodNames(Class_ $classNode): array
    {
        $classMethodNames = [];

        /** @var ClassMethod[] $classMethodNodes */
        $classMethodNodes = $this->betterNodeFinder->findInstanceOf($classNode->stmts, ClassMethod::class);
        foreach ($classMethodNodes as $classMethodNode) {
            $classMethodNames[] = (string) $classMethodNode->name;
        }

        return $classMethodNames;
    }

    private function isThisConstructCall(Node $node): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->var instanceof Variable) {
            return false;
        }

        if (! $this->isName($node->var, 'this')) {
            return false;
        }

        return $this->isName($node, '__construct');
    }

    private function processParentPhp4ConstructCall(Node $node): void
    {
        $parentClassName = $node->getAttribute(Attribute::PARENT_CLASS_NAME);

        if (! $node instanceof StaticCall) {
            return;
        }

        // no parent class
        if (! is_string($parentClassName)) {
            return;
        }

        if (! $node->class instanceof Name) {
            return;
        }

        // rename ParentClass
        if ((string) $node->class === $parentClassName) {
            $node->class = new Name('parent');
        }

        if ((string) $node->class !== 'parent') {
            return;
        }

        // it's not a parent PHP 4 constructor call
        if (! $this->isNameInsensitive($node, $parentClassName)) {
            return;
        }

        $node->name = new Identifier('__construct');
    }
}
