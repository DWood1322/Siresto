@echo off
echo ===================================================
echo   SIResto - Menjalankan Server Lokal Restoran
echo ===================================================

if not exist .env (
    copy .env.example .env >nul
)

set PHP_CMD=php
where php >nul 2>nul
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" (
        set PHP_CMD=C:\xampp\php\php.exe
    )
)

%PHP_CMD% artisan serve
pause
