<?php
declare(strict_types=1);


namespace Trump\Stream;

interface OutputInterface
{
    /**
     * @param string|string[] $lines
     */
    public function write($lines);
}
