<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Application\Actions\AskPlayerAction;
use Trump\BlackJack\Application\Actions\PlayerHitsAction;
use Trump\BlackJack\Application\Actions\PlayerStandsAction;
use Trump\BlackJack\Application\Actions\PlayerTurnAction;
use Trump\BlackJack\Domain\Game;
use Trump\BlackJack\Domain\Playable\Player;
use Trump\BlackJack\Domain\PlayAction\PlayerActionResult;
use Trump\BlackJack\Renderer;

final class GameRunner
{
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
        $askPlayerAction = new AskPlayerAction($this->io, $player);
        $playerHitsAction = new PlayerHitsAction($this->game->deck(), $player);
        $playerStandsAction = new PlayerStandsAction($this->game->dealer(), $player, $this->game->deck());
        $playerTurnAction = new PlayerTurnAction($this->render, $askPlayerAction, $playerHitsAction, $playerStandsAction);
        return $playerTurnAction();
    }
}
