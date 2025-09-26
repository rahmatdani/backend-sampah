<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    /**
     * Menampilkan daftar semua kecamatan
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $kecamatan = Kecamatan::all();
        
        return response()->json([
            'kecamatan' => $kecamatan
        ]);
    }
}
