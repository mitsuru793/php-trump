<?php
declare(strict_types=1);

namespace Trump\Stream;

final class StdInput implements InputInterface
{
    /** @var resource */
    private $input;

    public function __construct()
    {
        $this->input = STDIN;
    }

    public function read(): string
    {
        return stream_get_contents($this->input);
    }

    public function ask(): string
    {
        return stream_get_line($this->input, 1024, PHP_EOL);
    }
}