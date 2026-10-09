# SIPUS-Del

SIPUS-Del adalah Sistem Informasi Perpustakaan Kampus untuk tugas mandiri mata kuliah Pemrograman & Pengujian Web. Aplikasi memakai arsitektur MVC, Laravel, SQLite, dan server-side rendering dengan Blade.

Dikembangkan dengan Laravel 13 / PHP 8.3; modul acuan memakai Laravel 11.

## Fitur

- CRUD buku dan kategori dengan tujuh action resource, implicit route model binding, dan validasi server.
- ISBN serta kode kategori unik; penghapusan kategori yang masih memiliki buku ditolak dengan pesan yang jelas.
- Daftar buku beserta kategori dan daftar kategori beserta jumlah buku, masing-masing 10 item per halaman.
- Layout Blade bersama, Bootstrap 5 via CDN, navbar ungu, flash message, pesan validasi, input lama, dan konfirmasi hapus.
- Factory Faker Indonesia dan seeder berisi 5 kategori serta 20 buku.
- Eager loading untuk menghindari N+1 dan audit SQL opsional. Lihat [bukti audit SQL](docs/sql-audit.md).

## Prasyarat

- PHP 8.3 atau lebih baru beserta ekstensi yang disyaratkan Composer, termasuk PDO, `pdo_sqlite`, dan `sqlite3`.
- Composer dan Git.
- Folder `storage/` serta `bootstrap/cache/` dapat ditulis.
- Koneksi internet untuk instalasi dependensi dan memuat CSS Bootstrap dari CDN.

Node.js dan build Vite tidak diperlukan untuk halaman SIPUS-Del karena styling memakai Bootstrap CDN. Runtime verifikasi lokal menggunakan PHP 8.4.26, yang memenuhi persyaratan PHP 8.3+.

## Instalasi

Buka terminal pada folder proyek, lalu jalankan:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate:fresh --seed
php artisan serve
```

Pada Linux/macOS, ganti `Copy-Item .env.example .env` dengan `cp .env.example .env`. Salin file contoh hanya pada instalasi baru agar konfigurasi lokal yang sudah ada tidak tertimpa. `migrate:fresh --seed` menghapus seluruh tabel lama, membuat skema baru, lalu memasukkan data contoh.

Buka `http://127.0.0.1:8000`; halaman awal mengarah ke `/buku`. Pengaturan `.env.example` sudah memakai `DB_CONNECTION=sqlite`, `APP_FAKER_LOCALE=id_ID`, serta `SQL_AUDIT=false`. `DB_DATABASE` tidak perlu diisi karena Laravel menggunakan `database/database.sqlite` secara default. Tidak ada login karena autentikasi berada di luar cakupan tugas.

### Jika muncul “could not find driver”

Periksa konfigurasi CLI dengan `php --ini`, lalu aktifkan `pdo_sqlite` dan `sqlite3` pada `php.ini` yang digunakan. Pada lingkungan lokal tugas ini, driver tersedia tetapi belum aktif secara bawaan. Alternatif sementara tanpa mengubah konfigurasi sistem:

```powershell
php -d extension=pdo_sqlite -d extension=sqlite3 artisan migrate:fresh --seed
php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit
```

Untuk menjalankan server tanpa mengubah `php.ini`, gunakan server PHP langsung agar opsi ekstensi diterapkan pada proses yang melayani request:

```powershell
cd public
php -d extension=pdo_sqlite -d extension=sqlite3 -S 127.0.0.1:8000 ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
```

## Struktur MVC

| Bagian | Lokasi | Tanggung jawab |
| --- | --- | --- |
| Model | `app/Models/Buku.php`, `Kategori.php` | `$fillable`, `HasFactory`, relasi belongsTo/hasMany |
| Controller | `app/Http/Controllers/BukuController.php`, `KategoriController.php` | Kueri, validasi, perubahan data, redirect, flash |
| View | `resources/views/buku/`, `kategori/` | Halaman index, create, edit, show; partial form |
| Layout | `resources/views/components/layout.blade.php` | Kerangka HTML, navbar, CSS Bootstrap, flash |
| Route | `routes/web.php` | Dua `Route::resource` dan redirect `/` |
| Skema/data contoh | `database/migrations/`, `factories/`, `seeders/` | Tabel, relasi database, dan data pengujian |
| Konfigurasi audit | `config/sql_audit.php`, `AppServiceProvider::boot()` | Flag env, DB::listen, dan pencatatan log |
| Pengujian | `tests/Feature/` | Controller, seeder, render Blade, dan audit SQL |

View tidak menjalankan kueri SQL. Daftar buku memakai `Buku::with('kategori')->latest()->paginate(10)`, sedangkan daftar kategori memakai `Kategori::withCount('bukus')->paginate(10)`. `Paginator::useBootstrapFive()` didaftarkan dalam `AppServiceProvider`.

## Audit SQL

Atur `SQL_AUDIT=true`, `LOG_LEVEL=debug`, dan `LOG_CHANNEL=single` pada `.env`, jalankan `php artisan config:clear`, lalu buka `/buku`. Baris `SQL audit` akan tercatat di `storage/logs/laravel.log`. Saring hanya tabel `bukus` dan `kategoris`; untuk 10 buku, eager loading menghasilkan 3 kueri, sedangkan pengukuran lazy loading sementara menghasilkan 12 kueri.

