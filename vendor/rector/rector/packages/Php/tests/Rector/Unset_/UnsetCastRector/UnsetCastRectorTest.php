<?php declare(strict_types=1);

namespace Rector\Php\Tests\Rector\Unset_\UnsetCastRector;

use Rector\Php\Rector\Unset_\UnsetCastRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UnsetCastRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFiles([__DIR__ . '/Fixture/fixture.php.inc']);
    }

    public function getRectorClass(): string
    {
        return UnsetCastRector::class;
    }
}
