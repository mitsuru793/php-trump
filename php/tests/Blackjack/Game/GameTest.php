<?php
declare(strict_types=1);

namespace Trump\Blackjack\Game;

use Helper\TestBase;
use Trump\BlackJack\Domain\Playable\Dealer;
use Trump\BlackJack\Domain\Playable\Player;
use Trump\BlackJack\Renderer;
use Trump\Deck\Deck;
use Trump\Stream\Input;
use Trump\Stream\Output;

final class GameTest extends TestBase
{
    public function testRun()
    {
        $in = fopen('php://memory', 'w');;
        $out = fopen('php://memory', 'r+');;

        $dealer = new Dealer('Claire');
        $players = [
            new Player('Mike'),
            new Player('Jane'),
        ];

        $deck = Deck::create();

        $game = new GameRunner(
            new Input($in), new Output($out),
            $dealer, $players, $deck,
        );

        fwrite($in, 'stand' . PHP_EOL);
        fwrite($in, 'stand' . PHP_EOL);
        fwrite($in, 'stand' . PHP_EOL);
        $game->run();

        $render = new Renderer($dealer, $players, $deck);

        $expected = <<<EOF
        Dealer Claire( 0):
        Player Mike( 1): [♠1]
        Player Jane( 1): [♠1]
        EOF;

        rewind($out);
        $this->assertEquals($expected, stream_get_contents($out));
    }
}