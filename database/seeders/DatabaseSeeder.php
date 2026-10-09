<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = collect([
            [
                'kode_kategori' => 'KAT-001',
                'nama_kategori' => 'Basis Data',
            ],
            [
                'kode_kategori' => 'KAT-002',
                'nama_kategori' => 'Jaringan Komputer',
            ],
            [
                'kode_kategori' => 'KAT-003',
                'nama_kategori' => 'Kecerdasan Buatan',
            ],
            [
                'kode_kategori' => 'KAT-004',
                'nama_kategori' => 'Sistem Informasi',
            ],
            [
                'kode_kategori' => 'KAT-005',
                'nama_kategori' => 'Pemrograman Web',
            ],
        ])->map(function ($data) {
            return Kategori::create($data);
        });

        Buku::factory()
            ->count(20)
            ->recycle($kategori)
            ->create();
    }
}