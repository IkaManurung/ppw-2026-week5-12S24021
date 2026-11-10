# SIPUS-Del — REST API dan SOAP Web Service

## 1. Identitas Proyek

| Keterangan | Identitas |
|---|---|
| Nama | [Ika Maria Manurung] |
| NIM | [12S24021] |
| Program Studi | [Sistem Informasi] |
| Mata Kuliah | PPW 12S3101 |
| Proyek | SIPUS-Del |
| Branch | `week9-rest-api` |

## 2. Deskripsi Proyek

SIPUS-Del merupakan sistem informasi perpustakaan yang dikembangkan menggunakan Laravel. Pada praktikum Week 9, sistem dikembangkan dengan menambahkan layanan integrasi menggunakan REST API dan SOAP Web Service.

Pengembangan ini bertujuan agar data buku dan peminjaman dapat diakses melalui layanan API, dilengkapi autentikasi menggunakan Laravel Sanctum, pengelolaan transaksi peminjaman, serta integrasi referensi buku eksternal melalui OpenLibrary.

## 3. Arsitektur Provider dan Consumer

### 3.1 Provider

SIPUS-Del berperan sebagai API Provider yang menyediakan layanan:
- Autentikasi pengguna menggunakan Sanctum.
- Pengelolaan katalog buku.
- Pengelolaan transaksi peminjaman dan pengembalian.
- Pengecekan status peminjaman mahasiswa melalui SOAP.
- Penyediaan respons API dalam format JSON.

### 3.2 Consumer

SIPUS-Del juga berperan sebagai API Consumer dengan mengakses layanan OpenLibrary untuk memperoleh informasi referensi buku berdasarkan ISBN.

### 3.3 Matriks Kelayakan

| Aspek | Provider | Consumer |
|---|---|---|
| Layanan | Menyediakan REST API dan SOAP | Mengakses OpenLibrary |
| Data | Buku dan peminjaman | Referensi buku eksternal |
| Protokol | HTTP, REST, SOAP | HTTP REST |
| Format | JSON dan XML | JSON |
| Teknologi | Laravel dan Sanctum | Laravel HTTP Client |

## 4. Perbandingan REST dan SOAP

| Aspek | REST | SOAP |
|---|---|---|
| Format data | JSON | XML |
| Protokol | HTTP | HTTP |
| Struktur | Resource-based | Message-based |
| Penggunaan | Katalog dan transaksi buku | Pengecekan clearance mahasiswa |
| Respons kesalahan | RFC 7807 | SOAP Fault |

REST digunakan untuk pengelolaan data buku dan peminjaman, sedangkan SOAP digunakan untuk menyediakan layanan pengecekan status peminjaman aktif mahasiswa.

## 5. Katalog Endpoint API

Base URL:

`http://127.0.0.1:8000/api/v1`

| Method | Endpoint | Autentikasi | Fungsi |
|---|---|---|---|
| POST | `/auth/login` | Publik | Login pengguna |
| GET | `/auth/me` | Bearer Token | Melihat profil pengguna |
| POST | `/auth/logout` | Bearer Token | Logout |
| GET | `/bukus` | Publik | Menampilkan katalog buku |
| GET | `/bukus/{buku}` | Publik | Detail buku |
| POST | `/bukus` | Bearer Token | Menambahkan buku |
| PUT | `/bukus/{buku}` | Bearer Token | Mengubah seluruh data buku |
| PATCH | `/bukus/{buku}` | Bearer Token | Mengubah sebagian data buku |
| DELETE | `/bukus/{buku}` | Bearer Token | Menghapus buku |
| GET | `/bukus/{buku}/referensi-eksternal` | Publik | Mengakses referensi OpenLibrary |
| GET | `/peminjamans` | Bearer Token | Menampilkan peminjaman |
| POST | `/peminjamans` | Bearer Token | Membuat peminjaman |
| PATCH | `/peminjamans/{id}/kembalikan` | Bearer Token | Mengembalikan buku |

