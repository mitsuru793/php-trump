<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\Score;

use Helper\TestBase;
use Trump\Card;
use Trump\Mark;
use Trump\Number;

final class ScoreTest extends TestBase
{
    /**
     * @dataProvider maxScoreProvider
     */
    public function testMaxScore(array $nums, int $expected)
    {
        $cards = $this->fromNumbers($nums);
        $this->assertSame($expected, Score::maxFromCards($cards)->value());
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


    private function fromNumbers(array $nums): array
    {
        return array_map(fn($num) => new Card(Mark::SPADE(), Number::of($num)), $nums);
    }
}