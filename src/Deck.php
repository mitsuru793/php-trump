<?php
declare(strict_types=1);

namespace Trump;

final class Deck implements \Countable
{
    /** @var Card[] */
    private array $cards;

    /**
     * @param Card[] $cards
     */
    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    public static function create(): self
    {
        $cards = [];
        foreach (CardMark::all() as $mark) {
            foreach (CardNumber::all() as $number) {
                $cards[] = new Card($mark, $number);
            }
        }
        return new self($cards);
    }

    /**
     * @return Card[]
     */
    public function cards(): array
    {
        return $this->cards;
    }

    public function draw(): Card
    {
        if (empty($this->cards)) {
            $err = 'Cards of decks is empty. You can not draw a card.';
            throw new \BadMethodCallException($err);
        }
        return array_shift($this->cards);
    }

    public function count(): int
    {
        return count($this->cards);
    }
}
