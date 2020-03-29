<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Trump\BlackJack\Playable\Dealer;
use Trump\BlackJack\Playable\Playable;
use Trump\BlackJack\Playable\Player;
use Trump\Deck\Card;
use Trump\Deck\Deck;

final class Renderer
{
    private Dealer $dealer;

    /** @var Player[] */
    private array $players;

    private Deck $deck;

    /**
     * @param Player[] $players
     */
    public function __construct(Dealer $dealer, array $players, Deck $deck)
    {
        $this->dealer = $dealer;
        $this->players = $players;
        $this->deck = $deck;
    }

    public function renderGame(): string
    {
        return $this->line()
            . $this->renderPlayerCards($this->allPlayable());
    }

    public function error(string $message): string
    {
        return $this->line()
            . $this->puts("! $message !");
    }

    /**
     * @param string|string[] $messages
     */
    public function info($messages): string
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }

        $str = $this->line();
        foreach ($messages as $message) {
            $str .= $this->puts("> $message <");
        }
        return $str;
    }

    private function line(): string
    {
        return $this->puts('----------------');
    }

    private function puts(string $str): string
    {
        return $str . PHP_EOL;
    }

    /**
     * @param Playable[] $players
     */
    private function renderPlayerCards(array $players): string
    {
        $str = '';
        foreach ($players as $player) {
            $cards = array_map(
                fn (Card $c) => $this->renderCard($c),
                $player->cards(),
            );

            $player = sprintf(
                '%s %s(% 2d): %s',
                $player->isDealer() ? 'Dealer' : 'Player',
                $player->name(),
                $player->score(),
                implode(' ', $cards),
            );

            $str .= $this->puts($player);
        }
        return $str;
    }

    private function renderCard(Card $card): string
    {
        $markStr = '';
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

        $numStr = '';
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
