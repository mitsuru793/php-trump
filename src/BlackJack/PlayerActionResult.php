<?php
declare(strict_types=1);

namespace Trump\BlackJack;

final class PlayerActionResult
{
    private bool $isBust;
    
    private bool $didWin;

    public function __construct(bool $isBust, bool $didWin)
    {
        $this->isBust = $isBust;
        $this->didWin = $didWin;
    }

    public static function won(): self
    {
        return new self(false, true);
    }

    public static function lostByStand(): self
    {
        return new self(false, false);
    }

    public static function bust(): self
    {
        return new self(true, false);
    }

    public function isBust(): bool
    {
        return $this->isBust;
    }

    public function didWin(): bool
    {
        return $this->didWin;
    }
}