<?php
declare(strict_types=1);

namespace Trump\BlackJack;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Trump\BlackJack\Game\Game;
use Trump\BlackJack\Playable\Dealer;
use Trump\BlackJack\Playable\Player;
use Trump\Deck\Deck;

final class Command extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'blackjack';

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dealer = new Dealer('Claire');
        $players = [
            new Player('Mike'),
            new Player('Jane'),
        ];

        $io = new SymfonyStyle($input, $output);

        $deck = $dealer->shuffle(Deck::create());
        $game = new Game($input, $output, $dealer, $players, $deck);
        $game->run();

        return 0;

    }
}