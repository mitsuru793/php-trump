<?php
declare(strict_types=1);

namespace Trump\BlackJack\Domain\PlayAction;

use Trump\Enum;

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
