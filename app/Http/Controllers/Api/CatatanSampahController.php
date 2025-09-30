<?php

namespace App\Http\Controllers\Api;

use App\Models\CatatanSampah;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CatatanSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $catatanSampah = CatatanSampah::with(['pengguna', 'kecamatan'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $catatanSampah
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            \Log::info('CatatanSampahController@store called', ['request_data' => $request->all()]);
            
            // Validasi input dasar
            $validator = Validator::make($request->all(), [
                'pengguna_id' => 'required|exists:penggunas,id',
                'kecamatan_id' => 'required|exists:kecamatans,id',
                'jenis_terdeteksi' => 'required|string|max:255',
                'volume_terdeteksi_liter' => 'required|numeric|min:0',
                'berat_kg' => 'required|numeric|min:0',
                'foto' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:10240', // Validasi file upload
                'waktu_setoran' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            \Log::info('Validation passed, processing file upload');

            // Upload file ke folder storage/app/public/sampah-foto
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                
                \Log::info('File received', [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_mime' => $file->getMimeType()
                ]);
                
                // Cek apakah file valid
                if (!$file->isValid()) {
                    \Log::error('File upload not valid', ['error' => $file->getError()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'File foto tidak valid'
                    ], 422);
                }
                
                // Buat direktori jika belum ada
                \Storage::makeDirectory('public/sampah-foto');
                \Log::info('Directory created or already exists', ['directory' => 'storage/app/public/sampah-foto']);
                
                // Generate nama file unik
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Simpan file ke storage/app/public/sampah-foto menggunakan disk 'public'
                $path = $file->storeAs('sampah-foto', $fileName, 'public');
                \Log::info('File stored with path', ['path' => $path]);
                
                // Cek apakah file benar-benar disimpan
                $fullPath = storage_path('app/public/' . $path);
                \Log::info('Checking file existence', ['full_path' => $fullPath]);
                
                if (!file_exists($fullPath)) {
                    \Log::error('File was not saved to expected location', [
                        'path' => $fullPath,
                        'directory_exists' => is_dir(dirname($fullPath)),
                        'directory_readable' => is_readable(dirname($fullPath)),
                        'directory_writable' => is_writable(dirname($fullPath))
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menyimpan file foto'
                    ], 500);
                }
                
                $fotoPath = 'storage/' . $path; // Path yang akan disimpan ke database
                \Log::info('File saved successfully', ['path' => $fotoPath, 'full_path' => $fullPath]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File foto tidak ditemukan'
                ], 422);
            }

            \Log::info('File uploaded successfully', ['path' => $fotoPath]);
            
            $data = [
                'pengguna_id' => $request->pengguna_id,
                'kecamatan_id' => $request->kecamatan_id,
                'jenis_terdeteksi' => $request->jenis_terdeteksi,
                'volume_terdeteksi_liter' => $request->volume_terdeteksi_liter,
                'berat_kg' => $request->berat_kg,
                'foto_path' => $fotoPath, // Simpan path yang sudah disimpan
                'is_divalidasi' => 0, // Default value
                'points_diberikan' => 0, // Default value
                'waktu_setoran' => $request->waktu_setoran ?? now()
            ];
            
            $catatanSampah = CatatanSampah::create($data);
            
            \Log::info('Catatan sampah created successfully', ['id' => $catatanSampah->id, 'data' => $data]);

            $response = response()->json([
                'success' => true,
                'message' => 'Catatan sampah berhasil ditambahkan',
                'data' => $catatanSampah
            ], 201);
            
            \Log::info('Response prepared, sending');
            
            return $response;
            
        } catch (\Exception $e) {
            \Log::error('Error creating catatan sampah: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $catatanSampah = CatatanSampah::with(['pengguna', 'kecamatan'])->find($id);

        if (!$catatanSampah) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan sampah tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $catatanSampah
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $catatanSampah = CatatanSampah::find($id);

        if (!$catatanSampah) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan sampah tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'sometimes|required|exists:penggunas,id',
            'kecamatan_id' => 'sometimes|required|exists:kecamatans,id',
            'jenis_terdeteksi' => 'sometimes|required|string|max:255',
            'volume_terdeteksi_liter' => 'sometimes|required|numeric|min:0',
            'berat_kg' => 'sometimes|required|numeric|min:0',
            'foto_path' => 'sometimes|required|string|max:255',
            'waktu_setoran' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $catatanSampah->update([
            'pengguna_id' => $request->pengguna_id ?? $catatanSampah->pengguna_id,
            'kecamatan_id' => $request->kecamatan_id ?? $catatanSampah->kecamatan_id,
            'jenis_terdeteksi' => $request->jenis_terdeteksi ?? $catatanSampah->jenis_terdeteksi,
            'volume_terdeteksi_liter' => $request->volume_terdeteksi_liter ?? $catatanSampah->volume_terdeteksi_liter,
            'berat_kg' => $request->berat_kg ?? $catatanSampah->berat_kg,
            'foto_path' => $request->foto_path ?? $catatanSampah->foto_path,
            'waktu_setoran' => $request->waktu_setoran ?? $catatanSampah->waktu_setoran
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan sampah berhasil diupdate',
            'data' => $catatanSampah
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $catatanSampah = CatatanSampah::find($id);

        if (!$catatanSampah) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan sampah tidak ditemukan'
            ], 404);
        }

        $catatanSampah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan sampah berhasil dihapus'
        ]);
    }
    
    /**
     * Validate a catatan sampah (admin only).
     */
    public function validateCatatan(Request $request, string $id): JsonResponse
    {
        $catatanSampah = CatatanSampah::find($id);

        if (!$catatanSampah) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan sampah tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'is_divalidasi' => 'required|boolean',
            'points_diberikan' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $catatanSampah->update([
            'is_divalidasi' => $request->is_divalidasi,
            'points_diberikan' => $request->points_diberikan,
            'divalidasi_oleh' => auth()->id() // Set current user as validator
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Validasi catatan sampah berhasil',
            'data' => $catatanSampah
        ]);
    }
    
    /**
     * Check if pengguna_id and kecamatan_id are valid using GET method
     */
    public function checkIds(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'required|exists:penggunas,id',
            'kecamatan_id' => 'required|exists:kecamatans,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ID tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // Jika semua ID valid
        return response()->json([
            'success' => true,
            'message' => 'ID pengguna dan kecamatan valid',
            'data' => [
                'pengguna_id' => $request->pengguna_id,
                'kecamatan_id' => $request->kecamatan_id
            ]
        ]);
    }
    
    /**
     * Get authenticated user's information
     */
    public function getUserInfo(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau tidak terotentikasi'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi pengguna ditemukan',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'role' => $user->role,
                'alamat' => $user->alamat,
                'kecamatan_id' => $user->kecamatan_id,
                'points' => $user->points ?? 0,
                'streak_days' => $user->streak_days ?? 0
            ]
        ]);
    }

    /**
     * Menampilkan riwayat setoran sampah untuk pengguna tertentu.
     */
    public function history(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'required|integer|exists:penggunas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $penggunaId = (int) $request->input('pengguna_id');

        $riwayat = CatatanSampah::query()
            ->where('pengguna_id', $penggunaId)
            ->orderByDesc('waktu_setoran')
            ->get([
                'id',
                'jenis_terdeteksi as jenis_sampah',
                'volume_terdeteksi_liter as volume_liter',
                'berat_kg as berat_kg',
                'is_divalidasi',
                'waktu_setoran',
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat setoran ditemukan',
            'data' => $riwayat,
        ]);
    }
}