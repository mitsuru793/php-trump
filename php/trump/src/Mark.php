<?php
declare(strict_types=1);

namespace Trump;

/**
 * @method static self HEART()
 * @method static self SPADE()
 * @method static self CLOVER()
 * @method static self DIAMOND()
 */
final class Mark extends \MyCLabs\Enum\Enum
{
    private const HEART = 'heart';

    private const SPADE = 'spade';

    private const CLOVER = 'clover';

    private const DIAMOND = 'diamond';
}
