<?php

namespace App\Enums;

enum StatusPengajuan: string
{
    case Draft = 'DRAFT';
    case MenungguApproval = 'MENUNGGU_APPROVAL';
    case Disetujui = 'DISETUJUI';
    case Ditolak = 'DITOLAK';
    case DokumenSiap = 'DOKUMEN_SIAP';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::MenungguApproval => 'Menunggu Approval',
            self::Disetujui => 'Disetujui',
            self::Ditolak => 'Ditolak',
            self::DokumenSiap => 'Dokumen Siap',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::MenungguApproval => 'amber',
            self::Disetujui => 'emerald',
            self::Ditolak => 'red',
            self::DokumenSiap => 'blue',
        };
    }
}
