<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Sale()
 * @method static static OwnUse()
 * @method static static OwnNeedsHuntingClub()
 * @method static static Utilization()
 * @method static static ASF()
 */
final class HuntedAnimalPurposes extends Enum implements LocalizedEnum
{
    const Sale = 1;
    const OwnUse = 2;
    const OwnNeedsHuntingClub = 3;
    const Utilization = 4;
    const ASF = 5;
}
