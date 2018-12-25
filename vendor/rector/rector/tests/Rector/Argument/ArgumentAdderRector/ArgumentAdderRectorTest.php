<?php declare(strict_types=1);

namespace Rector\Tests\Rector\Argument\ArgumentAdderRector;

use Rector\Rector\Argument\ArgumentAdderRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Tests\Rector\Argument\ArgumentAdderRector\Source\ContainerBuilder;

final class ArgumentAdderRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFiles([
            __DIR__ . '/Fixture/fixture.php.inc', __DIR__ . '/Fixture/fixture2.php.inc', __DIR__ . '/Fixture/fixture3.php.inc', ]);
    }

    protected function getRectorClass(): string
    {
        return ArgumentAdderRector::class;
    }

    /**
     * @return mixed[]
     */
    protected function getRectorConfiguration(): array
    {
        return [ContainerBuilder::class => [
            'compile' => [['isCompiled' => false]],
            'addCompilerPass' => [2 => ['priority' => 0]],
        ]];
    }
}
