<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password', 'nama_lengkap', 'role', 'status_user'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * @return HasMany<PengajuanKredit, $this>
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(PengajuanKredit::class, 'iduser_marketing');
    }

    /**
     * @return HasMany<Notifikasi, $this>
     */
    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'iduser');
    }

    public function isMarketing(): bool
    {
        return $this->role === UserRole::Marketing;
    }

    public function isAtasanMarketing(): bool
    {
        return $this->role === UserRole::AtasanMarketing;
    }

    public function isAdminBackoffice(): bool
    {
        return $this->role === UserRole::AdminBackoffice;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function unreadNotifikasiCount(): int
    {
        return $this->notifikasis()->where('is_read', false)->count();
    }
}
