<?php
declare(strict_types=1);

namespace Trump;

final class Card
{
    private CardMark $mark;

    private CardNumber $number;

    public function __construct(CardMark $mark, CardNumber $number)
    {
        $this->mark = $mark;
        $this->number = $number;
    }

    public function mark(): CardMark
    {
        return $this->mark;
    }

    public function number(): CardNumber
    {
        return $this->number;
    }

    public function equals(self $other): bool
    {
        return $this->mark->equals($other->mark)
            && $this->number->equals($other->number);
    }
}
