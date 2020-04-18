<?php
declare(strict_types=1);

namespace BlackJack\Deck;

use BlackJack\Enum;

/**
 * @method static self HEART()
 * @method static self SPADE()
 * @method static self CLOVER()
 * @method static self DIAMOND()
 *
 * @method bool isHeart()
 * @method bool isSpade()
 * @method bool isClover()
 * @method bool isDiamond()
 */
final class CardMark extends Enum
{
    private const HEART = 'heart';

    private const SPADE = 'spade';

    private const CLOVER = 'clover';

    private const DIAMOND = 'diamond';
}
