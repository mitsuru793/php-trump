<?php
declare(strict_types=1);

namespace Trump\BlackJack\Domain\Playable;

use Trump\Deck\Card;
use Trump\Deck\Cards;

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
