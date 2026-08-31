<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterDealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_dealer',
        'alamat',
    ];

    /**
     * @return HasMany<PengajuanKredit, $this>
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(PengajuanKredit::class, 'iddealer');
    }
}
