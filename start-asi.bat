@echo off
REM ASI System - Automatic System Inventory
REM Windows Batch Script untuk menjalankan aplikasi

echo ========================================
echo    ASI System - Development Server
echo ========================================
echo.

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP tidak ditemukan. Pastikan PHP 8.4+ sudah terinstall dan ada di PATH.
    echo.
    echo Download PHP: https://www.php.net/downloads
    pause
    exit /b 1
)

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer tidak ditemukan. Pastikan Composer sudah terinstall dan ada di PATH.
    echo.
    echo Download Composer: https://getcomposer.org/download/
    pause
    exit /b 1
)

REM Check if Node.js is installed
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Node.js tidak ditemukan. Pastikan Node.js 16+ sudah terinstall dan ada di PATH.
    echo.
    echo Download Node.js: https://nodejs.org/
    pause
    exit /b 1
)

REM Check if .env file exists
if not exist ".env" (
    echo [INFO] File .env tidak ditemukan. Menyalin dari .env.example...
    copy ".env.example" ".env"
    echo [INFO] Silakan edit file .env untuk konfigurasi database.
    echo.
)

REM Check if vendor directory exists
if not exist "vendor" (
    echo [INFO] Dependencies PHP belum terinstall. Menjalankan composer install...
    composer install
    echo.
)

REM Check if node_modules directory exists
if not exist "node_modules" (
    echo [INFO] Dependencies Node.js belum terinstall. Menjalankan npm install...
    npm install
    echo.
)

REM Generate application key if not exists
findstr /C:"APP_KEY=" .env | findstr /V /C:"APP_KEY=base64:" >nul
if %errorlevel% equ 0 (
    echo [INFO] Generating application key...
    php artisan key:generate
    echo.
)

REM Check database connection
echo [INFO] Checking database connection...
php artisan migrate:status >nul 2>&1
if %errorlevel% neq 0 (
    echo [WARNING] Database belum dikonfigurasi atau tidak dapat diakses.
    echo [INFO] Silakan:
    echo   1. Edit file .env untuk konfigurasi database
    echo   2. Jalankan: php artisan migrate --seed
    echo.
    set /p choice="Lanjutkan tanpa database? (y/n): "
    if /i not "%choice%"=="y" (
        echo [INFO] Setup database terlebih dahulu, lalu jalankan script ini lagi.
        pause
        exit /b 1
    )
)

REM Build assets if needed
if not exist "public\build" (
    echo [INFO] Building assets...
    npm run build
    echo.
)

REM Create storage link if not exists
if not exist "public\storage" (
    echo [INFO] Creating storage link...
    php artisan storage:link
    echo.
)

echo [SUCCESS] Semua persyaratan terpenuhi!
echo.
echo ========================================
echo           Starting Servers
echo ========================================
echo.
echo [INFO] Laravel Server: http://localhost:8000
echo [INFO] Tekan Ctrl+C untuk menghentikan server
echo.
echo [INFO] Untuk development dengan hot reload:
echo   Buka terminal baru dan jalankan: npm run dev
echo.

REM Start Laravel development server
php artisan serve --host=0.0.0.0 --port=8000

echo.
echo [INFO] Server dihentikan.
pause