<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const ADMIN = 'admin';
    const SUPER_ADMIN = 'super_admin';
    const GUESTS = 'guest';
    const FARM_HANDS = 'farm_hands';

    const ALL = [
        self::FARM_HANDS, self::GUESTS, self::ADMIN, self::SUPER_ADMIN
    ];
}
