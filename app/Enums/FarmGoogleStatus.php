<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FarmGoogleStatus extends Enum
{
    const ACTIVE = 'Active';
    const INACTIVE = 'InActive';

    const ALL = [
        self::ACTIVE, self::INACTIVE
    ];
}
