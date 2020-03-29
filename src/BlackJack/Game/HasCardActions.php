<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use Trump\Deck;

/**
 * @mixin Game
 */
trait HasCardActions
{
    private function shuffle(Deck $deck): Deck
    {
        $cards = $deck->cards();
        shuffle($cards);
        return new Deck($cards);
    }

    private function passFirstCard(): void
    {
        foreach ($this->players as $player) {
            $card = $this->deck->draw();
            $player->addCard($card);
        }
    }
}