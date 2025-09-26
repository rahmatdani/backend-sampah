<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return 'users';
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Izinkan hanya pengguna dengan role admin atau peneliti mengakses panel admin
        return in_array($this->role ?? '', ['admin', 'peneliti']);
    }

    public function canAccessFilament(): bool
    {
        // Izinkan pengguna dengan role admin atau peneliti
        return in_array($this->role ?? '', ['admin', 'peneliti']);
    }
    
    public function getFilamentName(): string
    {
        return $this->name ?? $this->role ?? 'User';
    }
    
    public function getNameAttribute(): string
    {
        return $this->name ?? $this->role ?? 'User';
    }
}
