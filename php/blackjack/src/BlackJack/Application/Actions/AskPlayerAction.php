<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Application\Actions;

use Symfony\Component\Console\Style\SymfonyStyle;
use BlackJack\BlackJack\Domain\Playable\Player;
use BlackJack\BlackJack\Domain\PlayAction\PlayAction;

final class AskPlayerAction
{
    private SymfonyStyle $io;

    private Player $player;

    public function __construct(SymfonyStyle $io, Player $player)
    {
        $this->io = $io;
        $this->player = $player;
    }

    public function __invoke(): PlayAction
    {
        $msg = sprintf('Player %s\'s action', $this->player->name());
        $action = $this->io->choice($msg, array_values(PlayAction::toArray()));
        if (!PlayAction::isValid($action)) {
            $err = sprintf('Select actions: %s', implode(' / ', PlayAction::values()));
            throw new \UnexpectedValueException($err);
        }
        return PlayAction::of($action);
    }
}
