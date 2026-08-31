<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanKredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pengajuan',
        'iduser_marketing',
        'iddealer',
        'status',
        'catatan_reject',
        'iduser_approval',
        'tanggal_submit',
        'tanggal_approval',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusPengajuan::class,
            'tanggal_submit' => 'datetime',
            'tanggal_approval' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function marketing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser_marketing');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser_approval');
    }

    /**
     * @return BelongsTo<MasterDealer, $this>
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(MasterDealer::class, 'iddealer');
    }

    /**
     * @return HasOne<DataKonsumen, $this>
     */
    public function konsumen(): HasOne
    {
        return $this->hasOne(DataKonsumen::class, 'idpengajuan');
    }

    /**
     * @return HasOne<DataKendaraan, $this>
     */
    public function kendaraan(): HasOne
    {
        return $this->hasOne(DataKendaraan::class, 'idpengajuan');
    }

    /**
     * @return HasOne<DataPinjaman, $this>
     */
    public function pinjaman(): HasOne
    {
        return $this->hasOne(DataPinjaman::class, 'idpengajuan');
    }

    /**
     * @return HasMany<PengajuanDokumen, $this>
     */
    public function dokumens(): HasMany
    {
        return $this->hasMany(PengajuanDokumen::class, 'idpengajuan');
    }

    /**
     * @return HasMany<PengajuanHistory, $this>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(PengajuanHistory::class, 'idpengajuan');
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [StatusPengajuan::Draft, StatusPengajuan::Ditolak]);
    }

    public function isSubmittable(): bool
    {
        return $this->status === StatusPengajuan::Draft;
    }

    public function isApprovable(): bool
    {
        return $this->status === StatusPengajuan::MenungguApproval;
    }

    public function isGeneratable(): bool
    {
        return $this->status === StatusPengajuan::Disetujui;
    }
}
