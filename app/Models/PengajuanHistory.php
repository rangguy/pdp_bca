<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'idpengajuan',
        'status_sebelum',
        'status_sesudah',
        'iduser',
        'catatan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<PengajuanKredit, $this>
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKredit::class, 'idpengajuan');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser');
    }
}
