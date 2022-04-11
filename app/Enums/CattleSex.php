<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CattleSex extends Enum
{
    const DAM = 'Dam';
    const SIRE = 'Sire';

    const ALL = [
        self::DAM, self::SIRE
    ];
}
