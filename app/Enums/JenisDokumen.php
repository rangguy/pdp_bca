<?php

namespace App\Enums;

enum JenisDokumen: string
{
    case Ktp = 'KTP';
    case BuktiBayar = 'BUKTI_BAYAR';
    case FormAplikasi = 'FORM_APLIKASI';
    case KartuKeluarga = 'KARTU_KELUARGA';
    case Kontrak = 'KONTRAK';
    case Po = 'PO';

    public function label(): string
    {
        return match ($this) {
            self::Ktp => 'KTP',
            self::BuktiBayar => 'Bukti Bayar Tanda Jadi',
            self::FormAplikasi => 'Form Aplikasi Pengajuan',
            self::KartuKeluarga => 'Kartu Keluarga',
            self::Kontrak => 'Kontrak',
            self::Po => 'Purchase Order',
        };
    }

    /**
     * Dokumen yang wajib di-upload oleh Marketing.
     *
     * @return array<int, self>
     */
    public static function requiredUploads(): array
    {
        return [
            self::Ktp,
            self::BuktiBayar,
            self::FormAplikasi,
            self::KartuKeluarga,
        ];
    }
}
