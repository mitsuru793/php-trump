<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Domain\Game;
use Trump\BlackJack\Domain\Playable\Player;
use Trump\BlackJack\Domain\PlayAction\PlayerActionResult;
use Trump\BlackJack\Renderer;

final class GameRunner
{
    use HasPlayerActions;

    use HasCardActions;

    private InputInterface $input;

    private OutputInterface $output;

    private SymfonyStyle $io;

    private Game $game;

    private Renderer $render;

    public function __construct(InputInterface $input, OutputInterface $output, Game $game)
    {
        $this->input = $input;
        $this->output = $output;
        $this->game = $game;
        $this->io = new SymfonyStyle($input, $output);
        $this->render = new Renderer($this->io, $this->game);
    }

    public function run(): void
    {
        $this->passFirstCard();
        $this->cycle();
    }

    private function cycle(): void
    {
        foreach ($this->game->players() as $player) {
            $this->render->section(sprintf('Player %s turn.', $player->name()));
            $this->game->dealer()->dropCards();
            $this->render->renderGame();

            $messages = null;

            $result = $this->playerTurn($player);
            if ($result->isBust()) {
                $e = new BustException($player);
                $messages = [
                    $e->getMessage(),
                    'Dealer won.',
                ];
            } elseif ($result->didWin()) {
                $messages = [sprintf('Player %s won.', $player->name())];
            } else {
                $messages = ['Dealer won.'];
            }

            $this->render->renderGame();
            $this->render->info($messages);
        }
    }

    private function playerTurn(Player $player): PlayerActionResult
    {
        try {
            while (true) {
                try {
                    $action = $this->askAction($player);
                } catch (\UnexpectedValueException $e) {
                    $this->render->error($e->getMessage());
                    continue;
                }

                if ($action->isHit()) {
                    $this->hit($player);
                    $this->render->renderGame();
                    continue;
                }

                if ($action->isStand()) {
                    $playerWin = $this->stand($player);
                    if ($playerWin) {
                        return PlayerActionResult::won();
                    }
                    return PlayerActionResult::lostByStand();
                }
            }
        } catch (BustException $e) {
            return PlayerActionResult::bust();
        }
    }
}
