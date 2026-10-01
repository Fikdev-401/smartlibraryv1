<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * Router untuk `php artisan serve` (php -S).
 *
 * Catatan penting soal project ini:
 *   Struktur repo ini BUKAN struktur Laravel standar. Web root aslinya adalah
 *   ROOT project (lihat .htaccess + index.php di root, plus folder asset di root:
 *   portal/, images/, ebook-file/, admin/, dll). `php artisan serve` menjadikan
 *   public/ sebagai docroot, jadi file yang ada di root TIDAK otomatis ter-serve
 *   dan semua /portal/css/*.css akan 404 (halaman jadi HTML polos tanpa style).
 *   Karena itu file asset di root dikirim manual di bawah ini.
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// 1) File yang memang ada di public/ (docroot php -S) -> biarkan server yang kirim.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// 2) File asset yang ada di ROOT project -> kirim manual.
//    Dibatasi ke ekstensi static asset, dan realpath dipastikan masih di dalam
//    project, supaya .env / .git / file PHP / SQL tidak ikut ter-serve.
$rootFile = realpath(__DIR__.$uri);
if (
    $uri !== '/'
    && $rootFile !== false
    && is_file($rootFile)
    && strpos($rootFile, __DIR__.DIRECTORY_SEPARATOR) === 0
) {
    $types = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'webp'  => 'image/webp',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'otf'   => 'font/otf',
        'eot'   => 'application/vnd.ms-fontobject',
        'mp4'   => 'video/mp4',
        'webm'  => 'video/webm',
        'pdf'   => 'application/pdf',
    ];

    $base = basename($rootFile);
    $ext  = strtolower(pathinfo($base, PATHINFO_EXTENSION));

    // Jangan pernah bocorkan file tersembunyi (.env, .git, ...).
    if (strpos($base, '.') !== 0 && isset($types[$ext])) {
        header('Content-Type: '.$types[$ext]);
        header('Content-Length: '.filesize($rootFile));
        readfile($rootFile);
        return true;
    }
}

// 3) Sisanya -> front controller Laravel.
require_once __DIR__.'/public/index.php';
