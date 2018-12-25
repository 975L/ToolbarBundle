<?php declare(strict_types=1);

namespace Rector\Rector\Function_;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\ConfiguredCodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class FunctionToStaticCallRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private $functionToStaticCall = [];

    /**
     * @param string[] $functionToMethodCall
     */
    public function __construct(array $functionToMethodCall)
    {
        $this->functionToStaticCall = $functionToMethodCall;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns defined function call to static method call.', [
            new ConfiguredCodeSample(
                'view("...", []);',
                'SomeClass::render("...", []);',
                [
                    'view' => ['SomeStaticClass', 'render'],
                ]
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
        // anonymous function
        if (! $node->name instanceof Name) {
            return null;
        }

        $functionName = $this->getName($node);
        if (! isset($this->functionToStaticCall[$functionName])) {
            return null;
        }

        [$className, $methodName] = $this->functionToStaticCall[$functionName];

        return new StaticCall(new FullyQualified($className), $methodName, $node->args);
    }
}
