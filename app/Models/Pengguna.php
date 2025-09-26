<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\PenggunaFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'alamat',
        'kecamatan_id',
        'avatar_profil_id',
        'points',
        'streak_days',
        'last_scan_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_scan_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function catatanSampah()
    {
        return $this->hasMany(CatatanSampah::class, 'pengguna_id');
    }

    public function avatarProfil()
    {
        return $this->belongsTo(AvatarProfil::class, 'avatar_profil_id');
    }

    public function getLabel(): string
    {
        return $this->nama ?? 'Pengguna';
    }

    public function getPluralLabel(): string
    {
        return 'Pengguna';
    }

    public function getFilamentName(): string
    {
        return $this->nama ?? 'Pengguna';
    }

    public function getNameAttribute(): string
    {
        return $this->nama ?? 'Pengguna';
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Izinkan hanya peneliti dan admin yang bisa mengakses panel admin
        return in_array($this->role ?? '', ['peneliti', 'admin']);
    }

    public function canAccessFilament(): bool
    {
        // Izinkan pengguna dengan role peneliti atau admin
        return in_array($this->role ?? '', ['peneliti', 'admin']);
    }
    
    public function getUserName(): string
    {
        return $this->nama ?? 'Pengguna';
    }
}
