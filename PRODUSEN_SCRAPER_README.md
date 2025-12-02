# 📥 Produsen DG File Scraper - Complete Guide

Script Laravel untuk scraping dan mengunduh file-file lampiran dari data Produsen DG form.

## 🎯 Fitur Utama

- ✅ Ekstraksi URL otomatis dari meta data Produsen DG
- ✅ Download file dengan nama yang sesuai: `no_[X]_[Y]_[Judul]_[Nama_File_Asli]`
- ✅ Organisasi file per folder Produsen
- ✅ Support multiple URLs dalam satu field
- ✅ Progress reporting real-time
- ✅ Error handling & retry mechanism
- ✅ SSL certificate handling untuk Windows

## 📁 File yang Dibuat

### Core Files

| File | Deskripsi |
|------|-----------|
| `app/Console/Commands/ScrapeProdusenFiles.php` | Artisan command utama untuk scraping |
| `test-produsen-scraper.php` | Script test untuk preview data |
| `scrape-all-produsen.bat` | Batch script untuk Windows |

## 🚀 Quick Start

### Windows

**Option 1: Double Click**
```
Double click file: scrape-all-produsen.bat
```

**Option 2: Command Line**
```bash
# Preview data dulu
php test-produsen-scraper.php

# Test dengan 1 Produsen
php artisan produsen:scrape-files --id=1

# Scrape semua
php artisan produsen:scrape-files --all
```

## 📊 Output Structure

```
storage/app/private/scrapping_script/produsen_form/
├── produsen_perencanaan_dan_evaluasi_pengelolaan_daerah_aliran_sungai/
│   ├── no_1_1_SK_Terbaru_1.1.-SK-TIM-PELAKSANA-JARINGAN-IGT.pdf
│   ├── no_4_1_dokumentasi_4.1.-PROYEKSI-DAN-KOORDINAT.pdf
│   ├── no_8_2_bukti_dokumen_analisis_kebutuhan_8.2.-SK-MENHUT-426-2025-ABK.pdf
│   └── ...
├── produsen_perencanaan_konservasi/
│   └── ...
└── produsen_konservasi_spesies_dan_genetik/
    └── ...
```

## 📝 Command Reference

```bash
# Show help
php artisan produsen:scrape-files --help

# Preview data (no download)
php test-produsen-scraper.php

# Scrape specific Produsen
php artisan produsen:scrape-files --id=1

# Scrape all Produsen
php artisan produsen:scrape-files --all

# List all produsen commands
php artisan list produsen
```

## ✅ Test Results

**Status:** ✅ Tested & Working

**Test Data:**
- Produsen: Direktorat Perencanaan dan Evaluasi Pengelolaan DAS
- Files Downloaded: 27 files (~40+ MB)
- Success Rate: 100%

**Sample Files:**
- `no_1_1_SK_Terbaru_1.1.-SK-TIM-PELAKSANA-JARINGAN-IGT.pdf`
- `no_4_1_dokumentasi_4.1.-PROYEKSI-DAN-KOORDINAT.pdf`
- `no_8_2_bukti_dokumen_analisis_kebutuhan_8.2.-SK-MENHUT-426-2025-ABK.pdf`

## 📋 Format Nama File

**Format:** `no_[X]_[Y]_[judul_bersih]_[nama_file_asli]`

**Contoh:**
- Input: 
  - Soal: 8.2
  - Judul: "Lampiran bukti dokumen analisis kebutuhan"
  - File: "8.2.-SK-MENHUT-426-2025-ABK.pdf"
- Output: 
  - `no_8_2_bukti_dokumen_analisis_kebutuhan_8.2.-SK-MENHUT-426-2025-ABK.pdf`

## 🔧 Configuration

### Timeout Setting

Default: 120 seconds per file

Untuk mengubah, edit `app/Console/Commands/ScrapeProdusenFiles.php`:
```php
->timeout(120) // Ubah angka ini
```

### SSL Verification

Default: Disabled (untuk development)

Untuk production, edit `app/Console/Commands/ScrapeProdusenFiles.php`:
```php
Http::withOptions([
    'verify' => true, // Enable SSL verification
])->timeout(120)->get($url);
```

## 🎬 Workflow Recommendation

### Untuk Testing
```bash
# 1. Preview data dulu
php test-produsen-scraper.php

# 2. Test dengan 1 Produsen
php artisan produsen:scrape-files --id=1

# 3. Cek hasil di folder
# storage/app/private/scrapping_script/produsen_form/

# 4. Jika OK, scrape beberapa Produsen
php artisan produsen:scrape-files --id=2
php artisan produsen:scrape-files --id=3
```

### Untuk Production
```bash
# Scrape semua sekaligus
php artisan produsen:scrape-files --all
```

## 💡 Tips

1. **Cek koneksi internet** sebelum scrape
2. **Cek storage space** (estimasi: ~50-100 MB per Produsen)
3. **Jalankan saat off-peak** untuk koneksi lebih stabil
4. **Backup hasil** setelah scraping selesai

## ⚠️ Important Notes

1. Script **TIDAK** akan overwrite file yang sudah ada
2. Jika ingin re-download, hapus folder Produsen terlebih dahulu
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

## 📚 Dokumentasi Terkait

- **BPKH Scraper:** `BPKH_SCRAPER_README.md`
- **Quick Start BPKH:** `BPKH_SCRAPER_QUICK_START.md`

## 📞 Support

Jika ada masalah, cek:
1. Error message di console
2. Laravel log: `storage/logs/laravel.log`
3. Dokumentasi BPKH untuk referensi (sama strukturnya)

## 📅 Version History

- **v1.0** (2 Desember 2024)
  - Initial release
  - Support scraping dari meta data Produsen DG
  - Auto naming dengan format `no_[X]_[Y]_[judul]_[file]`
  - SSL handling untuk Windows
  - Batch scripts untuk Windows

---

**Created:** 2 Desember 2024  
**Author:** HolitSky Development Team  
**Laravel Version:** 12.x  
**PHP Version:** 8.1+
