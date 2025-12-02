# BPKH File Scraper - Dokumentasi

## Deskripsi

Script Laravel Artisan Command untuk mengunduh file-file lampiran dari meta data form BPKH. Script ini akan:

1. Membaca data meta dari tabel `bpkh_forms`
2. Mengekstrak semua URL yang ada di field "Lampiran"
3. Mengunduh file dari URL tersebut
4. Menyimpan dengan nama yang sesuai: `[Judul]_[Nama_File_Asli]`
5. Mengorganisir file ke folder per BPKH: `scrapping_script/bpkh_form/bpkh_[nama_bpkh]/`

## Instalasi

Command sudah tersedia setelah file dibuat di:
```
app/Console/Commands/ScrapeBpkhFiles.php
```

## Cara Penggunaan

### 1. Scrape Semua BPKH Forms

```bash
php artisan bpkh:scrape-files --all
```

Perintah ini akan memproses semua data BPKH yang ada di database.

### 2. Scrape BPKH Tertentu (by ID)

```bash
php artisan bpkh:scrape-files --id=5
```

Perintah ini hanya akan memproses BPKH dengan ID tertentu.

### 3. Scrape Multiple BPKH (satu per satu)

```bash
php artisan bpkh:scrape-files --id=1
php artisan bpkh:scrape-files --id=2
php artisan bpkh:scrape-files --id=3
```

## Struktur Folder Output

File akan disimpan di `storage/app/private/` dengan struktur:

```
storage/app/private/
└── scrapping_script/
    └── bpkh_form/
        ├── bpkh_wilayah_xxii_kendari/
        │   ├── SK_Terbaru_SK.36_Penetapan-Tim-Pengelola-Jaringan-Informasi-Geospasial_2025.pdf
        │   ├── bukti_dokumen_laporan_BPKH_LAPORAN-PENYEBARAN-IGT-TAHUN-2024.pdf
        │   └── ...
        ├── bpkh_wilayah_iv/
        │   ├── SK_Terbaru_SK-TIM-PELAKSANAAN-JARINGAN-LINGKUP-BPKHTL.pdf
        │   └── ...
        └── bpkh_wilayah_vi/
            └── ...
```

## Format Nama File

Script akan membuat nama file dengan format:
```
[Judul_Lampiran]_[Nama_File_Asli]
```

Contoh:
- Input: 
  - Judul: "Lampiran SK Terbaru"
  - URL: `https://storage.tally.so/private/SK-Pengelola-TAPAL21.pdf?id=xxx&token=yyy`
- Output: 
  - `Lampiran_SK_Terbaru_SK-Pengelola-TAPAL21.pdf`

## Fitur

### ✅ Yang Dilakukan Script:

1. **Ekstraksi URL Otomatis**
   - Mendukung single URL atau multiple URL dalam satu field
   - Mendukung format array atau string

2. **Sanitasi Nama Folder**
   - Nama BPKH dibersihkan dari karakter khusus
   - Dikonversi ke format: `bpkh_[nama]`

3. **Sanitasi Nama File**
   - Menghapus prefix "Lampiran" dari judul
   - Menggabungkan judul dengan nama file asli
   - Membersihkan karakter khusus

4. **Download dengan Timeout**
   - Timeout 120 detik untuk file besar
   - Error handling untuk koneksi gagal

5. **Progress Reporting**
   - Menampilkan progress per file
   - Menampilkan ukuran file yang diunduh
   - Summary total downloaded/failed

### 🔍 Field yang Diproses:

