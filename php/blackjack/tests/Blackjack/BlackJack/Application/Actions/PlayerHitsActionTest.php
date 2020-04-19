<?php
declare(strict_types=1);

namespace BlackJack\Application\Actions;

use BlackJack\BlackJack\Application\Actions\PlayerHitsAction;
use BlackJack\BlackJack\Domain\Playable\Player;
use BlackJack\BlackJack\Game\BustException;
use Helper\TestBase;
use Trump\Deck;

class PlayerHitsActionTest extends TestBase
{
    private Deck $deck;

    private Player $player;

    private PlayerHitsAction $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->deck = Deck::create();
        $this->player = new Player('mike', []);
        $this->action = new PlayerHitsAction($this->deck, $this->player);
    }

    public function testDrawCard()
    {
        $card = $this->deck->cards()[0];

        $beforeCount = $this->deck->count();
        ($this->action)();
        $afterCount = $this->deck->count();

        $this->assertEquals(1, $beforeCount - $afterCount);
        $this->assertCount(1, $this->player->cards());
        $this->assertEquals($card, $this->player->cards()[0]);
    }

    public function testThrowsWhenBust()
    {
        $nums = range(1, 5);

        $this->expectException(BustException::class);

        // Convert ace to 10.
        $sum = array_sum($nums) - 1 + 11;
        $err = sprintf('Player %s is bust at %d', $this->player->name(),  $sum);
        $this->expectExceptionMessage($err);

        foreach ($nums as $i) {
            ($this->action)();
        }
    }
}
