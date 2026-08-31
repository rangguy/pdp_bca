<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPinjaman extends Model
{
    protected $table = 'data_pinjamans';

    protected $primaryKey = 'idpengajuan';

    public $incrementing = false;

    protected $fillable = [
        'idpengajuan',
        'asuransi',
        'down_payment',
        'lama_kredit_bulan',
        'angsuran_per_bulan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'down_payment' => 'decimal:2',
            'angsuran_per_bulan' => 'decimal:2',
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
