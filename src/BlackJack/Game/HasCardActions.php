<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

/**
 * @mixin GameRunner
 */
trait HasCardActions
{
    private function passFirstCard(): void
    {
        foreach ($this->game->players() as $player) {
            $card = $this->game->deck()->draw();
            $player->addCard($card);
        }
    }
}