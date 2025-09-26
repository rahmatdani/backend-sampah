# API Setoran Sampah

## Perbedaan Role Pengguna

Dalam sistem ini, terdapat dua jenis pengguna:
1. **Pengguna Biasa (User)**: Pengguna mobile yang menggunakan aplikasi Flutter untuk melakukan setoran sampah
2. **Admin/Peneliti**: Pengguna yang memiliki akses ke panel administrasi

Hanya pengguna biasa (role: "user") yang dapat melakukan setoran sampah melalui endpoint ini.

## Autentikasi

Semua endpoint untuk setoran sampah memerlukan autentikasi menggunakan token Bearer. Laravel 12 menggunakan Sanctum untuk autentikasi API.

### Register

```
POST /api/register
```

**Parameters:**
- nama (string, required)
- email (string, required)
- password (string, required)
- password_confirmation (string, required)
- alamat (string, optional)
- kecamatan_id (integer, optional)

**Response:**
```json
{
  "access_token": "1|abcdefghijklmnopqrstuvwxyz",
  "token_type": "Bearer",
  "pengguna": {
    "id": 1,
    "nama": "Nama Pengguna",
    "email": "email@example.com",
    "role": "user",
    "alamat": "Alamat Pengguna",
    "kecamatan_id": 1,
    "points": 0,
    "streak_days": 0,
    // ... data pengguna lainnya
  }
}
```

Catatan: Pengguna yang mendaftar melalui endpoint ini secara otomatis memiliki role "user". Mereka dapat langsung menyertakan alamat dan kecamatan_id saat register, atau melengkapi profil setelah login.

### Login

```
POST /api/login
```

**Parameters:**
- email (string, required)
- password (string, required)

**Response:**
```json
{
  "access_token": "1|abcdefghijklmnopqrstuvwxyz",
  "token_type": "Bearer",
  "pengguna": {
    "id": 1,
    "nama": "Nama Pengguna",
    "email": "email@example.com",
    "role": "user",
    "alamat": "Alamat Pengguna",
    "kecamatan_id": 1,
    "points": 0,
    "streak_days": 0,
    // ... data pengguna lainnya
  }
}
```

Untuk menggunakan token ini dalam request berikutnya, tambahkan header:
```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz
```

## Melengkapi Profil

Setelah login, pengguna dapat melengkapi profil dengan menambahkan alamat dan kecamatan.

### Mendapatkan Data Profil

```
GET /api/profil
```

**Headers:**
- Authorization: Bearer [token]

**Response:**
```json
{
  "pengguna": {
    "id": 1,
    "nama": "Nama Pengguna",
    "email": "email@example.com",
    "role": "user",
    "alamat": "Alamat Pengguna",
    "kecamatan_id": 1,
    "points": 0,
    "streak_days": 0,
    "created_at": "2023-01-01T10:00:00.000000Z",
    "updated_at": "2023-01-01T10:00:00.000000Z",
    "kecamatan": {
      "id": 1,
      "nama": "Kecamatan Example",
      "created_at": "2023-01-01T10:00:00.000000Z",
      "updated_at": "2023-01-01T10:00:00.000000Z"
    }
  }
}
```

### Memperbarui Profil

```
PUT /api/profil
```

**Headers:**
- Authorization: Bearer [token]

**Parameters:**
- alamat (string, optional)
- kecamatan_id (integer, optional)

**Response:**
```json
{
  "message": "Profil berhasil diperbarui",
  "pengguna": {
    "id": 1,
    "nama": "Nama Pengguna",
    "email": "email@example.com",
    "role": "user",
    "alamat": "Alamat Pengguna",
    "kecamatan_id": 1,
    "points": 0,
    "streak_days": 0,
    "created_at": "2023-01-01T10:00:00.000000Z",
    "updated_at": "2023-01-01T11:00:00.000000Z",
    "kecamatan": {
      "id": 1,
      "nama": "Kecamatan Example",
      "created_at": "2023-01-01T10:00:00.000000Z",
      "updated_at": "2023-01-01T10:00:00.000000Z"
    }
  }
}
```

## Setoran Sampah

```
POST /api/setoran-sampah
```

Endpoint ini memerlukan autentikasi dengan token Bearer menggunakan middleware `auth:sanctum`. Hanya pengguna dengan role "user" yang dapat mengakses endpoint ini. Pengguna harus sudah melengkapi profil (menambahkan kecamatan) sebelum dapat melakukan setoran sampah.

Sistem akan secara otomatis menghitung berat sampah berdasarkan volume dan jenis sampah menggunakan faktor konversi:

- Organik: 0,5 kg per liter
- Plastik: 0,04 kg per liter
- Kertas: 0,25 kg per liter
- Logam: 0,8 kg per liter
- Residu: 0,3 kg per liter

**Headers:**
- Authorization: Bearer [token]

**Parameters:**
- jenis_sampah (string, required) - Nilai yang diperbolehkan: "Organik", "Plastik", "Kertas", "Logam", "Residu"
- volume_liter (numeric, required) - Volume dalam liter
- foto (file, required) - Foto bukti setoran sampah

