<?php
declare(strict_types=1);

namespace Trump\BlackJack\Application\Actions;

use Trump\BlackJack\Domain\Playable\Player;
use Trump\BlackJack\Game\BustException;
use Trump\Deck\Deck;

final class PlayerHitsAction
{
    private Deck $deck;

    private Player $player;

    public function __construct(Deck $deck, Player $player)
    {
        $this->deck = $deck;
        $this->player = $player;
    }

    /**
     * @throw BustException
     */
    public function __invoke(): void
    {
        $card = $this->deck->draw();
        $this->player->addCard($card);
        if ($this->player->isBust()) {
            throw new BustException($this->player);
        }
    }
}