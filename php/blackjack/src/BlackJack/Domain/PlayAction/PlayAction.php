<?php
declare(strict_types=1);

namespace BlackJack\BlackJack\Domain\PlayAction;

use BlackJack\Enum;

/**
 * @method static self HIT()
 * @method static self STAND()
 *
 * @method bool isHit()
 * @method bool isStand()
 */
final class PlayAction extends Enum
{
    private const HIT = 'hit';

    private const STAND = 'stand';
}
