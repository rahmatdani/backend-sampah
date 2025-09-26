<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanSampah extends Model
{
    /** @use HasFactory<\Database\Factories\CatatanSampahFactory> */
    use HasFactory;

    protected $fillable = [
        'pengguna_id',
        'kecamatan_id',
        'jenis_terdeteksi',
        'volume_terdeteksi_liter',
        'berat_kg',
        'foto_path',
        'waktu_setoran',
        'is_divalidasi',
        'points_diberikan',
    ];

    protected $casts = [
        'waktu_setoran' => 'datetime',
        'is_divalidasi' => 'boolean',
        'volume_terdeteksi_liter' => 'decimal:2',
        'berat_kg' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->waktu_setoran) {
                $model->waktu_setoran = now();
            }
        });
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    

    public function getLabel(): string
    {
        return $this->jenis_terdeteksi ?? 'Catatan Sampah';
    }

    public function getPluralLabel(): string
    {
        return 'Catatan Sampah';
    }
}
