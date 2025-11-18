# Panduan Fitur Penilaian Presentasi

## Deskripsi
Fitur penilaian presentasi memungkinkan juri untuk menilai presentasi dari peserta BPKH dan Produsen DG yang sudah lolos tahap penilaian form (status_nilai = 'scored').

## Struktur Database

### 1. Tabel `bpkh_presentasi_assesment`
- **respondent_id**: ID unik responden
- **nama_bpkh**: Nama BPKH
- **petugas_bpkh**: Nama petugas
- **aspek_penilaian**: JSON metadata untuk menyimpan aspek penilaian dari semua juri
- **penilaian_per_juri**: JSON array berisi penilaian dari setiap juri
- **nilai_final**: Rata-rata nilai dari semua juri
- **bobot_presentasi**: Bobot presentasi (default 35%)
- **nilai_final_dengan_bobot**: nilai_final × bobot_presentasi / 100
- **kategori_skor**: Kategori otomatis (Sangat Baik, Baik, Cukup, Kurang, Sangat Kurang)
- **deskripsi_skor**: Deskripsi kategori skor

### 2. Tabel `produsen_presentasi_assesment`
Struktur sama dengan tabel BPKH, tetapi untuk data Produsen DG.

### 3. Tabel `record_presentasi_assesment`
Menyimpan riwayat penilaian untuk tracking siapa yang menilai dan kapan.

## Aspek Penilaian

Setiap juri menilai 6 aspek dengan nilai 1-100:

1. **Substansi & Capaian Kinerja** - Bobot 30%
2. **Implementasi Strategi & Dampak** - Bobot 20%
3. **Kedalaman Analisis** - Bobot 15%
4. **Kejelasan & Alur Penyampaian** - Bobot 10%
5. **Kemampuan Menjawab Pertanyaan Juri** - Bobot 15%
6. **Kreativitas & Daya Tarik Presentasi** - Bobot 10%

**Total Bobot: 100%**

## Perhitungan Nilai

### Nilai Akhir Per Juri
```
nilai_akhir_user = (aspek1 × 30% + aspek2 × 20% + aspek3 × 15% + aspek4 × 10% + aspek5 × 15% + aspek6 × 10%)
```

### Nilai Final (Rata-rata dari semua juri)
```
nilai_final = (nilai_juri1 + nilai_juri2 + nilai_juri3) / jumlah_juri
```

Contoh:
- Juri 1: 50
- Juri 2: 60
- Juri 3: 54
- **Nilai Final = (50 + 60 + 54) / 3 = 54.67**

### Nilai Final Dengan Bobot
```
nilai_final_dengan_bobot = (nilai_final × bobot_presentasi) / 100
```

Contoh dengan bobot 35%:
```
nilai_final_dengan_bobot = (54.67 × 35) / 100 = 19.13
```

## Kategori Skor Otomatis

| Nilai | Kategori | Deskripsi |
|-------|----------|-----------|
| 81-100 | Sangat Baik | Melampaui ekspektasi, menunjukkan dampak dan inovasi signifikan |
| 61-80 | Baik | Menunjukkan implementasi kuat dan hasil nyata |
| 41-60 | Cukup | Relevan dan memenuhi sebagian besar indikator |
| 21-40 | Kurang | Implementasi terbatas, belum menunjukkan hasil optimal |
| 1-20 | Sangat Kurang | Minim bukti atau penyajian tidak relevan |

## Cara Penggunaan

### 1. Sinkronisasi Data dari Form

Jalankan command untuk sync data dari form yang sudah scored:

```bash
# Untuk BPKH
php artisan sync:bpkh-presentation

# Untuk Produsen
php artisan sync:produsen-presentation
```

Command ini akan mengambil data dari tabel `bpkh_forms` dan `produsen_forms` yang memiliki `status_nilai = 'scored'` dan memasukkannya ke tabel presentasi.

### 2. Akses Menu Penilaian Presentasi

**BPKH:**
- Index: `/presentation-bpkh`
- Detail: `/presentation-bpkh/{respondentId}`
- Nilai: `/presentation-bpkh/{respondentId}/nilai`

