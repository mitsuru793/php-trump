<?php
declare(strict_types=1);

namespace Trump\Stream;

interface InputInterface
{
    public function read(): string;
    
    public function ask(): string;
}