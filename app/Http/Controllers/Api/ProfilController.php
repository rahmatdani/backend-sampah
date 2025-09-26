<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function updateProfil(Request $request)
    {
        $pengguna = $request->user();
        
        // Validasi input
        $request->validate([
            'nama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Siapkan data untuk update
        $dataToUpdate = [];
        
        if ($request->filled('nama')) {
            $dataToUpdate['nama'] = $request->nama;
        }
        
        if ($request->filled('alamat')) {
            $dataToUpdate['alamat'] = $request->alamat;
        }
        
        if ($request->filled('kecamatan_id')) {
            $dataToUpdate['kecamatan_id'] = $request->kecamatan_id;
        }
        
        // Khusus untuk password, kita hash terlebih dahulu
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }
        
        // Update profil pengguna
        $pengguna->update($dataToUpdate);
        
        // Load relationships
        $pengguna->load(['kecamatan', 'avatarProfil']);
        
        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'pengguna' => $pengguna
        ]);
    }
    
    public function getProfil(Request $request)
    {
        $pengguna = $request->user();
        
        // Load relationships
        $pengguna->load(['kecamatan', 'avatarProfil']);
        
        return response()->json([
            'pengguna' => $pengguna
        ]);
    }
}