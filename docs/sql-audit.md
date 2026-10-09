# Audit SQL SIPUS-Del

Pengukuran dilakukan pada 9 Oktober 2026 menggunakan Laravel 13, PHP 8.4.26, SQLite, dan database hasil `migrate:fresh --seed` (5 kategori, 20 buku). Request GET `/buku` dijalankan melalui HTTP Kernel Laravel sampai Blade selesai dirender: status **200**, halaman **Daftar Buku**, 10 buku pada halaman pertama. Ini bukan pengukuran kueri model terpisah dari render halaman.

## Metode

1. `DB::listen` di `AppServiceProvider::boot()` mencatat SQL aktual melalui `Log::debug('SQL audit', ...)`, termasuk waktu dan koneksi. Pengaturan `config/sql_audit.php` membaca `SQL_AUDIT` sehingga tetap kompatibel dengan config cache.
2. Aktifkan `SQL_AUDIT=true`, `LOG_CHANNEL=single`, dan `LOG_LEVEL=debug`; jalankan `php artisan config:clear` bila konfigurasi pernah di-cache.
3. Dengan kode `Buku::with('kategori')->latest()->paginate(10)`, jalankan request pertama dan ambil hanya baris log baru di `storage/logs/laravel.log`.
4. Hapus sementara `with('kategori')->` dari `BukuController::index`, sehingga menjadi `Buku::latest()->paginate(10)`. Jalankan request kedua pada proses PHP baru dengan database yang sama. Kembalikan isi controller asli dalam blok `finally` setelah pengukuran.
5. Saring log untuk SQL yang mengakses `bukus` atau `kategoris` saja; kueri session, cache, dan migrations tidak dihitung. SQL berikut diekstrak dari field `sql` log aktual, bukan perkiraan.

Runner request setara dengan membuka halaman melalui browser:

```php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/buku', 'GET');
$response = $kernel->handle($request);
echo $response->getStatusCode(); // 200, Blade telah dirender
$kernel->terminate($request, $response);
```

Untuk mengulang manual, buka `/buku` satu kali pada setiap kondisi setelah aplikasi dijalankan dengan `php artisan serve`. Simpan posisi akhir log sebelum setiap request dan ambil baris baru saja. Bila driver SQLite CLI belum aktif, tambahkan `-d extension=pdo_sqlite -d extension=sqlite3` pada perintah PHP. Setelah selesai, kembalikan `with('kategori')`, atur `SQL_AUDIT=false`, dan bersihkan config cache.

## Dengan eager loading: 3 kueri

```sql
select count(*) as "aggregate" from "bukus";
select * from "bukus" order by "created_at" desc limit 10 offset 0;
select * from "kategoris" where "kategoris"."id" in (1, 2, 3);
```

Kueri pertama menghitung total 20 buku untuk pagination, kedua mengambil 10 buku, dan ketiga mengambil kategori untuk seluruh buku pada halaman sekaligus melalui `WHERE id IN (...)`.

## Tanpa eager loading: 12 kueri

```sql
select count(*) as "aggregate" from "bukus";
select * from "bukus" order by "created_at" desc limit 10 offset 0;
select * from "kategoris" where "kategoris"."id" = 1 limit 1;
select * from "kategoris" where "kategoris"."id" = 1 limit 1;
select * from "kategoris" where "kategoris"."id" = 1 limit 1;
select * from "kategoris" where "kategoris"."id" = 1 limit 1;
select * from "kategoris" where "kategoris"."id" = 2 limit 1;
select * from "kategoris" where "kategoris"."id" = 2 limit 1;
select * from "kategoris" where "kategoris"."id" = 2 limit 1;
select * from "kategoris" where "kategoris"."id" = 2 limit 1;
select * from "kategoris" where "kategoris"."id" = 3 limit 1;
select * from "kategoris" where "kategoris"."id" = 3 limit 1;
```

| Kondisi | COUNT bukus | SELECT bukus | SELECT kategoris | Total |
| --- | ---: | ---: | ---: | ---: |
| Dengan `with('kategori')` | 1 | 1 | 1 | **3** |
| Tanpa `with()` | 1 | 1 | 10 | **12** |

Tanpa eager loading, akses `$buku->kategori->nama_kategori` di setiap baris Blade memicu kueri tambahan untuk setiap instance buku. Sepuluh buku menghasilkan sepuluh kueri kategori, bahkan ketika beberapa buku memakai kategori yang sama, sehingga terjadi N+1 (satu kueri daftar ditambah N kueri relasi; COUNT pagination menambah satu lagi). `with('kategori')` menggabungkan pemuatan relasi menjadi satu kueri `WHERE IN`, sehingga total turun dari 12 menjadi 3 tanpa mengubah hasil halaman.

## Bukti log tersaring

Timestamp berikut dipertahankan sesuai log aplikasi (UTC), bukan zona waktu pengguna. SQL dan waktu eksekusi berasal dari dua request terpisah; urutan ID dan waktunya dapat berbeda pada pengukuran berikutnya.

Eager loading:

```text
[2026-10-09 13:17:34] local.DEBUG: SQL audit {"sql":"select count(*) as \"aggregate\" from \"bukus\"","time_ms":0.14,"connection":"sqlite"}
[2026-10-09 13:17:34] local.DEBUG: SQL audit {"sql":"select * from \"bukus\" order by \"created_at\" desc limit 10 offset 0","time_ms":0.22,"connection":"sqlite"}
[2026-10-09 13:17:34] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" in (1, 2, 3)","time_ms":0.18,"connection":"sqlite"}
```

Lazy loading sementara:

```text
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select count(*) as \"aggregate\" from \"bukus\"","time_ms":0.16,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"bukus\" order by \"created_at\" desc limit 10 offset 0","time_ms":0.26,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 1 limit 1","time_ms":0.16,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 1 limit 1","time_ms":0.12,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 1 limit 1","time_ms":0.1,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 1 limit 1","time_ms":0.08,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 2 limit 1","time_ms":0.08,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 2 limit 1","time_ms":0.08,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 2 limit 1","time_ms":0.08,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 2 limit 1","time_ms":0.08,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 3 limit 1","time_ms":0.08,"connection":"sqlite"}
[2026-10-09 13:17:50] local.DEBUG: SQL audit {"sql":"select * from \"kategoris\" where \"kategoris\".\"id\" = 3 limit 1","time_ms":0.09,"connection":"sqlite"}
```

## Kondisi akhir

- `BukuController::index` kembali memakai `Buku::with('kategori')->latest()->paginate(10)`; tidak ada perubahan controller tersisa dari eksperimen.
- `SQL_AUDIT=false` menjadi default pada `.env.example`; audit dapat diaktifkan saat diperlukan.
- Test regresi `SqlAuditTest` memastikan 10 buku tetap membutuhkan tepat 3 kueri tabel perpustakaan dan memeriksa audit aktif/nonaktif.
- PHPUnit: **22 tests, 659 assertions**, seluruhnya lulus. Log asli di `storage/` tidak di-commit; bukti tersaring ini disimpan di `docs/`.
