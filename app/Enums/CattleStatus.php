<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CattleStatus extends Enum
{
    const ALIVE = 'Alive';
    const DEAD = 'Dead';
    const SOLD = 'Sold';

    const ALL = [
        self::ALIVE, self::DEAD, self::SOLD
    ];
}