### SOAP Endpoint

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/soap/sipus.wsdl` | Menampilkan kontrak WSDL |
| POST | `/soap/sipus` | Mengecek status clearance mahasiswa |

## 6. Fitur dan Implementasi

### 6.1 Autentikasi Sanctum

Autentikasi API menggunakan Laravel Sanctum dengan mekanisme Bearer Token. Pengguna melakukan login untuk memperoleh token, kemudian token digunakan untuk mengakses endpoint yang dilindungi.

### 6.2 Katalog Buku

Katalog buku menyediakan fitur:
- Pagination.
- Pencarian berdasarkan judul, penulis, dan ISBN.
- Filter kategori.
- Filter ketersediaan stok.
- Pengurutan data.
- Detail buku beserta kategori dan riwayat peminjaman.

### 6.3 Pengelolaan Buku

REST API mendukung operasi POST, PUT, PATCH, dan DELETE. Penghapusan buku ditolak apabila masih terdapat peminjaman aktif.

### 6.4 Transaksi Peminjaman

Transaksi peminjaman menggunakan `DB::transaction()` dan `lockForUpdate()` untuk menjaga konsistensi stok buku.

Ketentuan transaksi:
- Stok buku harus tersedia.
- Mahasiswa tidak dapat meminjam buku yang sama ketika masih memiliki peminjaman aktif.
- Stok berkurang ketika peminjaman berhasil.
- Stok bertambah ketika buku dikembalikan.
- Pengembalian buku yang sudah dilakukan sebelumnya ditolak.

### 6.5 HATEOAS dan RFC 7807

HATEOAS digunakan untuk menyediakan tautan tindakan terkait resource API. RFC 7807 digunakan untuk menyajikan respons kesalahan dalam format Problem Details JSON.

### 6.6 Integrasi OpenLibrary

Sistem menyediakan endpoint referensi eksternal untuk mengambil informasi buku dari OpenLibrary berdasarkan ISBN menggunakan Laravel HTTP Client.

Apabila layanan eksternal tidak dapat dijangkau, sistem menangani kegagalan koneksi dan mengembalikan respons `503 Service Unavailable`. Dengan demikian, kegagalan layanan eksternal tidak menyebabkan aplikasi berhenti berjalan.

### 6.7 SOAP dan WSDL

SOAP digunakan untuk mengecek jumlah peminjaman aktif mahasiswa berdasarkan NIM. Respons menyediakan informasi jumlah peminjaman aktif dan status clearance. Kesalahan format XML atau NIM kosong ditangani menggunakan SOAP Fault.

## 7. Hasil Pengujian API

Pengujian dilakukan menggunakan Postman pada server lokal Laravel.

| Pengujian | Hasil |
|---|---|
| Login API | Berhasil — 200 OK |
| Melihat profil pengguna | Berhasil — 200 OK |
| Daftar katalog buku | Berhasil — 200 OK |
| Pagination katalog | Berhasil — 200 OK |
| Daftar peminjaman | Berhasil — 200 OK |
| Filter peminjaman berdasarkan NIM dan status | Berhasil — 200 OK |
| Membuat peminjaman | Berhasil — 201 Created |
| Peminjaman buku yang sama saat masih aktif | Ditolak — 409 Conflict |
| Stok buku kosong | Ditolak — 422 Unprocessable Content |
| Pengembalian buku | Berhasil — 200 OK |
| Pengembalian buku yang sudah dikembalikan | Ditolak — 409 Conflict |
| HATEOAS peminjaman | Berhasil — tautan tindakan sesuai status peminjaman |
| Referensi eksternal OpenLibrary | Belum berhasil diakses — 503 Service Unavailable |
| PATCH buku | Perlu pengujian ulang — sebelumnya 405 Method Not Allowed |



## 8. Instalasi dan Menjalankan Proyek

### Persyaratan
- PHP 8.2 atau lebih baru.
- Composer.
- MySQL.
- Laravel 11.
- Postman.

### Langkah menjalankan

1. Clone repository.
2. Masuk ke direktori proyek.
3. Install dependency:

   `composer install`

4. Salin `.env.example` menjadi `.env`.
5. Sesuaikan konfigurasi database.
6. Generate application key:

   `php artisan key:generate`

7. Jalankan migrasi:

   `php artisan migrate`

8. Jalankan server:

   `php artisan serve`

9. Akses API melalui:

   `http://127.0.0.1:8000`

**Catatan:** Jangan menggunakan `php artisan migrate:fresh` pada database yang berisi data proyek karena perintah tersebut menghapus tabel dan data.

## 9. Pengujian dengan Postman

1. Jalankan server Laravel.
2. Lakukan login melalui endpoint `/api/v1/auth/login`.
3. Salin token yang diperoleh.
4. Pilih Authorization → Bearer Token.
5. Masukkan token untuk endpoint yang memerlukan autentikasi.
6. Gunakan header `Accept: application/json`.
7. Untuk request JSON, gunakan `Content-Type: application/json`.
8. Jalankan request dan periksa status serta responsnya.

## 10. Dokumentasi API

Dokumentasi kontrak API tersedia pada:

- `docs/openapi-sipus-v1.yaml`
- `docs/SIPUS-Del-API-v1.postman_collection.json`

## 11. Bukti Pengujian

Tambahkan screenshot hasil pengujian ke folder `docs/screenshots/`.

Bukti yang perlu dilampirkan:

1. SOAP WSDL dan SOAP Fault.
2. Login API yang menghasilkan Bearer Token.
3. GET katalog buku dengan pagination.
4. POST buku dengan status 201 dan Location.
5. PUT dan PATCH buku.
6. Peminjaman berhasil dan pengujian konflik/stok kosong.
7. Pengembalian buku dan pengujian pengembalian ulang.
8. Pengujian referensi eksternal OpenLibrary.
9. Repository GitHub dan branch `week9-rest-api`.

**Catatan:** Screenshot harus berasal dari hasil pengujian sebenarnya. Jangan menampilkan Bearer Token atau GitHub Personal Access Token secara terbuka.

## 12. Kesimpulan

Pengembangan Week 9 menambahkan layanan REST API dan SOAP Web Service pada SIPUS-Del. Implementasi mencakup autentikasi Sanctum, pengelolaan katalog buku, transaksi peminjaman dan pengembalian, penanganan kesalahan API, serta integrasi referensi eksternal. Pengujian menggunakan Postman dilakukan untuk memastikan endpoint dapat memberikan respons sesuai fungsi yang diimplementasikan.