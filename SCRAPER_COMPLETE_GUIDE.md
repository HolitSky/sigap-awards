# 📥 SIGAP Awards File Scraper - Complete Guide

Script Laravel untuk scraping dan mengunduh file-file lampiran dari data BPKH dan Produsen DG forms.

## 🎯 Overview

Sistem scraping ini terdiri dari 2 command utama:
1. **BPKH Scraper** - Untuk form BPKH (Balai Pemantapan Kawasan Hutan)
2. **Produsen DG Scraper** - Untuk form Produsen Data Geospasial

## 📊 Quick Comparison

| Feature | BPKH Scraper | Produsen Scraper |
|---------|--------------|------------------|
| Command | `php artisan bpkh:scrape-files` | `php artisan produsen:scrape-files` |
| Model | `BpkhForm` | `ProdusenForm` |
| Output Folder | `scrapping_script/bpkh_form/` | `scrapping_script/produsen_form/` |
| Batch Script | `scrape-all-bpkh.bat` | `scrape-all-produsen.bat` |
| Test Script | `test-bpkh-scraper.php` | `test-produsen-scraper.php` |
| Avg Files/Form | ~24 files | ~28 files |

## 🚀 Quick Start

### Option 1: Batch Scripts (Recommended untuk Windows)

**BPKH:**
```bash
# Double click
scrape-all-bpkh.bat
```

**Produsen:**
```bash
# Double click
scrape-all-produsen.bat
```

### Option 2: Command Line

**Preview Data:**
```bash
# BPKH
php test-bpkh-scraper.php

# Produsen
php test-produsen-scraper.php
```

**Scrape Specific ID:**
```bash
# BPKH
php artisan bpkh:scrape-files --id=1

# Produsen
php artisan produsen:scrape-files --id=1
```

**Scrape All:**
```bash
# BPKH
php artisan bpkh:scrape-files --all

# Produsen
php artisan produsen:scrape-files --all
```

## 📁 Output Structure

```
storage/app/private/scrapping_script/
├── bpkh_form/
│   ├── bpkh_wilayah_xxii_kendari/
│   │   ├── no_1_1_SK_Terbaru_SK.36_Penetapan-Tim-Pengelola...pdf
│   │   ├── no_8_2_bukti_dokumen_surat_hasil_analisis...pdf
│   │   └── ...
│   ├── bpkh_wilayah_iv/
│   └── bpkh_wilayah_vi/
│
└── produsen_form/
    ├── produsen_perencanaan_dan_evaluasi_pengelolaan_daerah_aliran_sungai/
    │   ├── no_1_1_SK_Terbaru_1.1.-SK-TIM-PELAKSANA...pdf
    │   ├── no_8_2_bukti_dokumen_analisis_kebutuhan...pdf
    │   └── ...
    ├── produsen_perencanaan_konservasi/
    └── produsen_konservasi_spesies_dan_genetik/
```

## 📋 Format Nama File

**Format:** `no_[X]_[Y]_[judul_bersih]_[nama_file_asli]`

**Contoh:**
```
no_8_2_bukti_dokumen_surat_hasil_analisis_kebutuhan_analis-kebutuhan.pdf
│  │ │ │                                                   │
│  │ │ └── Judul (tanpa "Lampiran")                       └── Nama file asli
│  │ └──── Underscore separator
│  └────── Nomor sub-soal
└───────── Nomor soal utama
```

**Breakdown:**
- `no_8_2` = Dari "soal 8.2"
- `bukti_dokumen_surat_hasil_analisis_kebutuhan` = Dari "Lampiran bukti dokumen surat hasil analisis kebutuhan"
- `analis-kebutuhan.pdf` = Nama file asli dari URL

## 🎯 Fitur Utama

✅ **Auto Numbering** - Prefix nomor soal otomatis dari context  
✅ **Clean Naming** - Hapus prefix "Lampiran" dan karakter khusus  
✅ **Original Filename** - Tetap menyimpan nama file asli  
✅ **Folder Organization** - Terorganisir per BPKH/Produsen  
✅ **Progress Reporting** - Real-time progress dengan emoji  
✅ **Error Handling** - Tangani timeout, SSL, network errors  
✅ **SSL Support** - Auto handle SSL certificate issues (Windows)  

## 📝 All Available Commands

### BPKH Commands
```bash
php artisan bpkh:scrape-files --help
php artisan bpkh:scrape-files --id=1
php artisan bpkh:scrape-files --all
php artisan list bpkh
```

### Produsen Commands
```bash
php artisan produsen:scrape-files --help
php artisan produsen:scrape-files --id=1
php artisan produsen:scrape-files --all
php artisan list produsen
```

### Helper Scripts
```bash
# Preview data
php test-bpkh-scraper.php
php test-produsen-scraper.php

# Open folders
open-scraped-files.bat

# Batch scraping
scrape-all-bpkh.bat
scrape-all-produsen.bat
```

