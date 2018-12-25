<?php declare(strict_types=1);

namespace Rector\PhpParser\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt\Return_;
use Rector\NodeTypeResolver\Node\Attribute;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * Covers: https://github.com/nikic/PHP-Parser/commit/987c61e935a7d73485b4d73aef7a17a4c1e2e325
 */
final class RemoveNodeRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns integer return to remove node to constant in NodeVisitor of PHP-Parser', [
            new CodeSample(
                <<<'CODE_SAMPLE'
public function leaveNode()
{
    return false;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
public function leaveNode()
{
    return NodeTraverser::REMOVE_NODE;
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof ConstFetch) {
            return null;
        }

        $methodName = $node->getAttribute(Attribute::METHOD_NAME);
        if ($methodName !== 'leaveNode') {
            return null;
        }

        if (! $this->isFalse($node->expr)) {
            return null;
        }

        $node->expr = $this->createClassConstant('PhpParser\NodeTraverser', 'REMOVE_NODE');

        return $node;
    }
}
