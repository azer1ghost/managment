<?php

namespace App\Enums;

class InquiryType
{
    const CLIENT = 1;
    const COLLABORATION = 2;
    const VENDOR = 3;
    const PARTNER = 4;
    const VACANCY = 5;

    public static function toArray()
    {
        return [
            'CLIENT' => self::CLIENT,
            'COLLABORATION' => self::COLLABORATION,
            'VENDOR' => self::VENDOR,
            'PARTNER' => self::PARTNER,
            'VACANCY' => self::VACANCY,
        ];
    }
}
