@echo off
REM Script untuk membuka folder hasil scraping BPKH & Produsen

echo ========================================
echo Buka Folder Hasil Scraping
echo ========================================
echo.
echo Pilih folder yang ingin dibuka:
echo 1. BPKH Forms
echo 2. Produsen DG Forms
echo 3. Buka Keduanya
echo.

set /p choice="Pilih opsi (1/2/3): "

cd /d "%~dp0"

if "%choice%"=="1" goto bpkh
if "%choice%"=="2" goto produsen
if "%choice%"=="3" goto both
goto invalid

:bpkh
echo.
echo Membuka folder BPKH...
start "" "storage\app\private\scrapping_script\bpkh_form"
goto end

:produsen
echo.
echo Membuka folder Produsen...
start "" "storage\app\private\scrapping_script\produsen_form"
goto end

:both
echo.
echo Membuka folder BPKH dan Produsen...
start "" "storage\app\private\scrapping_script\bpkh_form"
timeout /t 1 /nobreak >nul
start "" "storage\app\private\scrapping_script\produsen_form"
goto end

:invalid
echo.
echo Pilihan tidak valid!
goto end

:end
echo.
echo Folder dibuka di Windows Explorer!
echo.
pause
