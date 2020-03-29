<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use BadMethodCallException;
use Trump\Deck;

final class Game
{
    use HasPlayerActions;

    use HasCardActions;

    private Dealer $dealer;

    /** @var Player[] */
    private array $players;

    private Deck $deck;

    private Renderer $render;

    public function __construct(Dealer $dealer, array $players)
    {
        $this->dealer = $dealer;
        $this->players = $players;
        $this->deck = $this->shuffle(Deck::create());
        $this->render = new Renderer($this->dealer, $this->players, $this->deck);
    }

    public function run(): void
    {
        $this->passFirstCard();
        echo $this->render->renderGame();

        $this->cycle();
    }

    private function cycle(): void
    {
        foreach ($this->players as $player) {
            $this->playerTurn($player);
        }
    }

    private function playerTurn(Player $player): void
    {
        try {
            while (true) {
                try {
                    $action = $this->askAction($player);
                } catch (\UnexpectedValueException $e) {
                    echo $this->render->error($e->getMessage());
                    continue;
                }

                if ($action->isHit()) {
                    $this->hit($player);
                    echo $this->render->renderGame();
                    continue;
                }

                if ($action->isStand()) {
                    $playerWin = $this->stand($player);
                    if ($playerWin) {
                        $msg = sprintf('Player %s won.', $player->name());
                    } else {
                        $msg = 'Dealer won.';
                    }
                    echo $this->render->info($msg);
                    echo $this->render->renderGame();
                    break;
                }

            }
        } catch (BustException $e) {
            echo $this->render->info($e->getMessage());
            echo $this->render->info('Dealer won.');
            echo $this->render->renderGame();
        }
    }
}
