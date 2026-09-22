# Setup Clone & Jalankan 1 klik

Project ini adalah aplikasi Laravel. Setelah git clone, jalankan satu perintah untuk setup otomatis:

    setup.bat

Itu saja. Script akan otomatis:

1. Cek PHP & Composer tersedia
2. Skip composer install karena vendor/ sudah termasuk di repo (tidak perlu download)
3. Copy .env.example ke .env (kalau belum ada)
4. php artisan key:generate
5. Clear semua cache
6. Buat storage symlink
7. (Opsional) php artisan migrate setelah konfirmasi
8. (Opsional) langsung jalankan php artisan serve

Catatan: folder vendor/ sudah di-commit ke repo (sekitar 44 MB) supaya clone
langsung siap jalan tanpa perlu composer install.

---

## Prasyarat

Sebelum clone, pastikan di komputer tujuan sudah ada:

- PHP versi 7.3 atau lebih baru (cek: php -v)
- MySQL atau MariaDB (lewat XAMPP Control Panel)
- Git (cek: git --version)

Composer TIDAK wajib di komputer klien karena vendor/ sudah di-bundle.

Belum install? Install XAMPP dari https://www.apachefriends.org (sudah termasuk PHP + MySQL).

---

## Workflow lengkap di komputer baru

    REM 1. Clone repo
    git clone https://github.com/USERNAME/REPO.git
    cd REPO

    REM 2. Jalankan setup 1-klik
    setup.bat

    REM 3. (Di akhir setup) jawab Y untuk langsung buka serve

Lalu buka browser ke http://localhost:8000.

---

## Yang perlu diedit manual di .env

Setelah setup.bat membuat .env, edit bagian ini agar sesuai database lokal:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_kamu
    DB_USERNAME=root
    DB_PASSWORD=

