<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Trump\BlackJack\Playable\Dealer;
use Trump\BlackJack\Playable\Player;
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

            echo $this->render->info($messages);
            echo $this->render->renderGame();
        }
    }

    private function playerTurn(Player $player): PlayerActionResult
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
