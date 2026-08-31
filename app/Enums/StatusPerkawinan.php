<?php

namespace App\Enums;

enum StatusPerkawinan: string
{
    case BelumKawin = 'BELUM_KAWIN';
    case Kawin = 'KAWIN';
    case Cerai = 'CERAI';

    public function label(): string
    {
        return match ($this) {
            self::BelumKawin => 'Belum Kawin',
            self::Kawin => 'Kawin',
            self::Cerai => 'Cerai',
        };
    }
}
