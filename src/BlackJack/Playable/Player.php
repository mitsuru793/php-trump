<?php
declare(strict_types=1);

namespace Trump\BlackJack\Playable;

final class Player implements Playable
{
    use HasPlayable;

    public function isDealer(): bool
    {
        return false;
    }
}
