<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain;

use BlackJack\BlackJack\Domain\Playable\Dealer;
use BlackJack\BlackJack\Domain\Playable\Player;
use Trump\Deck;

final class Game
{
    private Dealer $dealer;

    /** @var Player[] */
    private array $players;

    private Deck $deck;

    /**
     * @param Player[] $players
     */
    public function __construct(Dealer $dealer, array $players, Deck $deck)
    {
        $this->dealer = $dealer;
        $this->players = $players;
        $this->deck = $deck;
    }

    public function dealer(): Dealer
    {
        return $this->dealer;
    }

    /**
     * @return Player[]
     */
    public function players(): array
    {
       return $this->players;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }
}