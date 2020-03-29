<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Trump\BlackJack\Playable\Player;

final class BustException extends \RuntimeException
{
    public function __construct(Player $player)
    {
        $msg = sprintf('Player %s is bust at %d', $player->name(), $player->score());
        parent::__construct($msg);
    }
}
