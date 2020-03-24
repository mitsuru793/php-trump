<?php
declare(strict_types=1);

namespace Trump;

final class CardMark
{
    const HEART = 'heart';

    const SPADE = 'spade';

    const CLOVER = 'clover';

    const DIAMOND = 'diamond';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return self[]
     */
    public static function all(): array
    {
        return [
            new self(self::HEART),
            new self(self::SPADE),
            new self(self::CLOVER),
            new self(self::DIAMOND),
        ];
    }

    public static function hart(): self
    {
        return new self(self::HEART);
    }

    public static function spade(): self
    {
        return new self(self::SPADE);
    }

    public static function clover(): self
    {
        return new self(self::CLOVER);
    }

    public static function diamond(): self
    {
        return new self(self::DIAMOND);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isHeart(): bool
    {
        return $this->value === self::HEART;
    }

    public function isSpade(): bool
    {
        return $this->value === self::SPADE;
    }

    public function isClover(): bool
    {
        return $this->value === self::CLOVER;
    }

    public function isDiamond(): bool
    {
        return $this->value === self::DIAMOND;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}