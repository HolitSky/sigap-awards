# Quick Start - Fitur Penilaian Presentasi

## Setup Awal (Jalankan Sekali)

```bash
# 1. Jalankan migration
php artisan migrate

# 2. Sync data dari form yang sudah scored
php artisan sync:bpkh-presentation
php artisan sync:produsen-presentation
```

## URL Akses

### BPKH
- Daftar: `http://your-domain.com/presentation-bpkh`
- Detail: `http://your-domain.com/presentation-bpkh/{respondent_id}`
- Nilai: `http://your-domain.com/presentation-bpkh/{respondent_id}/nilai`

### Produsen DG
- Daftar: `http://your-domain.com/presentation-produsen-dg`
- Detail: `http://your-domain.com/presentation-produsen-dg/{respondent_id}`
- Nilai: `http://your-domain.com/presentation-produsen-dg/{respondent_id}/nilai`

## Alur Penggunaan

1. **Sync Data** (Admin/Superadmin)
   ```bash
   php artisan sync:bpkh-presentation
   php artisan sync:produsen-presentation
   ```

2. **Akses Menu Penilaian** (Semua Juri)
   - Login ke dashboard
   - Buka menu Penilaian Presentasi BPKH atau Produsen

3. **Nilai Presentasi**
   - Klik "Nilai Presentasi" pada peserta
   - Isi 6 aspek penilaian (nilai 1-100)
   - Tulis catatan juri
   - Pilih rekomendasi
   - Simpan

4. **Lihat Hasil**
   - Klik "Detail" untuk melihat nilai final
   - Nilai otomatis di-update setiap ada juri baru menilai

## Aspek Penilaian (Total 100%)

1. Substansi & Capaian Kinerja (30%)
2. Implementasi Strategi & Dampak (20%)
3. Kedalaman Analisis (15%)
4. Kejelasan & Alur Penyampaian (10%)
5. Kemampuan Menjawab Pertanyaan Juri (15%)
6. Kreativitas & Daya Tarik Presentasi (10%)

## Rekomendasi Pilihan

- Layak sebagai pemenang kategori
- Layak sebagai nominasi utama
- Perlu pembinaan lebih lanjut

## Kategori Skor Otomatis

| Nilai | Kategori |
|-------|----------|
| 81-100 | Sangat Baik |
| 61-80 | Baik |
| 41-60 | Cukup |
| 21-40 | Kurang |
| 1-20 | Sangat Kurang |

## Troubleshooting

**Q: Data tidak muncul di halaman presentasi?**
A: Pastikan sudah menjalankan command sync dan data form memiliki status_nilai = 'scored'

**Q: Tidak bisa menilai?**
A: Pastikan sudah login dan memiliki akses ke menu presentasi

**Q: Nilai final tidak berubah?**
A: Nilai final otomatis dihitung saat menyimpan penilaian. Refresh halaman detail.

## Command Sync

Jalankan command ini setiap kali ada peserta baru yang lolos ke tahap presentasi:

```bash
# Update semua data BPKH
php artisan sync:bpkh-presentation

# Update semua data Produsen
php artisan sync:produsen-presentation
```

Command ini aman dijalankan berulang kali (akan update data yang sudah ada).
