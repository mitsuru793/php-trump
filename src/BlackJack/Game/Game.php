<?php
declare(strict_types=1);

namespace Trump\BlackJack\Game;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Playable\Dealer;
use Trump\BlackJack\Playable\Player;
use Trump\BlackJack\PlayerActionResult;
use Trump\BlackJack\Renderer;
use Trump\Deck\Deck;

final class Game
{
    use HasPlayerActions;

    use HasCardActions;

    private InputInterface $input;

    private OutputInterface $output;

    private SymfonyStyle $io;

    private Dealer $dealer;

    /** @var Player[] */
    private array $players;

    private Deck $deck;

    private Renderer $render;

    public function __construct(InputInterface $input, OutputInterface $output, Dealer $dealer, array $players, Deck $deck)
    {
        $this->input = $input;
        $this->output = $output;
        $this->dealer = $dealer;
        $this->players = $players;
        $this->deck = $deck;
        $this->io = new SymfonyStyle($input, $output);
        $this->render = new Renderer($this->io, $this);
    }

    public function run(): void
    {
        $this->passFirstCard();
        $this->cycle();
    }

    public function dealer(): Dealer
    {
        return $this->dealer;
    }

    /**
     * @return Player[]
     */
    public function players(): array
    {
        return $this->players;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    private function cycle(): void
    {
        foreach ($this->players as $player) {
            $this->render->section(sprintf('Player %s turn.', $player->name()));
            $this->dealer->dropCards();
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
