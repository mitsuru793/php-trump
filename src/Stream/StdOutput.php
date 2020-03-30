<?php
declare(strict_types=1);

namespace Trump\Stream;

final class StdOutput implements OutputInterface
{
    public function write($lines): void
    {
        if (!is_array($lines)) {
            $lines = [$lines];
        }

        foreach ($lines as $line) {
            echo $line;
        }
    }

    public function puts($lines): void
    {
        if (!is_array($lines)) {
            $lines = [$lines];
        }

        foreach ($lines as $line) {
            echo $line . PHP_EOL;
        }
    }
}