## ✅ Test Results

### BPKH Scraper
- **Status:** ✅ Tested & Working
- **Test Form:** BPKH Wilayah XXII Kendari
- **Files:** 6 files (~10.6 MB)
- **Success Rate:** 100%

### Produsen Scraper
- **Status:** ✅ Tested & Working
- **Test Form:** Direktorat Perencanaan dan Evaluasi Pengelolaan DAS
- **Files:** 27 files (~40+ MB)
- **Success Rate:** 100%

## 🎬 Recommended Workflow

### Step 1: Preview Data
```bash
# Cek berapa form yang ada
php test-bpkh-scraper.php
php test-produsen-scraper.php
```

### Step 2: Test with 1 Form
```bash
# Test dengan 1 form dulu
php artisan bpkh:scrape-files --id=1
php artisan produsen:scrape-files --id=1
```

### Step 3: Check Results
```bash
# Buka folder hasil scraping
open-scraped-files.bat
```

### Step 4: Scrape All (if OK)
```bash
# Scrape semua data
php artisan bpkh:scrape-files --all
php artisan produsen:scrape-files --all
```

## 💡 Pro Tips

### 1. Scrape Saat Off-Peak
Jalankan scraping di malam hari atau saat koneksi internet stabil.

### 2. Monitor Storage Space
```bash
# Cek space sebelum scrape
dir storage\app\private\scrapping_script /s
```

### 3. Backup Results
Setelah scraping selesai, backup folder hasil:
```bash
# Copy ke external drive atau cloud storage
xcopy storage\app\private\scrapping_script D:\backup\ /E /I
```

### 4. Re-scrape Specific Form
```bash
# Hapus folder lama
Remove-Item -Path "storage\app\private\scrapping_script\bpkh_form\bpkh_wilayah_xxii_kendari" -Recurse

# Scrape ulang
php artisan bpkh:scrape-files --id=1
```

### 5. Batch Processing
```bash
# Scrape beberapa form sekaligus
php artisan bpkh:scrape-files --id=1
php artisan bpkh:scrape-files --id=2
php artisan bpkh:scrape-files --id=3
```

## 🔧 Configuration

### Timeout (Default: 120s)
Edit file command:
```php
// app/Console/Commands/ScrapeBpkhFiles.php
// app/Console/Commands/ScrapeProdusenFiles.php

->timeout(120) // Ubah sesuai kebutuhan
```

### SSL Verification (Default: Disabled)
```php
Http::withOptions([
    'verify' => false, // Development
    // 'verify' => true, // Production
])->timeout(120)->get($url);
```

## 📚 Documentation Files

| File | Description |
|------|-------------|
| `SCRAPER_COMPLETE_GUIDE.md` | This file - Complete guide |
| `BPKH_SCRAPER_README.md` | BPKH scraper documentation |
| `BPKH_SCRAPER_QUICK_START.md` | BPKH quick start guide |
| `BPKH_SCRAPER_TEST_RESULTS.md` | BPKH test results |
| `PRODUSEN_SCRAPER_README.md` | Produsen scraper documentation |

## 🐛 Troubleshooting

### Problem: SSL Certificate Error
**Solution:** Sudah ditangani otomatis dengan `verify => false`

### Problem: File tidak terunduh
**Check:**
1. Koneksi internet
2. URL masih valid (coba buka di browser)
3. Access token belum expire

### Problem: Timeout
**Solution:** 
- Increase timeout setting
- Cek koneksi internet
- Scrape satu-satu instead of all

### Problem: Permission Denied
**Solution (Windows):**
```bash
icacls storage /grant Users:F /t
```

### Problem: Out of Memory
**Solution:**
- Scrape satu-satu instead of all
- Increase PHP memory limit di `php.ini`

## ⚠️ Important Notes

1. **No Overwrite** - Script tidak akan overwrite file yang sudah ada
2. **Token Expiry** - URL dari Tally.so memiliki access token yang bisa expire
3. **SSL Disabled** - SSL verification disabled untuk development (Windows)
4. **Storage Space** - Pastikan ada cukup space (~2-5 GB untuk semua data)

## 📞 Support

Jika ada masalah:
1. Cek error message di console
2. Cek Laravel log: `storage/logs/laravel.log`
3. Baca dokumentasi spesifik (BPKH atau Produsen)
4. Test dengan 1 form dulu sebelum scrape all

## 📅 Version History

- **v1.0** (2 Desember 2024)
  - Initial release
  - BPKH scraper
  - Produsen scraper
  - Auto numbering dengan format `no_X_Y_`
  - SSL handling untuk Windows
  - Batch scripts & documentation

---

**Created:** 2 Desember 2024  
**Author:** HolitSky Development Team  
**Project:** SIGAP Awards 2025  
**Laravel Version:** 12.x  
**PHP Version:** 8.1+
