@echo off
REM Batch script untuk scrape semua BPKH files
REM Cara pakai: Double click file ini atau jalankan dari command prompt

echo ========================================
echo BPKH File Scraper - Batch Mode
echo ========================================
echo.

REM Cek apakah user ingin scrape semua atau satu-satu
echo Pilihan:
echo 1. Scrape SEMUA BPKH sekaligus (cepat, tapi tidak bisa monitor per BPKH)
echo 2. Scrape satu-satu (lambat, tapi bisa monitor progress detail)
echo 3. Test dengan 1 BPKH saja (ID=1)
echo 4. Preview data BPKH (tanpa download)
echo.

set /p choice="Pilih opsi (1/2/3/4): "

if "%choice%"=="1" goto scrape_all
if "%choice%"=="2" goto scrape_one_by_one
if "%choice%"=="3" goto test_one
if "%choice%"=="4" goto preview
goto invalid

:scrape_all
echo.
echo Memulai scraping SEMUA BPKH...
echo.
php artisan bpkh:scrape-files --all
goto end

:scrape_one_by_one
echo.
echo Scraping satu-satu...
echo Masukkan ID BPKH yang ingin di-scrape (pisahkan dengan spasi)
echo Contoh: 1 2 3 4 5
echo.
set /p ids="ID BPKH: "

for %%i in (%ids%) do (
    echo.
    echo ========================================
    echo Scraping BPKH ID: %%i
    echo ========================================
    php artisan bpkh:scrape-files --id=%%i
)
goto end

:test_one
echo.
echo Testing dengan BPKH ID=1...
echo.
php artisan bpkh:scrape-files --id=1
goto end

:preview
echo.
echo Menampilkan preview data BPKH...
echo.
php test-bpkh-scraper.php
goto end

:invalid
echo.
echo Pilihan tidak valid!
goto end

:end
echo.
echo ========================================
echo Selesai!
echo ========================================
echo.
echo File tersimpan di: storage\app\private\scrapping_script\bpkh_form\
echo.
pause
