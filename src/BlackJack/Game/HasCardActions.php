<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

/**
 * @mixin Game
 */
trait HasCardActions
{
    private function passFirstCard(): void
    {
        foreach ($this->players as $player) {
            $card = $this->deck->draw();
            $player->addCard($card);
        }
    }
}