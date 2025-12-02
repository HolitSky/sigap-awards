# 📥 BPKH File Scraper - Complete Guide

Script Laravel untuk scraping dan mengunduh file-file lampiran dari data BPKH form.

## 🎯 Fitur Utama

- ✅ Ekstraksi URL otomatis dari meta data BPKH
- ✅ Download file dengan nama yang sesuai: `[Judul]_[Nama_File_Asli]`
- ✅ Organisasi file per folder BPKH
- ✅ Support multiple URLs dalam satu field
- ✅ Progress reporting real-time
- ✅ Error handling & retry mechanism
- ✅ SSL certificate handling untuk Windows

## 📁 File yang Dibuat

### 1. Core Files

| File | Deskripsi |
|------|-----------|
| `app/Console/Commands/ScrapeBpkhFiles.php` | Artisan command utama untuk scraping |
| `test-bpkh-scraper.php` | Script test untuk preview data |

### 2. Documentation

| File | Deskripsi |
|------|-----------|
| `docs/BPKH_FILE_SCRAPER.md` | Dokumentasi lengkap |
| `BPKH_SCRAPER_QUICK_START.md` | Quick start guide |
| `BPKH_SCRAPER_TEST_RESULTS.md` | Hasil testing |
| `BPKH_SCRAPER_README.md` | File ini |

### 3. Batch Scripts

| File | Deskripsi |
|------|-----------|
| `scrape-all-bpkh.bat` | Batch script untuk Windows |
| `scrape-all-bpkh.sh` | Shell script untuk Linux/Mac |

## 🚀 Quick Start

### Windows

**Option 1: Double Click**
```
Double click file: scrape-all-bpkh.bat
```

**Option 2: Command Line**
```bash
# Preview data dulu
php test-bpkh-scraper.php

# Test dengan 1 BPKH
php artisan bpkh:scrape-files --id=1

# Scrape semua
php artisan bpkh:scrape-files --all
```

### Linux/Mac

```bash
# Beri permission execute
chmod +x scrape-all-bpkh.sh

# Jalankan
./scrape-all-bpkh.sh
```

## 📊 Output Structure

```
storage/app/private/scrapping_script/bpkh_form/
├── bpkh_wilayah_xxii_kendari/
│   ├── SK_Terbaru_SK.36_Penetapan-Tim-Pengelola-Jaringan-Informasi-Geospasial_2025.pdf
│   ├── bukti_dokumen_laporan_BPKH_LAPORAN-PENYEBARAN-IGT-TAHUN-2024.pdf
│   └── ...
├── bpkh_wilayah_iv/
│   └── ...
└── bpkh_wilayah_vi/
    └── ...
```

## 📝 Command Reference

```bash
# Show help
php artisan bpkh:scrape-files --help

# Preview data (no download)
php test-bpkh-scraper.php

# Scrape specific BPKH
php artisan bpkh:scrape-files --id=1

# Scrape all BPKH
php artisan bpkh:scrape-files --all

# List all bpkh commands
php artisan list bpkh
```

## ✅ Test Results

**Status:** ✅ Tested & Working

**Test Data:**
- BPKH: Wilayah XXII Kendari
- Files Downloaded: 6 files (~10.6 MB)
- Success Rate: 100%

**Sample Files:**
- SK_Terbaru_SK.36_Penetapan-Tim-Pengelola-Jaringan-Informasi-Geospasial_2025.pdf (2.34 MB)
- bukti_dokumen_laporan_BPKH_LAPORAN-PENYEBARAN-IGT-TAHUN-2024.pdf (5.01 MB)
- SOP_Alur_dan_Bukti_Sosialisasi_Klausul-8.2.1-SOP.9-PPID.pdf (215.56 KB)

## 🔧 Configuration

### Timeout Setting

Default: 120 seconds per file

Untuk mengubah, edit `app/Console/Commands/ScrapeBpkhFiles.php`:
```php
->timeout(120) // Ubah angka ini
```

### SSL Verification

Default: Disabled (untuk development)

Untuk production, edit `app/Console/Commands/ScrapeBpkhFiles.php`:
```php
Http::withOptions([
    'verify' => true, // Enable SSL verification
])->timeout(120)->get($url);
```

## 📖 Dokumentasi Lengkap

Untuk informasi lebih detail, baca:

1. **Quick Start:** `BPKH_SCRAPER_QUICK_START.md`
2. **Full Documentation:** `docs/BPKH_FILE_SCRAPER.md`
3. **Test Results:** `BPKH_SCRAPER_TEST_RESULTS.md`

## 🎬 Workflow Recommendation

### Untuk Testing
```bash
# 1. Preview data dulu
php test-bpkh-scraper.php

# 2. Test dengan 1 BPKH
php artisan bpkh:scrape-files --id=1

# 3. Cek hasil di folder
# storage/app/private/scrapping_script/bpkh_form/

# 4. Jika OK, scrape beberapa BPKH
php artisan bpkh:scrape-files --id=2
php artisan bpkh:scrape-files --id=3
```

### Untuk Production
```bash
# Scrape semua sekaligus
php artisan bpkh:scrape-files --all
```

## 💡 Tips

1. **Cek koneksi internet** sebelum scrape
2. **Cek storage space** (estimasi: ~50-100 MB per BPKH)
3. **Jalankan saat off-peak** untuk koneksi lebih stabil
4. **Backup hasil** setelah scraping selesai

## ⚠️ Important Notes

1. Script **TIDAK** akan overwrite file yang sudah ada
2. Jika ingin re-download, hapus folder BPKH terlebih dahulu
3. URL dari Tally.so memiliki access token yang mungkin expire
4. SSL verification disabled untuk development (Windows)

## 🐛 Troubleshooting

### Problem: SSL Certificate Error
**Solution:** Sudah ditangani otomatis dengan `verify => false`

### Problem: File tidak terunduh
**Check:**
- Koneksi internet
- URL masih valid
- Access token belum expire

### Problem: Timeout
**Solution:** Increase timeout di config (default: 120s)

### Problem: Permission denied
**Solution (Windows):**
```bash
icacls storage /grant Users:F /t
```

## 📞 Support

Jika ada masalah, cek:
1. Error message di console
2. Laravel log: `storage/logs/laravel.log`
3. Dokumentasi lengkap di `docs/BPKH_FILE_SCRAPER.md`

## 📅 Version History

- **v1.0** (2 Desember 2024)
  - Initial release
  - Support scraping dari meta data BPKH
  - Auto naming dengan format `[Judul]_[Nama_File_Asli]`
  - SSL handling untuk Windows
  - Batch scripts untuk Windows & Linux/Mac

---

**Created:** 2 Desember 2024  
**Author:** HolitSky Development Team  
**Laravel Version:** 12.x  
**PHP Version:** 8.1+
