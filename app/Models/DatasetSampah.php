<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetSampah extends Model
{
    /** @use HasFactory<\Database\Factories\DatasetSampahFactory> */
    use HasFactory;

    protected $fillable = [
        'label',
        'path_file',
        'uploaded_by',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'uploaded_by');
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPluralLabel(): string
    {
        return 'Dataset Sampah';
    }
}
