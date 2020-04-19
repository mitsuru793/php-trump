<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Playable;

use BlackJack\BlackJack\Domain\Score\Score;
use Trump\Card;

trait HasPlayable
{
    private string $name;

    /** @var Card[] */
    private array $cards;

    /**
     * @param Card[] $cards
     */
    public function __construct(string $name, array $cards)
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
        $this->cards[] = $card;
        return $this;
    }

    /**
     * @return Card[]
     */
    public function cards(): array
    {
        return $this->cards;
    }

    public function isBust(): bool
    {
        return $this->score()->value() > 21;
    }

    public function score(): Score
    {
        return Score::maxFromCards($this->cards);
    }
}