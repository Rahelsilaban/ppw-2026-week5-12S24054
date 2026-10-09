<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Keputusan Desain

- Tahap 1 mempertahankan migrasi bawaan Laravel untuk users, cache, dan jobs karena konfigurasi awal memakai session, cache, dan queue berbasis database.
- Migrasi kategori dijalankan sebelum buku; foreign key memakai `restrict` agar kategori yang masih digunakan buku tidak dapat dihapus.
- SQLite tidak menegakkan panjang string dan sifat unsigned seperti beberapa database lain. Deklarasi skema tetap mengikuti modul; batas nilai akan ditegakkan melalui validasi controller pada Tahap 4.
- Migrasi memakai anonymous class pada struktur `database/migrations` yang tersedia; Tahap 1 tidak membutuhkan penyesuaian struktur Laravel 11 ke Laravel 13.
- Pada Tahap 2, model `Kategori` dan `Buku` memakai properti klasik `$fillable` dan trait `HasFactory`, dengan relasi `Kategori::bukus()` dan `Buku::kategori()` melalui `kategori_id`. Factory keduanya akan dibuat pada Tahap 3.
- Model `User` bawaan Laravel 13 di proyek ini memakai PHP Attributes `Fillable` dan `Hidden`. Keduanya diganti menjadi properti `$fillable` dan `$hidden` dengan isi yang sama agar konfigurasi model mengikuti gaya klasik modul; struktur file tetap mengikuti proyek Laravel 13.
- Folder proyek awal belum memiliki repository Git. Commit Tahap 1 sekaligus mencatat kerangka proyek yang sudah terinstal, tanpa `.env`, `vendor/`, dan database SQLite lokal.

README bawaan di bawah akan diganti dengan panduan SIPUS-Del lengkap pada Tahap 7.

### Verifikasi Tahap 1

Runtime lokal menggunakan PHP 8.4.26 (memenuhi syarat PHP 8.3+). Driver SQLite pada PHP CLI belum aktif secara bawaan, sehingga perintah verifikasi dijalankan dengan `php -d extension=pdo_sqlite -d extension=sqlite3 artisan migrate --no-interaction`. Untuk memakai perintah `php artisan` biasa, aktifkan `pdo_sqlite` dan `sqlite3` pada konfigurasi PHP lokal terlebih dahulu. Konfigurasi sistem tidak diubah oleh proyek ini.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
