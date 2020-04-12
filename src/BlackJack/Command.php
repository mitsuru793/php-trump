<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trump\BlackJack\Domain\Game;
use Trump\BlackJack\Domain\Playable\Dealer;
use Trump\BlackJack\Domain\Playable\Player;
use Trump\BlackJack\Game\GameRunner;
use Trump\Deck\Cards;
use Trump\Deck\Deck;

final class Command extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'blackjack';

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dealer = new Dealer('Claire', Cards::empty());
        $players = [
            new Player('Mike', Cards::empty()),
            new Player('Jane', Cards::empty()),
        ];
        $deck = $dealer->shuffle(Deck::create());
        $game = new Game($dealer, $players, $deck);

        $runner = new GameRunner($input, $output, $game);
        $runner->run();

        return 0;
    }
}