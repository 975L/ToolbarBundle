<?php declare(strict_types=1);

namespace Rector\PHPUnit\Tests\Rector\SpecificMethod\AssertTrueFalseInternalTypeToSpecificMethodRector;

use Rector\PHPUnit\Rector\SpecificMethod\AssertTrueFalseInternalTypeToSpecificMethodRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AssertTrueFalseInternalTypeToSpecificMethodRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFiles([__DIR__ . '/Fixture/fixture.php.inc']);
    }

    public function getRectorClass(): string
    {
        return AssertTrueFalseInternalTypeToSpecificMethodRector::class;
    }
}
