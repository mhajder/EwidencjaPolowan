<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static User()
 * @method static static Admin()
 */
final class UserRoles extends Enum implements LocalizedEnum
{
    const User = 1;
    const Admin = 9;
}
