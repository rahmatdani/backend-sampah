<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'konten',
        'foto_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $news) {
            if (blank($news->slug) && filled($news->judul)) {
                $news->slug = static::generateUniqueSlug($news->judul, $news->id);
            }
        });

        static::updating(function (self $news) {
            if ($news->isDirty('judul')) {
                $news->slug = static::generateUniqueSlug($news->judul, $news->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($judul);
        $slug = $baseSlug;
        $counter = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
