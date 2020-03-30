<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use Trump\BlackJack\Playable\Dealer;
use Trump\BlackJack\Playable\Player;
use Trump\BlackJack\PlayerActionResult;
use Trump\BlackJack\Renderer;
use Trump\Deck\Deck;
use Trump\Stream\OutputInterface;

final class Game
{
    use HasPlayerActions;

    use HasCardActions;

    private OutputInterface $output;

    private Dealer $dealer;

    /** @var Player[] */
    private array $players;

    private Deck $deck;

    private Renderer $render;

    public function __construct(OutputInterface $output, Dealer $dealer, array $players, Deck $deck)
    {
        $this->output = $output;
        $this->dealer = $dealer;
        $this->players = $players;
        $this->deck = $deck;
        $this->render = new Renderer($this->dealer, $this->players, $this->deck);
    }

    public function run(): void
    {
        $this->passFirstCard();
        $this->output->write($this->render->renderGame());

        $this->cycle();
    }

    private function cycle(): void
    {
        foreach ($this->players as $player) {
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

            $this->output->write([
                $this->render->info($messages),
                $this->render->renderGame(),
            ]);
        }
    }

    private function playerTurn(Player $player): PlayerActionResult
    {
        try {
            while (true) {
                try {
                    $action = $this->askAction($player);
                } catch (\UnexpectedValueException $e) {
                    $this->output->write(
                        $this->render->error($e->getMessage())
                    );
                    continue;
                }

                if ($action->isHit()) {
                    $this->hit($player);
                    $this->output->write(
                        $this->render->renderGame()
                    );
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
