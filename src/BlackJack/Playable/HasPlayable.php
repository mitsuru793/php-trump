<?php
declare(strict_types=1);

namespace Trump\BlackJack\Playable;

use Trump\Deck\Card;

trait HasPlayable
{
    private string $name;

    /** @var Card[] */
    private array $cards;

    public function __construct(string $name, $cards = [])
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
        return $this->score() > 21;
    }

    public function score(): int
    {
        $score = array_reduce($this->cards, function (int $sum, Card $card) {
            $sum += $card->number()->value();
            return $sum;
        }, 0);
        assert(is_int($score));
        return $score;
    }
}