<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Menampilkan daftar berita untuk aplikasi mobile.
     */
    public function index(): JsonResponse
    {
        $news = News::query()
            ->latest('created_at')
            ->get()
            ->map(fn (News $item) => [
                'judul' => $item->judul,
                'slug' => $item->slug,
                'kategori' => $item->kategori,
                'konten' => $item->konten,
                'foto_url' => $item->foto_path ? url('/storage/' . ltrim($item->foto_path, '/')) : null,
                'created_at' => $item->created_at,
            ]);

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    /**
     * Menampilkan detail berita berdasarkan slug.
     */
    public function show(string $slug): JsonResponse
    {
        $news = News::query()
            ->where('slug', $slug)
            ->first();

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'judul' => $news->judul,
                'slug' => $news->slug,
                'kategori' => $news->kategori,
                'konten' => $news->konten,
                'foto_url' => $news->foto_path ? url('/storage/' . ltrim($news->foto_path, '/')) : null,
                'created_at' => $news->created_at,
                'updated_at' => $news->updated_at,
            ],
        ]);
    }
}
