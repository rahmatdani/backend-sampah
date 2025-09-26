<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SetoranSampahRequest;
use App\Models\CatatanSampah;
use App\Models\Pengguna;
use App\Models\JenisSampah;

class SetoranSampahController extends Controller
{
    public function store(SetoranSampahRequest $request)
    {
        // Mendapatkan pengguna yang sedang login
        $pengguna = $request->user();
        
        // Memastikan pengguna memiliki role 'user'
        if ($pengguna->role !== 'user') {
            return response()->json([
                'message' => 'Hanya pengguna biasa yang dapat melakukan setoran sampah'
            ], 403);
        }
        
        // Memastikan pengguna telah melengkapi profil (memiliki kecamatan)
        if (!$pengguna->kecamatan_id) {
            return response()->json([
                'message' => 'Pengguna harus melengkapi profil terlebih dahulu dengan menambahkan kecamatan'
            ], 400);
        }
        
        // Memastikan pengguna memiliki kecamatan
        if (!$pengguna->kecamatan) {
            return response()->json([
                'message' => 'Data kecamatan tidak ditemukan'
            ], 400);
        }
        
        // Validasi dan simpan foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('sampah-foto', 'public');
        }
        
        // Temukan jenis sampah berdasarkan nama
        $jenisSampah = JenisSampah::where('nama', $request->jenis_sampah)->first();
        if (!$jenisSampah) {
            return response()->json([
                'message' => 'Jenis sampah tidak valid'
            ], 400);
        }
        
        // Hitung berat berdasarkan volume dan faktor konversi
        $volume = $request->volume_liter;
        $berat = $volume * $jenisSampah->faktor_konversi;
        
        // Hitung poin berdasarkan jenis sampah dan volume
        $points = $this->hitungPoin($request->jenis_sampah, $request->volume_liter);
        
        // Buat catatan setoran sampah
        $catatanSampah = CatatanSampah::create([
            'pengguna_id' => $pengguna->id,
            'kecamatan_id' => $pengguna->kecamatan_id,
            'jenis_sampah_id' => $jenisSampah->id,
            'jenis_terdeteksi' => $request->jenis_sampah,
            'volume_terdeteksi_liter' => $request->volume_liter,
            'volume_final_liter' => $request->volume_liter,
            'berat_kg' => $berat,
            'foto_path' => $fotoPath,
            'waktu_setoran' => now(),
            'points_diberikan' => $points,
        ]);
        
        // Update poin dan streak pengguna
        $pengguna->increment('points', $points);
        $pengguna->update([
            'streak_days' => $this->hitungStreakHari($pengguna),
            'last_scan_at' => now()
        ]);
        
        // Load relationships untuk response
        $catatanSampah->load(['pengguna', 'kecamatan', 'jenisSampah']);
        
        return response()->json([
            'message' => 'Setoran sampah berhasil disimpan',
            'data' => $catatanSampah,
            'points_ditambahkan' => $points,
            'total_points' => $pengguna->fresh()->points,
            'streak_days' => $pengguna->fresh()->streak_days
        ], 201);
    }
    
    private function hitungPoin($jenisSampah, $volumeLiter)
    {
        // Aturan perhitungan poin berdasarkan jenis sampah
        $pointPerLiter = [
            'Organik' => 10,
            'Plastik' => 15,
            'Kertas' => 12,
            'Logam' => 20,
            'Residu' => 5
        ];
        
        $basePoints = $pointPerLiter[$jenisSampah] ?? 5;
        return round($basePoints * $volumeLiter);
    }
    
    private function hitungStreakHari(Pengguna $pengguna)
    {
        // Cek apakah pengguna setor sampah hari ini
        $hariIni = now()->toDateString();
        $terakhirSetor = $pengguna->last_scan_at ? $pengguna->last_scan_at->toDateString() : null;
        
        // Jika belum pernah setor atau terakhir setor bukan hari ini
        if (!$terakhirSetor || $terakhirSetor !== $hariIni) {
            // Cek apakah setor berturut-turut (kemarin)
            $kemarin = now()->subDay()->toDateString();
            if ($terakhirSetor === $kemarin) {
                // Tambah streak
                return $pengguna->streak_days + 1;
            } else {
                // Reset streak
                return 1;
            }
        }
        
        // Jika sudah setor hari ini, kembalikan streak yang sama
        return $pengguna->streak_days;
    }
}