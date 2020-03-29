<?php
declare(strict_types=1);

namespace Trump\BlackJack\Playable;

use Trump\Card;

interface Playable
{
    public function name(): string;

    public function isDealer(): bool;

    /**
     * @return static
     */
    public function addCard(Card $card);

    public function cards();

    public function isBust(): bool;

    public function score(): int;
}
