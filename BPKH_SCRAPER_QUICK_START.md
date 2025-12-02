# BPKH File Scraper - Quick Start Guide

## 🚀 Cara Cepat Menggunakan

### Step 1: Buka Terminal/Command Prompt

Pastikan Anda berada di folder root project Laravel:
```bash
cd d:\06 - web-application\www\sigap-awards
```

### Step 2: Jalankan Command

#### Untuk scrape SEMUA data BPKH:
```bash
php artisan bpkh:scrape-files --all
```

#### Untuk scrape BPKH tertentu (misal ID 5):
```bash
php artisan bpkh:scrape-files --id=5
```

### Step 3: Tunggu Proses Selesai

Script akan menampilkan progress seperti ini:
```
🚀 Starting BPKH Files Scraping...
Found 10 BPKH form(s) to process.

📋 Processing: BPKH Palangkaraya (ID: 1)
  📎 Lampiran SK Terbaru
    ⬇️  Downloading: Lampiran_SK_Terbaru_SK-Pengelola-TAPAL21.pdf
    ✓ Saved: scrapping_script/bpkh_form/bpkh_palangkaraya/... (2.45 MB)

✅ Scraping completed!
📥 Total files downloaded: 25
```

### Step 4: Cek Hasil Download

File akan tersimpan di:
```
storage/app/private/scrapping_script/bpkh_form/
```

Struktur folder:
```
storage/app/private/scrapping_script/
└── bpkh_form/
    ├── bpkh_wilayah_xxii_kendari/
    │   ├── SK_Terbaru_SK.36_Penetapan-Tim-Pengelola-Jaringan-Informasi-Geospasial_2025.pdf
    │   ├── bukti_dokumen_laporan_BPKH_LAPORAN-PENYEBARAN-IGT-TAHUN-2024.pdf
    │   └── ...
    ├── bpkh_wilayah_iv/
    └── bpkh_wilayah_vi/
```

## 📝 Contoh Penggunaan

### Cek dulu ada berapa BPKH:
```bash
php artisan tinker
>>> App\Models\BpkhForm::count()
=> 10
>>> App\Models\BpkhForm::pluck('id', 'nama_bpkh')
```

### Scrape satu-satu untuk testing:
```bash
php artisan bpkh:scrape-files --id=1
```

### Scrape semua sekaligus:
```bash
php artisan bpkh:scrape-files --all
```

## ⚙️ Konfigurasi (Opsional)

Jika ingin mengubah timeout download, edit file:
```
app/Console/Commands/ScrapeBpkhFiles.php
```

Cari baris:
```php
Http::timeout(120)->get($url);
```

Ubah `120` (detik) sesuai kebutuhan.

## 🔧 Troubleshooting

### Error: "Class 'App\Console\Commands\ScrapeBpkhFiles' not found"

**Solusi:**
```bash
composer dump-autoload
```

### Error: Permission denied saat save file

**Solusi:**
```bash
# Windows (run as Administrator)
icacls storage /grant Users:F /t

# Linux/Mac
chmod -R 775 storage
```

### File tidak terunduh

**Cek:**
1. Koneksi internet
2. URL masih valid (coba buka di browser)
3. Token access masih aktif

## 📊 Monitoring Progress

Script akan menampilkan:
- ✓ = Berhasil download
- ✗ = Gagal download
- Ukuran file yang diunduh
- Total summary di akhir

## 🎯 Tips

1. **Test dulu dengan 1 BPKH** sebelum scrape semua:
   ```bash
   php artisan bpkh:scrape-files --id=1
   ```

2. **Jalankan saat koneksi internet stabil** karena akan download banyak file

3. **Cek storage space** sebelum scrape semua data

4. **Backup data** jika perlu sebelum scrape ulang

## 📁 Akses File dari Code

```php
use Illuminate\Support\Facades\Storage;

// List semua file BPKH Wilayah XXII Kendari
$files = Storage::files('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari');

// Download file
return Storage::download('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari/file.pdf');

// Get file path (absolute path)
$path = Storage::path('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari/file.pdf');

// Check if file exists
$exists = Storage::exists('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari/file.pdf');
```

## 📚 Dokumentasi Lengkap

Lihat: `docs/BPKH_FILE_SCRAPER.md`

---

**Dibuat:** 2 Desember 2024  
**Command:** `php artisan bpkh:scrape-files`
