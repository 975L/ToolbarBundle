<?php declare(strict_types=1);

namespace Rector\Php\Tests\Rector\List_\ListSwapArrayOrderRector;

use Rector\Php\Rector\List_\ListSwapArrayOrderRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ListSwapArrayOrderRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFiles([__DIR__ . '/Fixture/fixture.php.inc']);
    }

    public function getRectorClass(): string
    {
        return ListSwapArrayOrderRector::class;
    }
}
