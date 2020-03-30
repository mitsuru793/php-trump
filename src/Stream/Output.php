<?php
declare(strict_types=1);

namespace Trump\Stream;

final class Output implements OutputInterface
{
    /** @var resource */
    private $output;

    /**
     * @param resource $output
     */
    public function __construct($output)
    {
        $this->output = $output;
    }

    public function write($lines): void
    {
        if (!is_array($lines)) {
            $lines = [$lines];
        }

        fwrite($this->output, implode('', $lines));
    }

    public function puts($lines): void
    {
        if (!is_array($lines)) {
            $lines = [$lines];
        }

        fwrite($this->output, implode(PHP_EOL, $lines));
    }
}