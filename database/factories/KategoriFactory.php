<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_kategori' => fake()->unique()->bothify('KAT-###'),
            'nama_kategori' => fake()->randomElement([
                'Teknologi Informasi',
                'Pemrograman',
                'Basis Data',
                'Kecerdasan Buatan',
                'Sistem Informasi',
                'Jaringan Komputer',
                'Manajemen',
                'Bisnis',
            ]),
        ];
    }
}