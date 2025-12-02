@echo off
REM Batch script untuk scrape semua Produsen DG files
REM Cara pakai: Double click file ini atau jalankan dari command prompt

echo ========================================
echo Produsen DG File Scraper - Batch Mode
echo ========================================
echo.

REM Cek apakah user ingin scrape semua atau satu-satu
echo Pilihan:
echo 1. Scrape SEMUA Produsen DG sekaligus (cepat, tapi tidak bisa monitor per Produsen)
echo 2. Scrape satu-satu (lambat, tapi bisa monitor progress detail)
echo 3. Test dengan 1 Produsen saja (ID=1)
echo 4. Preview data Produsen (tanpa download)
echo.

set /p choice="Pilih opsi (1/2/3/4): "

if "%choice%"=="1" goto scrape_all
if "%choice%"=="2" goto scrape_one_by_one
if "%choice%"=="3" goto test_one
if "%choice%"=="4" goto preview
goto invalid

:scrape_all
echo.
echo Memulai scraping SEMUA Produsen DG...
echo.
php artisan produsen:scrape-files --all
goto end

:scrape_one_by_one
echo.
echo Scraping satu-satu...
echo Masukkan ID Produsen yang ingin di-scrape (pisahkan dengan spasi)
echo Contoh: 1 2 3 4 5
echo.
set /p ids="ID Produsen: "

for %%i in (%ids%) do (
    echo.
    echo ========================================
    echo Scraping Produsen ID: %%i
    echo ========================================
    php artisan produsen:scrape-files --id=%%i
)
goto end

:test_one
echo.
echo Testing dengan Produsen ID=1...
echo.
php artisan produsen:scrape-files --id=1
goto end

:preview
echo.
echo Menampilkan preview data Produsen...
echo.
php test-produsen-scraper.php
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
echo File tersimpan di: storage\app\private\scrapping_script\produsen_form\
echo.
pause
