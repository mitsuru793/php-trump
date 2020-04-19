<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Score;

use BlackJack\BlackJack\Game\BustException;
use Trump\Card;

final class Score
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function value(): int
    {
        return $this->value;
    }

    /**
     * @param Card[] $cards
     */
    public static function maxFromCards(array $cards): self
    {
        $score = array_reduce($cards, function (int $sum, Card $card) {
            $num = $card->number()->value();
            if ($num > 10) {
                return $sum + 10;
            }

            if ($num !== 1) {
                return $sum + $num;
            }

            if ($num + 10 > BustException::BORDER) {
                // Ace is as 1.
                return $sum + ($num + 1);
            }

            // Ace is as 10.
            return $sum + ($num + 10);
        }, 0);
        assert(is_int($score));
        return new self($score);
    }
}