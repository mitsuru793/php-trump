<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Domain\Game;
use Trump\BlackJack\Domain\Playable\Playable;
use Trump\BlackJack\Domain\Playable\Player;
use Trump\BlackJack\Domain\PlayAction\PlayerActionResult;
use Trump\BlackJack\Game\BustException;
use Trump\Deck\Card;

final class Renderer
{
    /** @var SymfonyStyle */
    private $io;

    private Game $game;

    /**
     * @param Player[] $players
     */
    public function __construct(SymfonyStyle $io, Game $game)
    {
        $this->io = $io;
        $this->game = $game;
    }

    public function renderResult(Player $player, PlayerActionResult $result)
    {
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
        $this->info($messages);
    }

    public function renderGame(): void
    {
        $this->renderPlayerCards($this->allPlayable());
    }

    public function section(string $message): void
    {
        $this->io->section($message);
    }

    public function error(string $message): void
    {
        $this->io->error($message);
    }

    /**
     * @param string|string[] $messages
     */
    public function info($messages): void
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }

        foreach ($messages as $message) {
            $this->io->text($message);
        }
    }

    /**
     * @param Playable[] $players
     */
    private function renderPlayerCards(array $players): void
    {
        $data = [];
        foreach ($players as $player) {
            $cards = array_map(
                fn(Card $c) => $this->renderCard($c),
                iterator_to_array($player->cards()),
            );

            $key = sprintf('%s %s (% d)',
                $player->isDealer() ? 'Dealer' : 'Player',
                $player->name(),
                $player->score(),
            );
            $data[] = [$key => empty($cards) ? 'Nothing' : implode(' ', $cards)];
        }
        $this->io->definitionList(...$data);
    }

    private function renderCard(Card $card): string
    {
        $markStr = null;
        $mark = $card->mark();
        if ($mark->isSpade()) {
            $markStr = '♥';
        } elseif ($mark->isHeart()) {
            $markStr = '♠';
        } elseif ($mark->isClover()) {
            $markStr = '♣';
        } elseif ($mark->isDiamond()) {
            $markStr = '♦';
        }

        $numStr = null;
        $num = $card->number()->value();
        switch ($num) {
            case 13:
                $numStr = 'K';
                break;
            case 12:
                $numStr = 'Q';
                break;
            case 11:
                $numStr = 'J';
                break;
            default:
                $numStr = $num;
        }

        return sprintf('[%s%s]', $markStr, $numStr);
    }

    /**
     * @return Playable[]
     */
    private function allPlayable(): array
    {
        return array_merge([$this->game->dealer()], $this->game->players());
    }
}
