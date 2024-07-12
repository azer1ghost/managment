<?php

namespace App\Enums;

class InquiryPriority
{
    const UNNECESSARY = 0;
    const LOW = 1;
    const MEDIUM = 2;
    const HIGH = 3;

    public static function toArray()
    {
        return [
            'UNNECESSARY' => self::UNNECESSARY,
            'LOW' => self::LOW,
            'MEDIUM' => self::MEDIUM,
            'HIGH' => self::HIGH,
        ];
    }
}
