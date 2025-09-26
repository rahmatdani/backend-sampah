# 📘 Summary Aplikasi: Smart Waste Detection System

## 🎯 Tujuan
Aplikasi ini bertujuan untuk:
- **Mengelola data sampah** rumah tangga secara digital.  
- **Mendeteksi jenis sampah otomatis** menggunakan *Machine Learning (ML)* berbasis gambar.  
- **Mendukung penelitian & pengelolaan sampah** dengan dataset yang bisa terus diperbarui.  
- **Memberikan insentif (points & streak)** kepada warga yang konsisten mengunggah data.  

---

## 🏗️ Arsitektur Sistem

```
Flutter (Mobile) ---API---> Laravel 12 (Backend)
                                |
                                |--- Filament Admin (Web)
                                |--- Storage (Foto, Dataset)
                                |--- Queue (Jobs)
                                |
                                └--> Python ML Service (FastAPI/Flask)
```

---

## 🗄️ Skema Database (Bahasa Indonesia)

### Tabel Utama
1. **kecamatan**  
   - id, nama  

2. **rumah_tangga**  
   - id, kode, nama_kepala_keluarga, kecamatan_id  

3. **pengguna**  
   - id, nama, email, password, role (`user`, `peneliti`, `admin`)  
   - rumah_tangga_id, points, streak_days, last_scan_at  

4. **catatan_sampah**  
   - id, pengguna_id, rumah_tangga_id, kecamatan_id  
   - jenis_terdeteksi, jenis_manual  
   - volume_terdeteksi_liter, volume_manual_liter, volume_final_liter  
   - berat_kg, foto_path, waktu_setoran  
   - is_divalidasi, divalidasi_oleh, points_diberikan  

5. **dataset_sampah** (khusus training ML)  
   - id, label (organik, plastik, kertas, logam, residu)  
   - path_file (folder dataset/image)  
   - uploaded_by (pengguna_id, biasanya admin/peneliti)  

---

## ⚙️ Backend (Laravel 12)

### 🔹 API Endpoint
1. **Autentikasi**
   - `POST /api/login`
   - `POST /api/register`

2. **Catatan Sampah**
   - `POST /api/catatan-sampah/upload` → upload foto sampah  
   - `GET /api/catatan-sampah?pengguna_id=1` → daftar catatan  

3. **Dataset (Admin/Peneliti)**
   - `POST /api/dataset/upload` → unggah dataset training (zip/folder atau image per label)  
   - `GET /api/dataset` → lihat dataset yang sudah masuk  

4. **Training ML**
   - `POST /api/ml/train` → trigger training di Python ML Service  
   - `GET /api/ml/status` → status training  

5. **Prediksi**
   - `POST /api/ml/predict` → upload foto → return jenis sampah + confidence  

---

### 🔹 Filament (Admin Panel)
- **Manajemen Pengguna**
- **Manajemen Rumah Tangga**
- **Manajemen Kecamatan**
- **Validasi Catatan Sampah**
- **Upload Dataset untuk ML Training**
- **Monitor Hasil Prediksi & Statistik**

---

## 🤖 Machine Learning Service (Python)

### Arsitektur ML
- **Framework**: FastAPI / Flask  
- **Model**: CNN (misalnya MobileNetV2 / EfficientNet untuk image classification)  
- **Dataset**: diupload via Laravel → tersimpan di `storage/dataset/` → Python service akses via shared folder atau API  

### Endpoint ML Service
1. `POST /train` → training ulang model dengan dataset terbaru.  
2. `POST /predict` → input: gambar → output: `{jenis:"plastik", confidence:0.92, volume:1.5}`  
3. `GET /status` → status training/prediksi.  

### Workflow ML
1. Admin unggah dataset → Laravel simpan ke folder.  
2. Laravel panggil `POST /train` ke Python service.  
3. Model terlatih disimpan (`model.h5 / model.pt`).  
4. Flutter upload gambar sampah → Laravel → Python `/predict` → hasil kembali → Laravel simpan di `catatan_sampah`.  

---

## 📱 Aplikasi Mobile (Flutter)
- **Login & Register** (role user).  
- **Scan Sampah (kamera)** → foto dikirim ke backend.  
- **Hasil Deteksi Real-time** → tampilkan jenis & berat estimasi.  
- **Riwayat Setoran Sampah**.  
- **Poin & Streak Harian** (sistem reward).  

---

## 📦 Flow Penggunaan
1. **Admin/Peneliti** upload dataset baru di Filament.  
2. Backend Laravel → panggil Python ML Service untuk retraining.  
3. **User (Mobile Flutter)** foto sampah → backend → ML predict.  
4. Backend simpan hasil prediksi + manual correction (jika user edit).  
5. Data validasi bisa dipakai sebagai **dataset tambahan** untuk training berikutnya.  

---

## 🚀 Roadmap
- **v1.0** → Backend + API + Upload Foto + Dummy ML (queue).  
- **v2.0** → Integrasi Python ML Service dengan dataset real.  
- **v3.0** → Implementasi gamifikasi (points, leaderboard).  
- **v4.0** → Dashboard analitik (grafik per kecamatan, jenis sampah terbanyak, tren waktu).  

---

## ✅ Catatan Implementasi
- **Backend Laravel 12** fokus sebagai API provider & manajemen data.  
- **Filament** hanya untuk admin/peneliti (upload dataset, validasi, monitoring).  
- **Python ML** fokus training & prediksi.  
- **Mobile Flutter** untuk user akhir (scan sampah & lihat poin).  