Script hanya memproses field yang:
- Dimulai dengan kata "Lampiran" (case-insensitive)
- Mengandung URL valid (http:// atau https://)

Contoh field yang akan diproses:
- "Lampiran SK Terbaru"
- "Lampiran Surat Izin"
- "Lampiran Dokumen Pendukung"
- "lampiran foto kegiatan"

## Output Console

Contoh output saat menjalankan command:

```
🚀 Starting BPKH Files Scraping...
Found 3 BPKH form(s) to process.

📋 Processing: BPKH Palangkaraya (ID: 1)
  📎 Lampiran SK Terbaru
    ⬇️  Downloading: Lampiran_SK_Terbaru_SK-Pengelola-TAPAL21.pdf
    ✓ Saved: scrapping_script/bpkh_form/bpkh_palangkaraya/Lampiran_SK_Terbaru_SK-Pengelola-TAPAL21.pdf (2.45 MB)
  📎 Lampiran Surat Izin
    ⬇️  Downloading: Lampiran_Surat_Izin_Surat-Izin-Operasional.pdf
    ✓ Saved: scrapping_script/bpkh_form/bpkh_palangkaraya/Lampiran_Surat_Izin_Surat-Izin-Operasional.pdf (1.23 MB)

📋 Processing: BPKH Pontianak (ID: 2)
  📎 Lampiran Dokumen A
    ⬇️  Downloading: Lampiran_Dokumen_A_dokumen-a.pdf
    ✓ Saved: scrapping_script/bpkh_form/bpkh_pontianak/Lampiran_Dokumen_A_dokumen-a.pdf (856.12 KB)

✅ Scraping completed!
📥 Total files downloaded: 3
```

## Error Handling

Script menangani berbagai error:

1. **HTTP Error**: Jika server mengembalikan status error (4xx, 5xx)
2. **Timeout**: Jika download melebihi 120 detik
3. **Network Error**: Jika koneksi terputus
4. **File System Error**: Jika gagal menyimpan file

Semua error akan ditampilkan di console dengan simbol ✗ dan pesan error.

## Lokasi File

File disimpan di Laravel storage (private disk):
```
storage/app/private/scrapping_script/bpkh_form/
```

Untuk mengakses file:
```php
use Illuminate\Support\Facades\Storage;

// Get file path (absolute path)
$path = Storage::path('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari/file.pdf');

// Check if exists
$exists = Storage::exists('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari/file.pdf');

// Download file
return Storage::download('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari/file.pdf');

// List all files in a BPKH folder
$files = Storage::files('scrapping_script/bpkh_form/bpkh_wilayah_xxii_kendari');
```

## Troubleshooting

### Problem: File tidak terunduh

**Solusi:**
1. Cek koneksi internet
2. Cek apakah URL masih valid
3. Cek permission folder `storage/app/`

### Problem: Nama file terlalu panjang

**Solusi:**
Script otomatis membatasi panjang judul maksimal 50 karakter.

### Problem: Timeout saat download

**Solusi:**
Timeout sudah diset 120 detik. Jika masih timeout, bisa diubah di:
```php
Http::timeout(120)->get($url);
```

## Requirements

- PHP >= 8.1
- Laravel >= 10.x
- Extension: `php-curl` (untuk HTTP requests)
- Storage permission: writable

## Important Notes

### SSL Certificate Verification

Script ini menggunakan `verify => false` untuk menonaktifkan verifikasi SSL certificate. Ini diperlukan untuk development environment di Windows yang sering mengalami masalah SSL certificate.

**⚠️ WARNING:** Untuk production environment, sebaiknya:
1. Install SSL certificate yang valid
2. Atau gunakan `verify => true` dengan certificate bundle yang benar
3. Edit file `app/Console/Commands/ScrapeBpkhFiles.php` baris 121-123

```php
// Production (recommended)
$response = Http::withOptions([
    'verify' => true, // Enable SSL verification
])->timeout(120)->get($url);
```

## Notes

- Script tidak akan overwrite file yang sudah ada
- Jika ingin re-download, hapus dulu file lama atau ubah nama folder
- Script mendukung berbagai format file: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, dll
- URL dari Tally.so dengan access token akan tetap berfungsi selama token valid

## Contoh Data Meta

Format data meta yang didukung:

### Format 1: Ordered Array
```json
[
  {
    "key": "Lampiran SK Terbaru",
    "value": "https://storage.tally.so/private/SK-Pengelola.pdf?id=xxx&token=yyy"
  },
  {
    "key": "Jawaban soal 1.1",
    "value": "Jawaban text..."
  }
]
```

### Format 2: Associative Array
```json
{
  "Lampiran SK Terbaru": "https://storage.tally.so/private/SK-Pengelola.pdf?id=xxx&token=yyy",
  "Jawaban soal 1.1": "Jawaban text..."
}
```

### Format 3: Multiple URLs in One Field
```json
{
  "Lampiran Dokumen": [
    "https://example.com/file1.pdf",
    "https://example.com/file2.pdf"
  ]
}
```

Semua format di atas akan diproses dengan benar oleh script.
