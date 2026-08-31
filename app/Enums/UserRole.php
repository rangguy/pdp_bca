<?php

namespace App\Enums;

enum UserRole: string
{
    case Marketing = 'marketing';
    case AtasanMarketing = 'atasan_marketing';
    case AdminBackoffice = 'admin_backoffice';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Marketing => 'Marketing',
            self::AtasanMarketing => 'Atasan Marketing',
            self::AdminBackoffice => 'Admin Backoffice',
            self::Admin => 'Admin',
        };
    }
}