Setelah pengukuran, kembalikan `SQL_AUDIT=false` dan jalankan `php artisan config:clear`. Kondisi kode akhir tetap menggunakan eager loading. SQL audit mencatat nilai parameter aktual, sehingga hanya diaktifkan saat pemeriksaan. Log asli tidak masuk Git; metode dan bukti tersaring tersedia di [docs/sql-audit.md](docs/sql-audit.md).

## Pengujian dan Hasil Verifikasi

Setelah ekstensi SQLite aktif:

```powershell
php artisan migrate:fresh --seed
php artisan route:list
php vendor/bin/phpunit
php vendor/bin/pint --test app database routes tests config/sql_audit.php
php artisan view:cache
php artisan view:clear
```

Pengujian memakai SQLite in-memory dari `phpunit.xml`, sehingga tidak mengubah data aplikasi. Jika driver CLI belum aktif, gunakan opsi `-d extension=pdo_sqlite -d extension=sqlite3` seperti contoh di atas. Perintah `php artisan test` juga dapat dipakai setelah ekstensi diaktifkan di `php.ini`.

Hasil verifikasi akhir pada 9 Oktober 2026:

| Pemeriksaan | Hasil |
| --- | --- |
| `migrate:fresh --seed` | Sukses; 5 kategori dan 20 buku |
| Rute resource | 14 rute: 7 buku dan 7 kategori; redirect `/` dan rute bawaan framework dihitung terpisah |
| PHPUnit | 22 tests, 659 assertions, seluruhnya lulus |
| Create buku valid | Berhasil tersimpan |
| ISBN duplikat | Ditolak pada store/update |
| Stok -1 | Ditolak |
| Judul kurang dari 5 karakter | Ditolak |
| Update tanpa mengubah ISBN | Berhasil tanpa error unique |
| Hapus kategori yang mempunyai buku | Ditolak dengan flash error; data tetap tersimpan |
| Render Blade | Delapan halaman CRUD, data kosong, pagination, error/old input, flash, dan escaping lulus |
| Eager loading | 3 kueri; dibandingkan 12 kueri lazy loading sementara |
| Format dan kompilasi view | Pint dan `view:cache` lulus |

`git status --short` harus kosong setelah commit. `git ls-files -- .env 'vendor/*'` tidak boleh menghasilkan output; `.env`, `vendor/`, database SQLite lokal, cache hasil kompilasi, serta log tidak disertakan dalam commit.

## Keputusan Desain

- Gaya modul dipertahankan: properti klasik `protected $fillable`, `$request->validate()` dalam controller, dan `Route::resource`. Tidak memakai Form Request, PHP Attributes untuk konfigurasi model, starter kit, AI SDK, atau Boost.
- Struktur proyek Laravel 13 dipertahankan: konfigurasi aplikasi/routing ada di `bootstrap/app.php` dan daftar provider di `bootstrap/providers.php`; tidak menambahkan file Kernel atau provider routing lama. Migrasi anonymous class tidak memerlukan perubahan struktur untuk tugas ini.
- Model `User` bawaan proyek Laravel 13 semula memakai Attributes `Fillable` dan `Hidden`; keduanya diganti menjadi `$fillable` dan `$hidden` dengan isi yang sama sesuai gaya modul. Session JSON bawaan tetap dipertahankan; pengujian pesan validasi mengikuti redirect langsung sebelum memeriksa HTML.
- Tabel bawaan users, cache, dan jobs dipertahankan karena session, cache, dan queue menggunakan database. Seeder akun contoh diganti dengan data perpustakaan karena autentikasi tidak diminta.
- Kategori dibuat sebelum buku. Foreign key `kategori_id` memakai `restrict`; controller memeriksa keberadaan buku sebelum menghapus kategori.
- SQLite tidak menegakkan panjang string dan unsigned seperti beberapa database lain. Deklarasi migrasi mengikuti modul, sementara validasi controller menegakkan batas panjang dan nilai input. ISBN menerima 10–13 digit sesuai aturan tugas; factory menghasilkan ISBN-13 unik. Judul boleh sama.
- Faker dipanggil dengan locale `id_ID` secara eksplisit agar konsisten walau `.env` lama berbeda. Seeder membuat empat buku per kategori, setelah kelima kategori tersedia; judul mengikuti nama kategori. Seeder ditujukan untuk database kosong melalui `migrate:fresh --seed`, bukan untuk dijalankan berulang pada data yang sama.
- Setiap resource berbagi partial form create/edit. Semua output memakai `{{ }}`, form memakai CSRF/method spoofing, dan hapus memakai konfirmasi browser. Detail kategori menampilkan jumlah buku; filter dan pencarian tidak ditambahkan karena tidak diminta.
- Bootstrap 5.3.8 CSS menggunakan CDN dengan integrity hash. Navigasi sederhana tidak memerlukan JavaScript Bootstrap; font memakai default Bootstrap. Warna navbar `#4C1D95` dan hover `#3B0764` mengikuti modul.
- Audit SQL memakai file konfigurasi agar flag env dapat di-cache dan nonaktif secara default. Eager loading dipertahankan setelah eksperimen lazy loading.
- Repository Git belum tersedia pada awal tugas; commit Tahap 1 sekaligus mencatat kerangka proyek yang telah terinstal. Setiap tahap berikutnya memiliki satu commit Conventional Commits.
