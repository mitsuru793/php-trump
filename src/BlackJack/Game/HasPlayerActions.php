<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use BadMethodCallException;
use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Playable\Player;
use Trump\BlackJack\PlayAction;

/**
 * @mixin Game
 */
trait HasPlayerActions
{
    private function askAction(Player $player): PlayAction
    {
        $msg = sprintf('Player %s\'s action: ', $player->name());
        $action = (new SymfonyStyle($this->input, $this->output))->ask($msg);
        if (!PlayAction::isValid($action)) {
            $err = sprintf('Select actions: %s', implode(' / ', PlayAction::values()));
            throw new \UnexpectedValueException($err);
        }
        return PlayAction::of($action);
    }

    private function hit(Player $player): void
    {
        $card = $this->deck->draw();
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

        while ($this->dealer->score() < 17) {
            $card = $this->deck->draw();
            $this->dealer->addCard($card);
        }

        if ($this->dealer->isBust()) {
            return true;
        }

        return $this->dealer->score() < $player->score();
    }
}