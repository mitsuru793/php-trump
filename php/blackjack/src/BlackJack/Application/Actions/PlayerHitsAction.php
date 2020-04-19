<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Application\Actions;

use BlackJack\BlackJack\Domain\Playable\Player;
use BlackJack\BlackJack\Game\BustException;
use Trump\Deck;

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