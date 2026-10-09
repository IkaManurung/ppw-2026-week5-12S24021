# SIPUS-Del
### Sistem Informasi Perpustakaan Kampus Del

SIPUS-Del merupakan aplikasi web sederhana untuk mengelola data buku dan kategori buku pada perpustakaan kampus. Aplikasi ini dikembangkan menggunakan Laravel 11 sebagai implementasi materi Pemrograman dan Pengujian Web Week 5.

## Fitur Utama

- Menampilkan daftar buku dan kategori.
- Menambahkan data buku dan kategori.
- Melihat detail buku dan kategori.
- Mengubah data buku dan kategori.
- Menghapus data buku dan kategori.
- Validasi input pada sisi server.
- Relasi data menggunakan Eloquent ORM.
- Pagination untuk daftar data.
- Eager loading untuk mengurangi query berulang.
- SQL audit logging untuk memeriksa query database.
- Seeder dan Factory untuk menghasilkan data pengujian.

## Teknologi

- PHP 8.3
- Laravel 11
- SQLite
- Eloquent ORM
- Blade Template Engine
- Bootstrap 5
- Git dan GitHub

## Struktur Aplikasi

```text
app/
├── Http/Controllers/
│   ├── BukuController.php
│   └── KategoriController.php
├── Models/
│   ├── Buku.php
│   └── Kategori.php
└── Providers/
    └── AppServiceProvider.php

database/
├── factories/
├── migrations/
└── seeders/

resources/views/
├── buku/
├── kategori/
└── components/
    └── layout.blade.php

routes/
└── web.php
```

## Persyaratan

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau 8.3 dengan ekstensi SQLite aktif.
- Composer.
- Node.js dan NPM.
- Git.

## Cara Menjalankan Aplikasi

1. Clone repositori:

   ```bash
   git clone URL_REPOSITORI
   ```

2. Masuk ke folder project:

   ```bash
   cd sipus-del
   ```

3. Install dependensi PHP:

   ```bash
   composer install
   ```

4. Salin `.env.example` menjadi `.env` dan sesuaikan konfigurasi database SQLite.

5. Generate application key:

   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi dan seeder:

   ```bash
   php artisan migrate:fresh --seed
   ```

   **Perhatian:** perintah `migrate:fresh` menghapus seluruh tabel dan data lama sebelum membuat ulang database.

7. Jalankan server:

   ```bash
   php artisan serve
   ```

8. Buka aplikasi pada `http://127.0.0.1:8000`.

## SQL Audit dan Eager Loading

Aplikasi menerapkan eager loading pada daftar buku melalui `Buku::with('kategori')`. SQL audit listener pada `AppServiceProvider` mencatat query beserta waktu eksekusinya ke:

```text
storage/logs/laravel.log
```

Log digunakan untuk memeriksa query pengambilan buku dan kategori serta membantu membuktikan bahwa eager loading menghindari query kategori berulang untuk setiap buku.

## Pengembang

Nama: Ika Maria Manurung 
NIM: 12S24021 
Program Studi: S1 Sistem Informasi  


## Catatan

Data buku dan kategori yang dihasilkan oleh Factory dan Seeder merupakan data pengujian aplikasi.