**Produsen DG:**
- Index: `/presentation-produsen-dg`
- Detail: `/presentation-produsen-dg/{respondentId}`
- Nilai: `/presentation-produsen-dg/{respondentId}/nilai`

### 3. Melakukan Penilaian

1. Pilih peserta dari daftar
2. Klik "Nilai Presentasi"
3. Isi nilai untuk setiap aspek (1-100)
4. Tulis catatan juri (opsional)
5. Pilih rekomendasi:
   - Layak sebagai pemenang kategori
   - Layak sebagai nominasi utama
   - Perlu pembinaan lebih lanjut
6. Klik "Simpan Penilaian"

### 4. Melihat Hasil Penilaian

Di halaman detail, Anda dapat melihat:
- Jumlah juri yang sudah menilai
- Nilai final (rata-rata)
- Nilai final dengan bobot
- Kategori skor otomatis
- Daftar penilaian dari setiap juri
- Riwayat penilaian

## Fitur Tambahan

### Multi-Juri
- Sistem mendukung penilaian dari banyak juri
- Setiap juri dapat menilai dan mengupdate penilaiannya sendiri
- Nilai final dihitung otomatis sebagai rata-rata

### Tracking & History
- Semua penilaian dicatat di tabel `record_presentasi_assesment`
- Dapat melihat siapa juri terakhir yang menilai
- Timestamp untuk setiap penilaian

### JSON Metadata
- Aspek penilaian disimpan sebagai JSON untuk efisiensi database
- Mudah di-extend jika ada aspek baru di masa depan
- Tidak perlu banyak kolom di database

## Route Names

### BPKH
- `dashboard.presentation.bpkh.index`
- `dashboard.presentation.bpkh.show`
- `dashboard.presentation.bpkh.edit`
- `dashboard.presentation.bpkh.update`
- `dashboard.presentation.bpkh.history`

### Produsen
- `dashboard.presentation.produsen.index`
- `dashboard.presentation.produsen.show`
- `dashboard.presentation.produsen.edit`
- `dashboard.presentation.produsen.update`
- `dashboard.presentation.produsen.history`

## File-file yang Dibuat/Dimodifikasi

### Migrations
1. `2025_10_19_191424_create_bpkh_presentasi_assesment_table.php`
2. `2025_10_19_191436_create_produsen_presentasi_assesment_table.php`
3. `2025_10_19_192211_create_record_presentasi_assesment_table.php`

### Models
1. `app/Models/BpkhPresentationAssesment.php`
2. `app/Models/ProdusenPresentationAssesment.php`
3. `app/Models/RecordPresentationAssesment.php`

### Commands
1. `app/Console/Commands/SyncBpkhPresentationAssessment.php`
2. `app/Console/Commands/SyncProdusenPresentationAssessment.php`

### Controllers
1. `app/Http/Controllers/dashboard/BpkhPresentationController.php`
2. `app/Http/Controllers/dashboard/ProdusenPresentationController.php`

### Views - BPKH
1. `resources/views/dashboard/pages/presentation/bpkh/index.blade.php`
2. `resources/views/dashboard/pages/presentation/bpkh/show.blade.php`
3. `resources/views/dashboard/pages/presentation/bpkh/score.blade.php`

### Views - Produsen
1. `resources/views/dashboard/pages/presentation/produsen/index.blade.php`
2. `resources/views/dashboard/pages/presentation/produsen/show.blade.php`
3. `resources/views/dashboard/pages/presentation/produsen/score.blade.php`

### Routes
- Updated `routes/web.php` dengan route presentasi

## Langkah Setup

1. **Jalankan Migration**
   ```bash
   php artisan migrate
   ```

2. **Sync Data dari Form**
   ```bash
   php artisan sync:bpkh-presentation
   php artisan sync:produsen-presentation
   ```

3. **Akses Fitur**
   - Login sebagai juri
   - Akses menu penilaian presentasi
   - Mulai menilai peserta

## Catatan Penting

- Pastikan data form sudah memiliki `status_nilai = 'scored'` sebelum di-sync
- Setiap juri dapat menilai dan mengupdate penilaiannya sendiri
- Nilai final akan otomatis diupdate setiap ada penilaian baru
- Bobot presentasi default 35% dan bisa diubah di database jika diperlukan
