<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    /** @use HasFactory<\Database\Factories\JenisSampahFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'faktor_konversi',
        'deskripsi',
    ];

    protected $casts = [
        'faktor_konversi' => 'decimal:4',
    ];

    public function catatanSampah()
    {
        return $this->hasMany(CatatanSampah::class, 'jenis_sampah_id');
    }

    public function getLabel(): string
    {
        return $this->nama;
    }

    public function getPluralLabel(): string
    {
        return 'Jenis Sampah';
    }
}
