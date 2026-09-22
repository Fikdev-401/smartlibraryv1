@echo off
REM ==========================================================
REM  First-time setup script for this Laravel app on Windows.
REM  Usage:  > setup.bat
REM  Requires: PHP on PATH, Composer on PATH, MySQL/MariaDB
REM ==========================================================
setlocal ENABLEDELAYEDEXPANSION
chcp 65001 >nul
title Laravel First-Run Setup

echo.
echo  ===============================================
echo    SETUP APLIKASI LARAVEL — FIRST RUN
echo  ===============================================
echo.

REM --- 1. Cek PHP & Composer ---
where php >nul 2>nul
if errorlevel 1 (
    echo  [X] PHP tidak ditemukan di PATH.
    echo      Pastikan XAMPP terinstall dan tambahkan D:\xampp\php ke PATH.
    pause & exit /b 1
)
where composer >nul 2>nul
if errorlevel 1 (
    echo  [X] Composer tidak ditemukan di PATH.
    echo      Install dari https://getcomposer.org/download/
    pause & exit /b 1
)
echo  [OK] PHP dan Composer terdeteksi.

REM --- 2. composer install (skip kalau vendor/ sudah ada) ---
if exist "vendor\autoload.php" (
    echo  [SKIP] vendor\ sudah ada, lewati composer install.
) else (
    echo  [..] Menjalankan composer install...
    call composer install --no-interaction --prefer-dist
    if errorlevel 1 (
        echo  [X] composer install gagal. Cek koneksi internet / log di atas.
        pause & exit /b 1
    )
    echo  [OK] composer install selesai.
)

REM --- 3. Siapkan file .env (copy dari .env.example kalau belum ada) ---
if exist ".env" (
    echo  [SKIP] .env sudah ada, lewati copy.
) else (
    if exist ".env.example" (
        echo  [..] Membuat .env dari .env.example ...
        copy /Y .env.example .env >nul
        echo  [OK] .env dibuat. Edit DB_DATABASE / DB_USERNAME / DB_PASSWORD sebelum lanjut.
        echo.
        echo       >> Buka .env, cari baris:
        echo          DB_DATABASE=app_xxx
        echo          DB_USERNAME=root
        echo          DB_PASSWORD=
        echo.
        set /p EDITENV="  Tekan Y untuk edit .env sekarang, atau Enter untuk lanjut: "
        if /i "!EDITENV!"=="Y" notepad .env
    ) else (
        echo  [X] .env.example tidak ditemukan!
        pause & exit /b 1
    )
)

REM --- 4. Generate APP_KEY kalau belum ada ---
findstr /C:"APP_KEY=base64" .env >nul 2>nul
if not errorlevel 1 (
    echo  [SKIP] APP_KEY sudah ada, lewati generate.
) else (
    echo  [..] Generate APP_KEY...
    call php artisan key:generate --no-interaction
)

REM --- 5. Bersihkan cache ---
echo  [..] Membersihkan cache...
call php artisan config:clear >nul 2>nul
call php artisan cache:clear >nul 2>nul
call php artisan view:clear >nul 2>nul

REM --- 6. Storage symlink (untuk akses file upload dari public) ---
if not exist "public\storage" (
    echo  [..] Membuat storage symlink...
    call php artisan storage:link >nul 2>nul
)

REM --- 7. (Opsional) Migrasi database ---
echo.
set /p DOMIG="  Jalankan migrasi database sekarang? (y/N): "
if /i "!DOMIG!"=="Y" (
    echo  [..] Menjalankan php artisan migrate ...
    call php artisan migrate --force
    if errorlevel 1 (
        echo.
        echo  [!] Migrasi gagal. Pastikan database sudah dibuat di phpMyAdmin
        echo      dan kredensial DB di .env benar.
    ) else (
        echo  [OK] Migrasi selesai.
    )
)

echo.
echo  ===============================================
echo    SETUP SELESAI
echo  ===============================================
echo.
echo   Cara menjalankan:
echo     1. Pastikan XAMPP ^& MySQL sudah jalan
echo     2. Jalankan:  php artisan serve
echo     3. Buka:      http://localhost:8000
echo.
set /p RUNSERVE="  Jalankan php artisan serve sekarang? (y/N): "
if /i "!RUNSERVE!"=="Y" (
    echo.
    call php artisan serve
)

endlocal
pause