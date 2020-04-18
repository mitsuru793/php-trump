<?php
declare(strict_types=1);

namespace BlackJack\Deck;

use InvalidArgumentException;

final class CardNumber
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 1 || 13 < $value) {
            $err = sprintf('Card number must be between 1 and 13, but %d.', $value);
            throw new InvalidArgumentException($err);
        }
        $this->value = $value;
    }

    public static function of(int $number): self
    {
        return new self($number);
    }

    /**
     * @return self[]
     */
    public static function all(): array
    {
        return array_map(function (int $n) {
            return new self($n);
        }, range(1, 13));
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(CardNumber $other): bool
    {
        return $this->value === $other->value;
    }
}
