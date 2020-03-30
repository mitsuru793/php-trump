<?php
declare(strict_types=1);

namespace Trump\BlackJack\Playable;

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
}
