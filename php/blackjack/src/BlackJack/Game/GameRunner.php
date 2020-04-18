<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Game;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BlackJack\BlackJack\Application\Actions\AskPlayerAction;
use BlackJack\BlackJack\Application\Actions\PlayerHitsAction;
use BlackJack\BlackJack\Application\Actions\PlayerStandsAction;
use BlackJack\BlackJack\Application\Actions\PlayerTurnAction;
use BlackJack\BlackJack\Domain\Game;
use BlackJack\BlackJack\Domain\Playable\Player;
use BlackJack\BlackJack\Domain\PlayAction\PlayerActionResult;
use BlackJack\BlackJack\Renderer;

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

            $this->render->renderGame();
            $this->render->renderResult($player, $result);
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
