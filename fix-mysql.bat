@echo off
REM ==========================================================
REM  XAMPP MySQL Recovery - InnoDB log sequence desync
REM  Tested: 2026-09-22 (mariadb 10.4.13 + InnoDB crash recovery)
REM ==========================================================
setlocal ENABLEDELAYEDEXPANSION
chcp 65001 >nul
title XAMPP MySQL Recovery Tool

set "XAMPP_DIR=D:\xampp"
set "MYSQL_DATA=%XAMPP_DIR%\mysql\data"
set "MYSQL_BIN=%XAMPP_DIR%\mysql\bin"
set "MYSQL_INI=%MYSQL_BIN%\my.ini"
set "DUMP_DIR=%XAMPP_DIR%\htdocs\app\storage\app\mysql-dump"
set "TS=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%"

echo.
echo  ===============================================
echo    XAMPP MySQL Recovery Tool v2
echo  ===============================================
echo.
echo  Target : %MYSQL_DATA%
echo  Dump   : %DUMP_DIR%
echo.

REM --- 1. Kill any mysqld ---
echo  [1/8] Kill any mysqld.exe...
taskkill /F /IM mysqld.exe /T 2>nul
timeout /t 3 /nobreak >nul
echo         OK.

REM --- 2. Backup current InnoDB files ---
echo  [2/8] Backup InnoDB files...
if exist "%MYSQL_DATA%\ibdata1" move /Y "%MYSQL_DATA%\ibdata1" "%MYSQL_DATA%\ibdata1.bak.%TS%" >nul
if exist "%MYSQL_DATA%\ib_logfile0" move /Y "%MYSQL_DATA%\ib_logfile0" "%MYSQL_DATA%\ib_logfile0.bak.%TS%" >nul
if exist "%MYSQL_DATA%\ib_logfile1" move /Y "%MYSQL_DATA%\ib_logfile1" "%MYSQL_DATA%\ib_logfile1.bak.%TS%" >nul
echo         Backup: *.bak.%TS%

