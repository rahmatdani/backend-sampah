<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatatanSampahController;
use App\Http\Controllers\Api\SetoranSampahController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\DatasetController;
use App\Http\Controllers\Api\MlController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\AvatarProfilController;

// Autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Kecamatan
Route::get('/kecamatan', [KecamatanController::class, 'index']);

// Avatar Profil
Route::get('/avatars', [AvatarProfilController::class, 'index']);

// Catatan Sampah (protected routes)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/catatan-sampah', [CatatanSampahController::class, 'index']);
    Route::post('/catatan-sampah', [CatatanSampahController::class, 'store']);
    
    // More specific routes first
    Route::get('/catatan-sampah/check-ids', [CatatanSampahController::class, 'checkIds']);
    Route::get('/catatan-sampah/user-info', [CatatanSampahController::class, 'getUserInfo']);
    
    // Specific ID-based routes
    Route::get('/catatan-sampah/{catatanSampah}', [CatatanSampahController::class, 'show']);
    
    // Admin validation route
    Route::post('/catatan-sampah/{catatanSampah}/validate', [CatatanSampahController::class, 'validateCatatan']);
});

// Setoran Sampah dan Profil (dengan autentikasi)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/setoran-sampah', [SetoranSampahController::class, 'store']);
    Route::get('/profil', [ProfilController::class, 'getProfil']);
    Route::put('/profil', [ProfilController::class, 'updateProfil']);
    Route::put('/profil/avatar', [AvatarProfilController::class, 'updateAvatar']);
    Route::post('/profil/avatar/upload', [AvatarProfilController::class, 'uploadAvatar']);
});

// Dataset (Admin/Peneliti)
Route::post('/dataset/upload', [DatasetController::class, 'upload']);
Route::get('/dataset', [DatasetController::class, 'index']);

// Training ML
Route::post('/ml/train', [MlController::class, 'train']);
Route::get('/ml/status', [MlController::class, 'status']);

// Prediksi
Route::post('/ml/predict', [MlController::class, 'predict']);
