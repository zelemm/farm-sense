<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CattleType extends Enum
{
    const COW = 'Cow';

    const ALL = [
        self::COW
    ];
}
