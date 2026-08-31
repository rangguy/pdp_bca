<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataKendaraan extends Model
{
    protected $primaryKey = 'idpengajuan';

    public $incrementing = false;

    protected $fillable = [
        'idpengajuan',
        'merk',
        'model',
        'tipe',
        'warna',
        'harga_kendaraan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_kendaraan' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<PengajuanKredit, $this>
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKredit::class, 'idpengajuan');
    }
}
