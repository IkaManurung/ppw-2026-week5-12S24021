<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun pustakawan untuk pengujian
        User::updateOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            [
                'name' => 'Pustakawan',
                'password' => Hash::make('password123'),
            ]
        );

        // Kategori buku
        $dataKategori = [
            ['kode_kategori' => 'KAT-001', 'nama_kategori' => 'Basis Data'],
            ['kode_kategori' => 'KAT-002', 'nama_kategori' => 'Jaringan Komputer'],
            ['kode_kategori' => 'KAT-003', 'nama_kategori' => 'Kecerdasan Buatan'],
            ['kode_kategori' => 'KAT-004', 'nama_kategori' => 'Sistem Informasi'],
            ['kode_kategori' => 'KAT-005', 'nama_kategori' => 'Pemrograman Web'],
        ];

        foreach ($dataKategori as $data) {
            Kategori::firstOrCreate(
                ['kode_kategori' => $data['kode_kategori']],
                ['nama_kategori' => $data['nama_kategori']]
            );
        }

        $kategori = Kategori::where('kode_kategori', 'KAT-001')->firstOrFail();

        // Sampel buku dengan stok 0
        $bukuStokNol = Buku::firstOrCreate(
            ['isbn' => '9786020000001'],
            [
                'judul' => 'Buku Uji Stok Kosong',
                'penulis' => 'Penulis Contoh',
                'penerbit' => 'Penerbit Del',
                'tahun_terbit' => 2024,
                'kategori_id' => $kategori->id,
                'stok' => 0,
                'sinopsis' => 'Data pengujian penolakan peminjaman ketika stok kosong.',
            ]
        );

        // Sampel buku dengan stok 1
        $bukuStokSatu = Buku::firstOrCreate(
            ['isbn' => '9786020000002'],
            [
                'judul' => 'Buku Uji Stok Satu',
                'penulis' => 'Penulis Contoh',
                'penerbit' => 'Penerbit Del',
                'tahun_terbit' => 2024,
                'kategori_id' => $kategori->id,
                'stok' => 1,
                'sinopsis' => 'Data pengujian peminjaman dengan stok terbatas.',
            ]
        );

        // Transaksi contoh yang sudah dikembalikan.
        // Status dikembalikan membuat stok buku tetap 1.
        Peminjaman::firstOrCreate(
            ['kode_pinjam' => 'PJM-WEEK6-001'],
            [
                'buku_id' => $bukuStokSatu->id,
                'nim_peminjam' => '12S0001',
                'nama_peminjam' => 'Mahasiswa Contoh',
                'tanggal_pinjam' => '2026-10-15',
                'tanggal_jatuh_tempo' => '2026-10-22',
                'tanggal_kembali' => '2026-10-16',
                'status' => 'dikembalikan',
            ]
        );

        // Buku contoh dari factory dibuat hanya jika tabel buku kosong.
        if (Buku::count() === 0) {
            $kategoris = Kategori::all();

            Buku::factory()
                ->count(20)
                ->recycle($kategoris)
                ->create();
        }
    }
}
