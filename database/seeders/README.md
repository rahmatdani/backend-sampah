# Database Seeders

Dokumen ini menjelaskan seeder-seeder yang tersedia dalam aplikasi dan cara menggunakannya.

## Daftar Seeder

1. **KecamatanSeeder** - Menambahkan data kecamatan di Kota Makassar dan Kabupaten Gowa
2. **PenggunaSeeder** - Menambahkan pengguna contoh dengan data lengkap
3. **PenelitiSeeder** - Menambahkan pengguna dengan role peneliti (sudah ada)
4. **CatatanSampahSeeder** - Menambahkan catatan sampah contoh untuk pengguna

## Cara Menjalankan Seeder

### Menjalankan Semua Seeder
```bash
php artisan db:seed
```

### Menjalankan Seeder Tertentu
```bash
php artisan db:seed --class=KecamatanSeeder
php artisan db:seed --class=PenggunaSeeder
php artisan db:seed --class=CatatanSampahSeeder
```

## Detail Seeder

### KecamatanSeeder
Menambahkan 32 kecamatan:
- 14 kecamatan di Kota Makassar
- 18 kecamatan di Kabupaten Gowa

### PenggunaSeeder
Menambahkan 5 pengguna contoh dengan role 'user', masing-masing memiliki:
- Nama
- Email
- Password (password123)
- Alamat
- Kecamatan
- Points
- Streak days

### CatatanSampahSeeder
Menambahkan 3-5 catatan sampah untuk setiap pengguna dengan data:
- Jenis sampah (Organik, Plastik, Kertas, Logan, Residu)
- Volume dalam liter
- Berat dalam kg
- Waktu setoran
- Status validasi
- Points

## Factory

Setiap model memiliki factory untuk pengujian:
- KecamatanFactory
- PenggunaFactory
- CatatanSampahFactory

Factory dapat digunakan dalam pengujian atau untuk membuat data dummy:
```php
// Membuat 10 kecamatan
Kecamatan::factory()->count(10)->create();

// Membuat 5 pengguna
Pengguna::factory()->count(5)->create();

// Membuat 20 catatan sampah
CatatanSampah::factory()->count(20)->create();
```