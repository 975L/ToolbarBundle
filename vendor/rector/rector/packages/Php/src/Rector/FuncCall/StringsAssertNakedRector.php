<?php declare(strict_types=1);

namespace Rector\Php\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Parser;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see https://3v4l.org/lB5fR
 * @see https://github.com/simplesamlphp/simplesamlphp/pull/708/files
 */
final class StringsAssertNakedRector extends AbstractRector
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('String asserts must be passed directly to assert()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function nakedAssert()
{
    assert('true === true');
    assert("true === true");
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
function nakedAssert()
{
    assert(true === true);
    assert(true === true);
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'assert')) {
            return null;
        }

        if (! $node->args[0]->value instanceof String_) {
            return null;
        }

        /** @var String_ $stringNode */
        $stringNode = $node->args[0]->value;

        $phpCode = '<?php ' . $stringNode->value . ';';
        $contentNodes = $this->parser->parse($phpCode);

        if (! isset($contentNodes[0])) {
            return null;
        }

        if (! $contentNodes[0] instanceof Expression) {
            return null;
        }

        $node->args[0] = new Arg($contentNodes[0]->expr);

        return $node;
    }
}
