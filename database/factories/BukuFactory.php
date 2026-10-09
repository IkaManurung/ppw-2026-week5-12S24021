<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'isbn' => fake()->unique()->numerify('978##########'),

            'judul' => fake()->randomElement([
                'Dasar-Dasar Pemrograman Modern',
                'Membangun Sistem Informasi Digital',
                'Pengantar Basis Data',
                'Kecerdasan Buatan untuk Pemula',
                'Jaringan Komputer dan Internet',
                'Pengembangan Aplikasi Web',
                'Data Science untuk Pemula',
                'Algoritma dan Struktur Data',
                'Analisis dan Perancangan Sistem',
                'Pemrograman Berorientasi Objek',
                'Pengantar Machine Learning',
                'Manajemen Data Digital',
                'Teknologi Informasi Masa Kini',
                'Sistem Informasi dalam Organisasi',
                'Praktik Pemrograman Web',
                'Konsep Dasar Artificial Intelligence',
                'Basis Data Relasional',
                'Pengembangan Software Modern',
                'Dasar-Dasar UI/UX',
                'Transformasi Digital',
            ]),

            'penulis' => fake()->unique()->randomElement([
                'IU',
                'Taeyeon',
                'BIBI',
                'Sunmi',
                'Chungha',
                'Seulgi',
                'Wendy',
                'Karina',
                'Winter',
                'Wonyoung',
                'Yujin',
                'Chaewon',
                'Sakura',
                'Hanni',
                'Danielle',
                'Nayeon',
                'Mina',
                'Mingyu',
                'Felix',
                'Hyunjin',
                'Yeonjun',
                'Soobin',
                'Sunghoon',
                'Jungwon',
                'Sullyoon',
                'Haewon',
                'Yeji',
                'Ryujin',
                'Miyeon',
                'Minnie',
                'Wonbin',
                'Sohee',
                'San',
                'Seonghwa',
                'Jaehyun',
                'Taesan',
                'Natty',
                'Belle',
            ]),

            'penerbit' => fake()->randomElement([
                'Del Publishing',
                'Informatika Press',
                'Tech Media',
                'Digital Library Press',
                'Nusantara Media',
            ]),

            'tahun_terbit' => fake()->numberBetween(2018, 2026),

            'kategori_id' => Kategori::factory(),

            'stok' => fake()->numberBetween(1, 20),

            'sinopsis' => fake()->paragraph(3),
        ];
    }
}