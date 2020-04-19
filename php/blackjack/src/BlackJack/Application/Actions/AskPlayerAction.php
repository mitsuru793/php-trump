<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Application\Actions;

use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use BlackJack\BlackJack\Domain\Playable\Player;
use BlackJack\BlackJack\Domain\PlayAction\PlayAction;

final class AskPlayerAction
{
    private SymfonyStyle $io;

    private Player $player;

    private bool $autocomplete;

    public function __construct(SymfonyStyle $io, Player $player, bool $autocomplete = true)
    {
        $this->io = $io;
        $this->player = $player;
        $this->autocomplete = $autocomplete;
    }

    public function __invoke(): PlayAction
    {
        $err = sprintf('Select actions: %s', implode(' / ', PlayAction::values()));

        $msg = sprintf('Player %s\'s action', $this->player->name());
        $choices = array_values(PlayAction::toArray());
        $question = new ChoiceQuestion($msg, $choices);
        $question->setErrorMessage($err);

        if (!$this->autocomplete) {
            /**
             * Prevent outputting escape sequences when test.
             * @link \Symfony\Component\Console\Helper\QuestionHelper::autocomplete()
             */
            $question->setAutocompleterValues([]);
        }

        $action = $this->io->askQuestion($question);
        return PlayAction::of($action);
    }
}
