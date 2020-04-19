<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Playable;

use BlackJack\BlackJack\Domain\Score\Score;
use Trump\Card;

interface Playable
{
    public function name(): string;

    public function isDealer(): bool;

    /**
     * @return static
     */
    public function addCard(Card $card);

    /**
     * @return Card[]
     */
    public function cards(): array;

    public function isBust(): bool;

    public function score(): Score;
}
