@echo off
echo ===================================================
echo   SIResto - Otomatisasi Migrasi & Setup Database
echo ===================================================

if not exist .env (
    echo [1/3] File .env tidak ditemukan. Menyalin dari .env.example...
    copy .env.example .env >nul
)

set PHP_CMD=php
where php >nul 2>nul
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" (
        set PHP_CMD=C:\xampp\php\php.exe
    ) else (
        echo [ERROR] PHP tidak ditemukan! Pastikan XAMPP atau PHP sudah terinstall.
        pause
        exit /b 1
    )
)

echo [2/3] Menjalankan migrasi database MySQL & Seeding Data Sampel...
%PHP_CMD% artisan migrate:fresh --seed --force

echo ===================================================
echo [3/3] Migrasi Selesai! Database SIResto Siap Digunakan.
echo ===================================================
pause
