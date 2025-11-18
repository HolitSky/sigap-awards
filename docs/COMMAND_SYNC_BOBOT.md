# Command Sync Nilai Bobot

## Deskripsi
Command untuk melakukan sinkronisasi dan perhitungan ulang `nilai_bobot_total` pada form yang sudah memiliki nilai `total_score` namun belum memiliki nilai bobot.

## Latar Belakang
Fitur perhitungan bobot ditambahkan setelah beberapa form sudah dinilai. Akibatnya, form-form yang sudah dinilai sebelumnya tidak memiliki nilai `nilai_bobot_total`. Command ini dibuat untuk mengisi nilai tersebut secara otomatis tanpa perlu menilai ulang secara manual.

## Rumus Perhitungan
```php
nilai_bobot_total = (total_score × bobot) ÷ 100
```

Dimana:
- `total_score` = Nilai final yang sudah diinput oleh juri
- `bobot` = Bobot penilaian (default: 45)
- `nilai_bobot_total` = Hasil akhir perhitungan bobot

## Command yang Tersedia

### 1. Sync BPKH Forms
```bash
php artisan bpkh:sync-bobot
```
Melakukan sinkronisasi nilai bobot untuk form BPKH.

### 2. Sync Produsen Forms
```bash
php artisan produsen:sync-bobot
```
Melakukan sinkronisasi nilai bobot untuk form Produsen.

### 3. Sync Semua Forms (BPKH & Produsen)
```bash
php artisan sync:all-bobot
```
Menjalankan sinkronisasi untuk BPKH dan Produsen sekaligus.

## Output Command

Command akan menampilkan:
- ✅ Jumlah form yang berhasil dihitung nilai bobotnya
- ❌ Jumlah form yang gagal (jika ada)
- 📝 Jumlah form yang sudah memiliki nilai bobot (tidak perlu diupdate)

### Contoh Output:
```
🔄 Memulai sinkronisasi nilai bobot untuk BPKH Forms...

📋 Ditemukan 25 form yang perlu dihitung nilai bobotnya.

 25/25 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📊 RINGKASAN SINKRONISASI
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
✅ Berhasil dihitung: 25 form
📝 Sudah memiliki nilai bobot: 15 form
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

🎉 Sinkronisasi nilai bobot BPKH selesai!
```

## Kriteria Form yang Akan Diupdate

Command hanya akan mengupdate form yang memenuhi kriteria:
1. ✅ Memiliki `total_score` (tidak null)
2. ❌ `nilai_bobot_total` kosong (null)

Form yang sudah memiliki `nilai_bobot_total` **tidak akan diupdate** dan hanya akan dihitung dalam output statistik.

## Catatan Penting

- ⚠️ Command ini **TIDAK** akan membuat record di tabel `record_user_assesments`
- ⚠️ Command ini hanya melakukan update nilai `nilai_bobot_total` pada tabel forms
- ✅ Safe untuk dijalankan berulang kali (idempotent)
- ✅ Menggunakan progress bar untuk tracking progress
- ✅ Menampilkan error detail jika ada form yang gagal diupdate

## Kapan Menggunakan Command Ini?

1. Setelah menjalankan migration `add_bobot_columns_to_bpkh_forms_table` dan `add_bobot_columns_to_produsen_forms_table`
2. Ketika ada data lama yang sudah dinilai sebelum fitur bobot ditambahkan
3. Setelah melakukan import data dari sistem lama
4. Ketika terjadi bug/error pada perhitungan bobot dan perlu recalculate

## Troubleshooting

### "Tidak ada data yang perlu diupdate"
Ini berarti semua form yang memiliki `total_score` sudah memiliki `nilai_bobot_total`. Tidak ada masalah.

### Error saat update
Jika ada error, command akan menampilkan detail error dan melanjutkan ke form berikutnya tanpa berhenti.

## File Terkait

- **Commands:**
  - `app/Console/Commands/BpkhSyncBobot.php`
  - `app/Console/Commands/ProdusenSyncBobot.php`
  - `app/Console/Commands/SyncAllBobot.php`

- **Models:**
  - `app/Models/BpkhForm.php`
  - `app/Models/ProdusenForm.php`

- **Migrations:**
  - `database/migrations/2025_10_18_103900_add_bobot_columns_to_bpkh_forms_table.php`
  - `database/migrations/2025_10_18_103901_add_bobot_columns_to_produsen_forms_table.php`
