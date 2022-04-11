<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CattleArrivalStatus extends Enum
{
    const BORNONFARM = 'Born on Farm';
    const DIRECTFROMAUCTION = 'Direct from Auction';
    const DIRECTFROMFARM = 'Direct from Farm';

    const ALL = [
        self::BORNONFARM, self::DIRECTFROMAUCTION, self::DIRECTFROMFARM
    ];
}
