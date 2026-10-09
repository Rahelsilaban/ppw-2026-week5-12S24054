<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');

        return [
            'isbn' => $faker->unique()->isbn13(),
            'judul' => $faker->randomElement([
                'Dasar-Dasar Pemrograman Web',
                'Perancangan Basis Data Relasional',
                'Administrasi Jaringan Komputer',
                'Matematika Diskrit untuk Informatika',
                'Analisis dan Perancangan Sistem Informasi',
            ]),
            'penulis' => $faker->name(),
            'penerbit' => $faker->randomElement([
                'Informatika Bandung', 'Andi', 'Elex Media Komputindo', 'Gramedia Pustaka Utama', 'Deepublish',
            ]),
            'tahun_terbit' => $faker->numberBetween(1990, now()->year),
            'kategori_id' => Kategori::factory(),
            'stok' => $faker->numberBetween(0, 50),
            'sinopsis' => $faker->randomElement([
                'Buku pengantar untuk mahasiswa dengan pembahasan konsep dasar, contoh penerapan, dan latihan mandiri.',
                'Panduan praktis yang menghubungkan teori dengan studi kasus untuk mendukung pembelajaran di kampus.',
                'Materi disusun bertahap dengan contoh soal dan proyek sederhana untuk memperkuat pemahaman pembaca.',
            ]),
        ];
    }
}