**Response:**
```json
{
  "message": "Setoran sampah berhasil disimpan",
  "data": {
    "id": 1,
    "pengguna_id": 1,
    "kecamatan_id": 1,
    "jenis_sampah_id": 1,
    "jenis_terdeteksi": "Organik",
    "volume_terdeteksi_liter": 2.5,
    "volume_final_liter": 2.5,
    "berat_kg": 1.25,
    "foto_path": "sampah-foto/xyz.jpg",
    "waktu_setoran": "2023-01-01T10:00:00.000000Z",
    "points_diberikan": 25,
    "created_at": "2023-01-01T10:00:00.000000Z",
    "updated_at": "2023-01-01T10:00:00.000000Z",
    "pengguna": {
      // Data pengguna
    },
    "kecamatan": {
      // Data kecamatan
    },
    "jenisSampah": {
      "id": 1,
      "nama": "Organik",
      "kode": "ORG",
      "faktor_konversi": 0.5,
      "deskripsi": "Sampah yang berasal dari bahan alami yang mudah terurai",
      "created_at": "2023-01-01T10:00:00.000000Z",
      "updated_at": "2023-01-01T10:00:00.000000Z"
    }
  },
  "points_ditambahkan": 25,
  "total_points": 25,
  "streak_days": 1
}
```

**Mapping Field:**
- `user_id` → `pengguna_id` (diambil otomatis dari pengguna yang login)
- `kecamatan_id` (diambil otomatis dari profil pengguna)
- `jenis_sampah` → `jenis_terdeteksi`
- `volume_liter` → `volume_terdeteksi_liter`
- `foto_url` → `foto_path` (path relatif di server)
- `timestamp` → `waktu_setoran` (diambil otomatis saat setoran dibuat)
- `points` → `points_diberikan` (dihitung otomatis berdasarkan jenis sampah dan volume)
- `streak_days` (dihitung otomatis dan disimpan di profil pengguna)
- `berat_kg` (dihitung otomatis berdasarkan volume dan faktor konversi jenis sampah)

**Catatan:**
- `user_id` dan `kecamatan_id` diambil secara otomatis dari data profil pengguna
- `timestamp` diambil secara otomatis saat setoran dibuat
- `points` dihitung berdasarkan jenis sampah dan volume:
  - Organik: 10 poin per liter
  - Plastik: 15 poin per liter
  - Kertas: 12 poin per liter
  - Logam: 20 poin per liter
  - Residu: 5 poin per liter
- `berat_kg` dihitung secara otomatis menggunakan rumus: `volume_liter × faktor_konversi`
- `streak_days` dihitung secara otomatis berdasarkan frekuensi setoran
- `foto_url` disimpan sebagai path relatif di server, untuk mendapatkan URL lengkap, gabungkan dengan URL dasar penyimpanan file (misalnya: `http://localhost/storage/sampah-foto/xyz.jpg`)

## Cara Menggunakan API

### 1. Menggunakan Postman

1. **Register:**
   - Method: POST
   - URL: `http://localhost/api/register`
   - Headers: `Content-Type: application/json`
   - Body (raw JSON):
   ```json
   {
     "nama": "Nama Pengguna",
     "email": "email@example.com",
     "password": "password123",
     "password_confirmation": "password123",
     "alamat": "Alamat Pengguna",
     "kecamatan_id": 1
   }
   ```

2. **Login:**
   - Method: POST
   - URL: `http://localhost/api/login`
   - Headers: `Content-Type: application/json`
   - Body (raw JSON):
   ```json
   {
     "email": "email@example.com",
     "password": "password123"
   }
   ```

3. **Melengkapi Profil:**
   - Method: PUT
   - URL: `http://localhost/api/profil`
   - Headers: 
     - `Content-Type: application/json`
     - `Authorization: Bearer [token_dari_login]`
   - Body (raw JSON):
   ```json
   {
     "alamat": "Alamat Pengguna",
     "kecamatan_id": 1
   }
   ```

4. **Setoran Sampah:**
   - Method: POST
   - URL: `http://localhost/api/setoran-sampah`
   - Headers: `Authorization: Bearer [token_dari_login]`
   - Body (form-data):
     - jenis_sampah: Organik
     - volume_liter: 2.5
     - foto: [pilih file gambar]

### 2. Menggunakan cURL

1. **Register:**
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Nama Pengguna",
    "email": "email@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "alamat": "Alamat Pengguna",
    "kecamatan_id": 1
  }'
```

2. **Login:**
```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "email@example.com",
    "password": "password123"
  }'
```

3. **Melengkapi Profil:**
```bash
curl -X PUT http://localhost/api/profil \
  -H "Authorization: Bearer [token_dari_login]" \
  -H "Content-Type: application/json" \
  -d '{
    "alamat": "Alamat Pengguna",
    "kecamatan_id": 1
  }'
```

4. **Setoran Sampah:**
```bash
curl -X POST http://localhost/api/setoran-sampah \
  -H "Authorization: Bearer [token_dari_login]" \
  -H "Content-Type: multipart/form-data" \
  -F "jenis_sampah=Organik" \
  -F "volume_liter=2.5" \
  -F "foto=@/path/to/your/image.jpg"
```

## Contoh Perhitungan Berat

1. **Organik (faktor konversi: 0.5)**
   - Volume: 10 liter
   - Berat: 10 × 0.5 = 5 kg

2. **Plastik (faktor konversi: 0.04)**
   - Volume: 10 liter
   - Berat: 10 × 0.04 = 0.4 kg

3. **Kertas (faktor konversi: 0.25)**
   - Volume: 10 liter
   - Berat: 10 × 0.25 = 2.5 kg

4. **Logam (faktor konversi: 0.8)**
   - Volume: 10 liter
   - Berat: 10 × 0.8 = 8 kg

5. **Residu (faktor konversi: 0.3)**
   - Volume: 10 liter
   - Berat: 10 × 0.3 = 3 kg

## Catatan Penting

1. Pastikan server Laravel sudah berjalan
2. Pastikan database sudah dimigrasi
3. Pastikan ada data kecamatan dengan ID yang digunakan dalam register atau melengkapi profil
4. Pastikan storage link sudah dibuat dengan `php artisan storage:link` agar foto bisa diakses
5. Untuk pengujian, pastikan ada data kecamatan dan jenis sampah yang sudah terdaftar di database