REM --- 3. Clean any prior innodb_force_recovery in my.ini ---
echo  [3/8] Clean my.ini from old force_recovery...
findstr /C:"innodb_force_recovery" "%MYSQL_INI%" >nul 2>nul
if not errorlevel 1 (
    for /f "tokens=*" %%L in ('type "%MYSQL_INI%"') do (
        set "line=%%L"
        setlocal enabledelayedexpansion
        echo(!line!| findstr /C:"innodb_force_recovery" >nul
        if errorlevel 1 echo(!line!>>"%MYSQL_INI%.tmp"
        endlocal
    )
    move /Y "%MYSQL_INI%.tmp" "%MYSQL_INI%" >nul
)
powershell -NoProfile -Command "$c=[System.IO.File]::ReadAllText('%MYSQL_INI%'); if ($c.Length -gt 0 -and $c[0] -eq [char]0xFEFF) { $c=$c.Substring(1) }; $u=New-Object System.Text.UTF8Encoding $false; [System.IO.File]::WriteAllText('%MYSQL_INI%', $c, $u)" >nul 2>&1
echo         OK.

REM --- 4. Try start MySQL normal (will rebuild log files) ---
echo  [4/8] Try start MySQL normal (rebuilds ib_logfile*)...
start "MySQL" /B "%MYSQL_BIN%\mysqld.exe" --defaults-file="%MYSQL_INI%" --standalone
timeout /t 10 /nobreak >nul
%MYSQL_BIN%\mysql.exe -u root -e "SELECT VERSION();" >nul 2>nul
if not errorlevel 1 goto :success

REM --- 5. Normal start failed - try force_recovery=6 + skip-grant-tables ---
echo         Normal start gagal. Force recovery mode...
findstr /C:"innodb_force_recovery" "%MYSQL_INI%" >nul 2>nul
if errorlevel 1 (
    for /f "tokens=*" %%L in ('type "%MYSQL_INI%"') do (
        set "line=%%L"
        setlocal enabledelayedexpansion
        echo(!line!| findstr /C:"[mysqld]" >nul
        if not errorlevel 1 (
            echo(!line!
            echo innodb_force_recovery = 6
        ) else (
            echo(!line!
        )
        endlocal
    ) > "%MYSQL_INI%.tmp"
    move /Y "%MYSQL_INI%.tmp" "%MYSQL_INI%" >nul
)
powershell -NoProfile -Command "$c=[System.IO.File]::ReadAllText('%MYSQL_INI%'); if ($c.Length -gt 0 -and $c[0] -eq [char]0xFEFF) { $c=$c.Substring(1) }; $u=New-Object System.Text.UTF8Encoding $false; [System.IO.File]::WriteAllText('%MYSQL_INI%', $c, $u)" >nul 2>&1

taskkill /F /IM mysqld.exe /T 2>nul
timeout /t 2 /nobreak >nul
echo  [5/8] Start with innodb_force_recovery=6 + skip-grant-tables...
start "MySQL" /B "%MYSQL_BIN%\mysqld.exe" --defaults-file="%MYSQL_INI%" --standalone --skip-grant-tables
timeout /t 10 /nobreak >nul
%MYSQL_BIN%\mysql.exe -u root -e "SELECT VERSION();" >nul 2>nul
if errorlevel 1 (
    echo         [FATAL] Bahkan force_recovery=6 gagal. Manual intervention.
    pause & exit /b 1
)
echo         MySQL UP (recovery mode). Dumping databases...
if not exist "%DUMP_DIR%" mkdir "%DUMP_DIR%"
%MYSQL_BIN%\mysqldump.exe -u root --skip-lock-tables --single-transaction --routines --triggers --events --databases smartlibraryv2 app phpmyadmin test > "%DUMP_DIR%\all_databases.sql" 2>"%DUMP_DIR%\dump_errors.log"
for %%F in ("%DUMP_DIR%\all_databases.sql") do echo         Dump: %%~zF bytes

REM --- 6. Stop mysqld, fresh init data dir ---
echo  [6/8] Fresh init data directory...
taskkill /F /IM mysqld.exe /T 2>nul
timeout /t 3 /nobreak >nul
if exist "%MYSQL_DATA%\smartlibraryv2" rd /S /Q "%MYSQL_DATA%\smartlibraryv2"
if exist "%MYSQL_DATA%\app" rd /S /Q "%MYSQL_DATA%\app"
if exist "%MYSQL_DATA%\phpmyadmin" rd /S /Q "%MYSQL_DATA%\phpmyadmin"
if exist "%MYSQL_DATA%\test" rd /S /Q "%MYSQL_DATA%\test"
if exist "%MYSQL_DATA%\ibdata1" del /Q "%MYSQL_DATA%\ibdata1"
if exist "%MYSQL_DATA%\ibtmp1" del /Q "%MYSQL_DATA%\ibtmp1"
if exist "%MYSQL_DATA%\mysql" rd /S /Q "%MYSQL_DATA%\mysql"
if exist "%MYSQL_DATA%\performance_schema" rd /S /Q "%MYSQL_DATA%\performance_schema"

REM Strip innodb_force_recovery from my.ini
findstr /C:"innodb_force_recovery" "%MYSQL_INI%" >nul 2>nul
if not errorlevel 1 (
    for /f "tokens=*" %%L in ('type "%MYSQL_INI%"') do (
        set "line=%%L"
        setlocal enabledelayedexpansion
        echo(!line!| findstr /C:"innodb_force_recovery" >nul
        if errorlevel 1 echo(!line!>>"%MYSQL_INI%.tmp"
        endlocal
    )
    move /Y "%MYSQL_INI%.tmp" "%MYSQL_INI%" >nul
)
powershell -NoProfile -Command "$c=[System.IO.File]::ReadAllText('%MYSQL_INI%'); if ($c.Length -gt 0 -and $c[0] -eq [char]0xFEFF) { $c=$c.Substring(1) }; $u=New-Object System.Text.UTF8Encoding $false; [System.IO.File]::WriteAllText('%MYSQL_INI%', $c, $u)" >nul 2>&1

echo         Running mysql_install_db...
pushd "%MYSQL_BIN%"
"%MYSQL_BIN%\mysql_install_db.exe" --datadir="%MYSQL_DATA%" --default-user --allow-remote-root-access >nul 2>&1
if errorlevel 1 (
    echo         [FATAL] mysql_install_db gagal.
    popd
    pause & exit /b 1
)
popd
echo         OK.

REM --- 7. Start fresh MySQL + restore dump ---
echo  [7/8] Start fresh MySQL...
start "MySQL" /B "%MYSQL_BIN%\mysqld.exe" --defaults-file="%MYSQL_INI%" --standalone
timeout /t 10 /nobreak >nul
%MYSQL_BIN%\mysql.exe -u root -e "SELECT VERSION();" >nul 2>nul
if errorlevel 1 (
    echo         [GAGAL] Fresh MySQL tidak start. Cek log.
    pause & exit /b 1
)
echo         Fresh MySQL UP. Restoring dump...
powershell -NoProfile -Command "Get-Content '%DUMP_DIR%\all_databases.sql' | & '%MYSQL_BIN%\mysql.exe' -u root" 2>"%DUMP_DIR%\restore_errors.log"
if not errorlevel 1 (
    echo         Restore OK.
) else (
    echo         [WARNING] Restore ada error - lihat restore_errors.log
)

:success
echo.
echo  ===============================================
echo    RECOVERY SELESAI!
echo  ===============================================
echo.
echo  MySQL running di port 3306.
echo  Database user: root (no password) dari semua host.
echo.
echo  LANGKAH SELANJUTNYA (WAJIB):
echo    1. cd d:\xampp\htdocs\app
echo    2. php artisan migrate --force
echo       Jika error "table already exists", insert manual ke migrations:
echo       INSERT INTO smartlibraryv2.migrations (migration, batch) VALUES
echo       ('2016_06_01_000001_create_oauth_auth_codes_table', 1),
echo       ('2016_06_01_000002_create_oauth_access_tokens_table', 1),
echo       ('2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
echo       ('2016_06_01_000004_create_oauth_clients_table', 1),
echo       ('2016_06_01_000005_create_oauth_personal_access_clients_table', 1);
echo.
echo  Backup file: %MYSQL_DATA%\*.bak.%TS%
echo  Hapus .bak setelah yakin data OK.
echo.
pause
endlocal