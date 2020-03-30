<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Game\Game;
use Trump\BlackJack\Playable\Dealer;
use Trump\BlackJack\Playable\Playable;
use Trump\BlackJack\Playable\Player;
use Trump\Deck\Card;
use Trump\Deck\Deck;

final class Renderer
{
    /** @var SymfonyStyle */
    private $io;

    private Dealer $dealer;

    /** @var Player[] */
    private array $players;

    private Deck $deck;

    /**
     * @param Player[] $players
     */
    public function __construct(SymfonyStyle $io, Game $game)
    {
        $this->io = $io;
        $this->dealer = $game->dealer();
        $this->players = $game->players();
        $this->deck = $game->deck();
    }

    public function renderGame(): void
    {
        $this->renderPlayerCards($this->allPlayable());
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
                fn (Card $c) => $this->renderCard($c),
                $player->cards(),
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
        return array_merge([$this->dealer], $this->players);
    }
}
