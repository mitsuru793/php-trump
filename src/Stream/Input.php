<?php
declare(strict_types=1);

namespace Trump\Stream;

final class Input implements InputInterface
{
    /** @var resource */
    private $input;

    /**
     * @param resource $input
     */
    public function __construct($input)
    {
        $this->input = $input;
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