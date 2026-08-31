<?php

namespace App\Models;

use App\Enums\StatusPerkawinan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataKonsumen extends Model
{
    protected $primaryKey = 'idpengajuan';

    public $incrementing = false;

    protected $fillable = [
        'idpengajuan',
        'nama',
        'nik',
        'tanggal_lahir',
        'status_perkawinan',
        'nama_pasangan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'status_perkawinan' => StatusPerkawinan::class,
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
