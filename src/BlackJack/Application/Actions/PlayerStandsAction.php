<?php
declare(strict_types=1);

namespace Trump\BlackJack\Application\Actions;

use BadMethodCallException;
use Trump\BlackJack\Domain\Playable\Dealer;
use Trump\BlackJack\Domain\Playable\Player;
use Trump\Deck\Deck;

final class PlayerStandsAction
{
    private Dealer $dealer;

    private Player $player;

    private Deck $deck;

    /**
     * PlayerStandsAction constructor.
     * @param Player $player
     */
    public function __construct(Dealer $dealer, Player $player, Deck $deck)
    {
        $this->dealer = $dealer;
        $this->player = $player;
        $this->deck = $deck;
    }

    /**
     * Returns true when player wins
     *
     * @throws BadMethodCallException
     */
    public function __invoke(): bool
    {
        if ($this->player->isBust()) {
            $err = sprintf('Player %s is bust so cannot stand', $this->player->name());
            throw new BadMethodCallException($err);
        }

        while ($this->dealer->score() < 17) {
            $card = $this->deck->draw();
            $this->dealer->addCard($card);
        }

        if ($this->dealer->isBust()) {
            return true;
        }

        return $this->dealer->score() < $this->player->score();
    }
}