Jangan lupa buat database nama_database_kamu dulu di phpMyAdmin (http://localhost/phpmyadmin) sebelum menjalankan migrasi.

---

## FAQ

Q: Bisa langsung jalan tanpa composer install?
A: YA. Folder vendor/ sudah di-commit ke repo (sekitar 44 MB). Setelah clone, langsung jalankan setup.bat dan tidak perlu download dependency apapun.

Q: Kenapa .env juga tidak di-push?
A: Isinya kredensial database, API key, dan lain-lain. Sensitif dan beda tiap environment (lokal vs server produksi). Gunakan .env.example sebagai template.

Q: Folder ebook-file/ apa?
A: Aset upload ebook PDF + cover image (14.000+ file). Sengaja tidak di-push. Setelah clone, struktur folder ini akan dibuat otomatis oleh sistem ketika user upload ebook pertama kali.

Q: Setup.bat gagal di langkah mana?
A: Lihat pesan error di layar. Penyebab umum:
  - key:generate gagal: pastikan .env writable dan folder storage/ writable
  - migrate gagal: cek DB sudah dibuat di phpMyAdmin dan kredensial di .env benar

Q: Bagaimana kalau ada update dependency baru (misal tambah package via composer)?
A: Karena vendor/ di-commit, setelah composer require <package> di komputer kamu:
  1. composer install (di komputer kamu untuk download)
  2. git add vendor/ -f
  3. git commit -m "Add <package> dependency"
  4. push ke repo
  Clone dari komputer lain akan otomatis dapat package baru tanpa composer install.

---

## Perintah harian (setelah setup)

    REM Jalankan server
    php artisan serve

    REM Bersihkan cache kalau ada perubahan config atau view
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear

## Recovery MySQL (kalau XAMPP "shutdown unexpectedly")

Kalau XAMPP Control Panel menampilkan error **"MySQL shutdown unexpectedly"** dengan pesan log InnoDB seperti:

    [ERROR] InnoDB: Page [page id: space=0, page number=X] log sequence number YYY is in the future!
    [ERROR] InnoDB: Your database may be corrupt or you may have copied the InnoDB tablespace but not the InnoDB log files.

Maka InnoDB tablespace (`ibdata1`) tidak sinkron dengan redo logs (`ib_logfile0/1`). Penyebab umum: MySQL di-shutdown paksa (kill dari Task Manager / mati listrik), atau `ibdata1` di-restore dari backup tanpa redo logs.

Jalankan tool recovery yang sudah tersedia:

    fix-mysql.bat

Script akan otomatis (8 langkah):
1. Kill mysqld.exe (kalau masih jalan)
2. Backup `ibdata1` + `ib_logfile*` ke `*.bak.YYYYMMDD_HHMMSS`
3. Bersihkan `innodb_force_recovery` lama dari `my.ini` (jika ada)
4. Coba start MySQL normal — InnoDB auto-rebuild redo logs
5. Kalau masih gagal, start dengan `innodb_force_recovery=6 + --skip-grant-tables` lalu dump semua database
6. Stop MySQL, hapus folder database + InnoDB files + folder `mysql/` + `performance_schema/`
7. Run `mysql_install_db.exe` untuk re-init system tables
8. Start MySQL fresh + restore dump dari langkah 5

Dump otomatis disimpan ke `storage\app\mysql-dump\all_databases.sql` (53 MB untuk project ini).

**Setelah recovery sukses**, jalankan langkah tambahan:

    REM Kalau error "Table 'oauth_auth_codes' already exists" saat migrate:
    REM Insert manual 5 record ke tabel migrations, baru migrate ulang:
    mysql -u root smartlibraryv2 -e "INSERT IGNORE INTO migrations (migration, batch) VALUES \
      ('2016_06_01_000001_create_oauth_auth_codes_table', 1), \
      ('2016_06_01_000002_create_oauth_access_tokens_table', 1), \
      ('2016_06_01_000003_create_oauth_refresh_tokens_table', 1), \
      ('2016_06_01_000004_create_oauth_clients_table', 1), \
      ('2016_06_01_000005_create_oauth_personal_access_clients_table', 1);"

    php artisan migrate --force

**Backup files di `D:\xampp\mysql\data\*.bak.YYYYMMDD_HHMMSS`** aman dihapus setelah yakin data application restore dengan benar. Keep minimal sampai 1 minggu untuk jaga-jaga.

### Verifikasi koneksi DB setelah recovery

    REM Cek mysqld jalan
    tasklist /FI "IMAGENAME eq mysqld.exe"

    REM Cek port 3306 listening
    netstat -an | findstr :3306

    REM Test query
    d:\xampp\mysql\bin\mysql.exe -u root -e "SELECT VERSION(); SHOW DATABASES;"

Kalau `SHOW DATABASES` tidak menampilkan `smartlibraryv2` (atau database project), cek file `storage\app\mysql-dump\restore_errors.log` — kemungkinan dump terpotong karena tablespace conflict atau stderr mysqldump ikut tercampur. Solusi: hapus folder database di `D:\xampp\mysql\data\<nama_db>\` lalu re-restore dump:

    Get-Content storage\app\mysql-dump\all_databases.sql | d:\xampp\mysql\bin\mysql.exe -u root

# Setup Clone & Jalankan 1 klik

Project ini adalah aplikasi Laravel. Setelah git clone, jalankan satu perintah untuk setup otomatis:

    setup.bat

Itu saja. Script akan otomatis:

1. Cek PHP & Composer tersedia
2. composer install (mengikuti composer.lock, versi exact, cepat 1-2 menit)
3. Copy .env.example ke .env (kalau belum ada)
4. php artisan key:generate
5. Clear semua cache
6. Buat storage symlink
7. (Opsional) php artisan migrate setelah konfirmasi
8. (Opsional) langsung jalankan php artisan serve

---

## Prasyarat

Sebelum clone, pastikan di komputer tujuan sudah ada:

- PHP versi 7.3 atau lebih baru (cek: php -v)
- Composer versi 2 atau lebih baru (cek: composer --version)
- MySQL atau MariaDB (lewat XAMPP Control Panel)
- Git (cek: git --version)

Belum install? Install XAMPP dari https://www.apachefriends.org (sudah termasuk PHP + MySQL), lalu install Composer dari https://getcomposer.org/.

---

## Workflow lengkap di komputer baru

    REM 1. Clone repo
    git clone https://github.com/USERNAME/REPO.git
    cd REPO

    REM 2. Jalankan setup 1-klik
    setup.bat

    REM 3. (Di akhir setup) jawab Y untuk langsung buka serve

Lalu buka browser ke http://localhost:8000.

---

## Yang perlu diedit manual di .env

Setelah setup.bat membuat .env, edit bagian ini agar sesuai database lokal:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_kamu
    DB_USERNAME=root
    DB_PASSWORD=

Jangan lupa buat database nama_database_kamu dulu di phpMyAdmin (http://localhost/phpmyadmin) sebelum menjalankan migrasi.

---

## FAQ

Q: Bisa langsung jalan tanpa composer install?
A: Tidak bisa. Folder vendor/ (isi Laravel + dependency) sengaja tidak di-push ke git karena ukurannya besar (ratusan MB). composer install membaca composer.lock yang sudah ter-push, jadi otomatis pakai versi exact yang sama dengan komputer kamu sekarang. Biasanya 1-2 menit.

Q: Kenapa .env juga tidak di-push?
A: Isinya kredensial database, API key, dan lain-lain. Sensitif dan beda tiap environment (lokal vs server produksi). Gunakan .env.example sebagai template.

Q: Folder ebook-file/ apa?
A: Aset upload ebook PDF + cover image (14.000+ file). Sengaja tidak di-push. Setelah clone, struktur folder ini akan dibuat otomatis oleh sistem ketika user upload ebook pertama kali.

Q: Setup.bat gagal di langkah mana?
A: Lihat pesan error di layar. Penyebab umum:
  - composer install gagal: cek koneksi internet / proxy
  - key:generate gagal: pastikan .env writable dan folder storage/ writable
  - migrate gagal: cek DB sudah dibuat di phpMyAdmin dan kredensial di .env benar

---

## Perintah harian (setelah setup)

    REM Jalankan server
    php artisan serve

    REM Bersihkan cache kalau ada perubahan config atau view
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
