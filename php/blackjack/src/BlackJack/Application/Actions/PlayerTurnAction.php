<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Application\Actions;

use BlackJack\BlackJack\Domain\PlayAction\PlayerActionResult;
use BlackJack\BlackJack\Game\BustException;
use BlackJack\BlackJack\Renderer;

final class PlayerTurnAction
{
    private Renderer $render;

    private AskPlayerAction $askPlayerAction;

    private PlayerHitsAction $playerHitsAction;

    private PlayerStandsAction $playerStandsAction;

    public function __construct(Renderer $render, AskPlayerAction $askPlayerAction, PlayerHitsAction $playerHitsAction, PlayerStandsAction $playerStandsAction)
    {
        $this->render = $render;
        $this->askPlayerAction = $askPlayerAction;
        $this->playerHitsAction = $playerHitsAction;
        $this->playerStandsAction = $playerStandsAction;
    }

    public function __invoke(): PlayerActionResult
    {
        try {
            while (true) {
                try {
                    $action = ($this->askPlayerAction)();
                } catch (\UnexpectedValueException $e) {
                    $this->render->error($e->getMessage());
                    continue;
                }

                if ($action->isHit()) {
                    ($this->playerHitsAction)();
                    $this->render->renderGame();
                    continue;
                }

                if ($action->isStand()) {
                    $playerWin = ($this->playerStandsAction)();
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