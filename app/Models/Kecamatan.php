<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    /** @use HasFactory<\Database\Factories\KecamatanFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
    ];

    public function catatanSampah()
    {
        return $this->hasMany(CatatanSampah::class, 'kecamatan_id');
    }

    public function getLabel(): string
    {
        return $this->nama;
    }

    public function getPluralLabel(): string
    {
        return 'Kecamatan';
    }
}
