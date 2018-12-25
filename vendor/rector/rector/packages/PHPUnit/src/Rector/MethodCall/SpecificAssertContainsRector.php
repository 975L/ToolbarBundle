<?php declare(strict_types=1);

namespace Rector\PHPUnit\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractPHPUnitRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/master/ChangeLog-8.0.md
 * @see https://github.com/sebastianbergmann/phpunit/commit/1c17ac33af234045ff27ba92433be8d9e5884c0a
 */
final class SpecificAssertContainsRector extends AbstractPHPUnitRector
{
    /**
     * @var string[][]
     */
    private $oldMethodsNamesToNewNames = [
        'string' => [
            'assertContains' => 'assertStringContains',
            'assertNotContains' => 'assertStringNotContains',
        ],
        'iterable' => [
            'assertContains' => 'assertIterableContains',
            'assertNotContains' => 'assertIterableNotContains',
        ],
    ];

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Change assertContains()/assertNotContains() method to new string and iterable alternatives',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
<?php

final class SomeTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $this->assertContains('foo', 'foo bar');
        $this->assertNotContains('foo', 'foo bar');
        $this->assertContains('foo', ['foo', 'bar']);
        $this->assertNotContains('foo', ['foo', 'bar']);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
<?php

final class SomeTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $this->assertStringContains('foo', 'foo bar');
        $this->assertStringNotContains('foo', 'foo bar');
        $this->assertIterableContains('foo', ['foo', 'bar']);
        $this->assertIterableNotContains('foo', ['foo', 'bar']);
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
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isInTestClass($node)) {
            return null;
        }

        if (! $this->isNames($node, ['assertContains', 'assertNotContains'])) {
            return null;
        }

        $type = $this->isStringType($node->args[1]->value) ? 'string' : 'iterable';

        $node->name = new Identifier($this->oldMethodsNamesToNewNames[$type][$this->getName($node)]);

        return $node;
    }
}
