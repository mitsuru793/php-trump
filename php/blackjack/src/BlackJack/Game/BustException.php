<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Game;

use BlackJack\BlackJack\Domain\Playable\Player;

final class BustException extends \RuntimeException
{
    public const BORDER = 21;

    public function __construct(Player $player)
    {
        $msg = sprintf('Player %s is bust at %d', $player->name(), $player->score()->value());
        parent::__construct($msg);
    }
}
