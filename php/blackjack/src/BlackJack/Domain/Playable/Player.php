<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Playable;

final class Player implements Playable
{
    use HasPlayable;

    public function isDealer(): bool
    {
        return false;
    }
}
