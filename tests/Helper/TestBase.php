<?php
declare(strict_types=1);

namespace Helper;

use PHPStan\Testing\TestCase;

abstract class TestBase extends TestCase
{
    public function assertObjectEquals($expected, $actual): void
    {
        if (!method_exists($expected, 'equals')) {
            $err = sprintf('Does not have equals method: %s', get_class($expected));
            throw new \InvalidArgumentException($err);
        }
        parent::assertTrue($expected->equals($actual));
    }
}
