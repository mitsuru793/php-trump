<?php
declare(strict_types=1);

namespace BlackJack\Deck;

use Helper\TestBase;

class CardsTest extends TestBase
{
    /**
     * @dataProvider maxScoreProvider
     */
    public function testMaxScore(array $nums, int $expected)
    {
        $max = $this->fromNumbers($nums)->maxScore();
        $this->assertSame($expected, $max);
    }

    public function maxScoreProvider()
    {
        return [
            [[], 0],
            [[1], 11],
            [[2], 2],
            [[2, 5], 7],
            [[1, 10], 21],
            [[1, 11], 21],
            [[2, 10, 11], 22],
        ];
    }


    private function fromNumbers(array $nums): Cards
    {
        $cards = array_map(fn ($num) => new Card(CardMark::SPADE(), CardNumber::of($num)), $nums);
        return new Cards($cards);
    }
}
