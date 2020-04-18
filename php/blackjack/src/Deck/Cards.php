<?php
declare(strict_types=1);

namespace Trump\Deck;

use Trump\BlackJack\Game\BustException;

final class Cards implements \IteratorAggregate
{
    /** @var Card[] */
    private array $cards;

    /** @var array<string, array<int, Card>>  */
    private array $cache;

    /**
     * @param Card[] $cards
     */
    public function __construct(array $cards = [])
    {
        $this->cards = $cards;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function add(Card $card): self
    {
        $this->cards[] = $card;
        return $this;
    }

    /**
     * Try to make ace be 10.
     */
    public function maxScore(): int
    {
        $score = array_reduce($this->cards, function (int $sum, Card $card) {
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
        return $score;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->cards);
    }
}