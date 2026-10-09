<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_kategori' => fake('id_ID')->unique()->bothify('KAT-######'),
            'nama_kategori' => fake('id_ID')->randomElement([
                'Pemrograman', 'Basis Data', 'Jaringan', 'Matematika', 'Sistem Informasi',
            ]),
        ];
    }
}
