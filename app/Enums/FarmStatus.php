<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FarmStatus extends Enum
{
    const DRAFT = 'Draft';
    const ACTIVE = 'Active';
    const INACTIVE = 'InActive';

    const ALL = [
        self::ACTIVE, self::DRAFT, self::INACTIVE
    ];
}
