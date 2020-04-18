<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Playable;

use BlackJack\Deck\Card;
use BlackJack\Deck\Cards;

interface Playable
{
    public function name(): string;

    public function isDealer(): bool;

    /**
     * @return static
     */
    public function addCard(Card $card);

    public function cards(): Cards;

    public function isBust(): bool;

    public function score(): int;
}
