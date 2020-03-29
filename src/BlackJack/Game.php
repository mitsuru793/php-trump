<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use BadMethodCallException;
use Trump\Deck;

final class Game
{
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

    private function shuffle(Deck $deck): Deck
    {
        $cards = $deck->cards();
        shuffle($cards);
        return new Deck($cards);
    }

    public function run(): void
    {
        $this->passFirstCard();
        $this->cycle();
    }

    private function passFirstCard(): void
    {
        foreach ($this->players as $player) {
            $card = $this->deck->draw();
            $player->addCard($card);

            echo $this->render->renderGame();
        }
    }

    private function cycle(): void
    {
        foreach ($this->players as $player) {
            $this->playerTurn($player);
        }
    }

    private function askAction(Player $player): PlayAction
    {
        $msg = sprintf('Player %s\'s action:', $player->name());
        $action = readline($msg);
        if (!PlayAction::isValid($action)) {
            $err = sprintf('Select actions: %s', implode(' / ', PlayAction::values()));
            throw new \UnexpectedValueException($err);
        }
        return PlayAction::of($action);
    }

    private function hit(Player $player): void
    {
        $card = $this->deck->draw();
        $player->addCard($card);
        if ($player->isBust()) {
            throw new BustException($player);
        }
    }

    /**
     * Returns true when player wins
     */
    private function stand(Player $player): bool
    {
        if ($player->isBust()) {
            $err = sprintf('Player %s is bust so cannot stand', $player->name());
            throw new BadMethodCallException($err);
        }

        while ($this->dealer->score() < 17) {
            $card = $this->deck->draw();
            $this->dealer->addCard($card);
        }

        if ($this->dealer->isBust()) {
            return true;
        }

        return $this->dealer->score() < $player->score();
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
