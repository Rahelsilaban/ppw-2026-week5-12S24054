<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $kategoris = collect([
            ['kode_kategori' => 'PRG', 'nama_kategori' => 'Pemrograman'],
            ['kode_kategori' => 'BD', 'nama_kategori' => 'Basis Data'],
            ['kode_kategori' => 'JRG', 'nama_kategori' => 'Jaringan'],
            ['kode_kategori' => 'MTK', 'nama_kategori' => 'Matematika'],
            ['kode_kategori' => 'SI', 'nama_kategori' => 'Sistem Informasi'],
        ])->map(fn (array $attributes) => Kategori::factory()->create($attributes));

        foreach ($kategoris as $kategori) {
            foreach (['Dasar-Dasar', 'Panduan Praktis', 'Konsep dan Penerapan', 'Studi Kasus'] as $awalan) {
                Buku::factory()->for($kategori)->create([
                    'judul' => $awalan.' '.$kategori->nama_kategori,
                ]);
            }
        }
    }
}
