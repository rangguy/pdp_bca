<?php

namespace App\Models;

use App\Enums\JenisDokumen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanDokumen extends Model
{
    protected $fillable = [
        'idpengajuan',
        'jenis_dokumen',
        'file_path',
        'is_generated',
        'uploaded_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jenis_dokumen' => JenisDokumen::class,
            'is_generated' => 'boolean',
            'uploaded_at' => 'datetime',
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
