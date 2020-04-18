<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Playable;

use BlackJack\Deck\Cards;
use BlackJack\Deck\Deck;

final class Dealer implements Playable
{
    use HasPlayable;

    public function isDealer(): bool
    {
        return true;
    }

    public function shuffle(Deck $deck): Deck
    {
        $cards = $deck->cards();
        shuffle($cards);
        return new Deck($cards);
    }

    public function dropCards(): self
    {
        $this->cards = Cards::empty();
        return $this;
    }
}
