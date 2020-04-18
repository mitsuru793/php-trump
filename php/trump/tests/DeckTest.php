<?php
declare(strict_types=1);

namespace Trump;

use Helper\TestBase;

class DeckTest extends TestBase
{
    public function testCreate()
    {
        $deck = Deck::create();

        $markCards = [
            'heart' => [],
            'clover' => [],
            'diamond' => [],
            'spade' => [],
        ];
        $markCards = array_reduce($deck->cards(), function (array $markCards, Card $card) {
            $mark = $card->mark();
            if ($mark->equals(Mark::HEART())) {
                array_push($markCards['heart'], $card);
            } elseif ($mark->equals(Mark::CLOVER())) {
                array_push($markCards['clover'], $card);
            } elseif ($mark->equals(Mark::DIAMOND())) {
                array_push($markCards['diamond'], $card);
            } elseif ($mark->equals(Mark::SPADE())) {
                array_push($markCards['spade'], $card);
            } else {
                $err = sprintf('Invalid card mark: %s', $mark);
                throw new \RuntimeException($err);
            }
            return $markCards;
        }, $markCards);

        $this->assertCount(13, $markCards['heart']);
        $this->assertCount(13, $markCards['clover']);
        $this->assertCount(13, $markCards['diamond']);
        $this->assertCount(13, $markCards['spade']);

        foreach ($markCards as $mark => $cards) {
            $nums = range(1, 13);
            foreach ($cards as $card) {
                assert($card instanceof Card);

                $this->assertSame($mark, $card->mark()->getValue());

                $found = array_search($card->number()->value(), $nums, true) !== false;
                $this->assertTrue($found);
            }
        }
    }

    public function testDraw()
    {
        $cards = [
            new Card(Mark::HEART(), Number::of(1)),
            new Card(Mark::SPADE(), Number::of(2)),
        ];
        $deck = new Deck($cards);

        $drawn = $deck->draw();
        $this->assertObjectEquals(Number::of(1), $drawn->number());
        $this->assertObjectEquals(Mark::HEART(), $drawn->mark());
        $this->assertCount(1, $deck);

        $drawn = $deck->draw();
        $this->assertObjectEquals(Number::of(2), $drawn->number());
        $this->assertObjectEquals(Mark::SPADE(), $drawn->mark());
        $this->assertCount(0, $deck);

        $this->expectException(\BadMethodCallException::class);
        $deck->draw();
    }
}
