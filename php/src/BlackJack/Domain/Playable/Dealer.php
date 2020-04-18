<?php
declare(strict_types=1);

namespace Trump\BlackJack\Domain\Playable;

use Trump\Deck\Cards;
use Trump\Deck\Deck;

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
