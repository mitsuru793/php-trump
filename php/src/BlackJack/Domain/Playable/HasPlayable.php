<?php
declare(strict_types=1);

namespace Trump\BlackJack\Domain\Playable;

use Trump\Deck\Card;
use Trump\Deck\Cards;

trait HasPlayable
{
    private string $name;

    private Cards $cards;

    public function __construct(string $name, Cards $cards)
    {
        $this->name = $name;
        $this->cards = $cards;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function addCard(Card $card): self
    {
        $this->cards->add($card);
        return $this;
    }

    public function cards(): Cards
    {
        return $this->cards;
    }

    public function isBust(): bool
    {
        return $this->score() > 21;
    }

    public function score(): int
    {
        return $this->cards->maxScore();
    }
}