<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use BadMethodCallException;
use Trump\BlackJack\Playable\Player;
use Trump\BlackJack\PlayAction;

/**
 * @mixin GameRunner
 */
trait HasPlayerActions
{
    private function askAction(Player $player): PlayAction
    {
        $msg = sprintf('Player %s\'s action', $player->name());
        $action = $this->io->choice($msg, array_values(PlayAction::toArray()));
        if (!PlayAction::isValid($action)) {
            $err = sprintf('Select actions: %s', implode(' / ', PlayAction::values()));
            throw new \UnexpectedValueException($err);
        }
        return PlayAction::of($action);
    }

    private function hit(Player $player): void
    {
        $card = $this->game->deck()->draw();
        $player->addCard($card);
        if ($player->isBust()) {
            throw new BustException($player);
        }
    }

    /**
     * Returns true when player wins
     */
    private function stand(Player $player): bool
    {
        if ($player->isBust()) {
            $err = sprintf('Player %s is bust so cannot stand', $player->name());
            throw new BadMethodCallException($err);
        }

        while ($this->game->dealer()->score() < 17) {
            $card = $this->game->deck()->draw();
            $this->game->dealer()->addCard($card);
        }

        if ($this->game->dealer()->isBust()) {
            return true;
        }

        return $this->game->dealer()->score() < $player->score();
    }
}