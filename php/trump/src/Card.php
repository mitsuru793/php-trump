<?php
declare(strict_types=1);

namespace Trump;

final class Card
{
    private Mark $mark;

    private Number $number;

    public function __construct(Mark $mark, Number $number)
    {
        $this->mark = $mark;
        $this->number = $number;
    }

    public function mark(): Mark
    {
        return $this->mark;
    }

    public function number(): Number
    {
        return $this->number;
    }

    public function equals(self $other): bool
    {
        return $this->mark->equals($other->mark)
            && $this->number->equals($other->number);
    }
}
