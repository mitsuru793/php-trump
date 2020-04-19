<?php
declare(strict_types=1);

namespace BlackJack\BlackJack;

use BlackJack\BlackJack\Domain\Game;
use BlackJack\BlackJack\Domain\Playable\Dealer;
use BlackJack\BlackJack\Domain\Playable\Player;
use BlackJack\BlackJack\Game\GameRunner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trump\Deck;

final class Command extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'blackjack';

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dealer = new Dealer('Claire', []);
        $players = [
            new Player('Mike', []),
            new Player('Jane', []),
        ];
        $deck = $dealer->shuffle(Deck::create());
        $game = new Game($dealer, $players, $deck);

        $runner = new GameRunner($input, $output, $game);
        $runner->run();

        return 0;
    }
}