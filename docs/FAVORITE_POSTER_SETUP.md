# Setup Fitur Penilaian Poster Favorit

## Deskripsi
Fitur ini memungkinkan input penilaian poster favorit dengan sistem counting sederhana (input angka) untuk BPKH dan Produsen DG yang digabungkan dalam satu tabel.

## File yang Dibuat

### 1. Database
- **Migration**: `database/migrations/2025_01_20_000004_create_favorite_poster_votes_table.php`
- **Model**: `app/Models/FavoritePosterVote.php`
- **Seeder**: `database/seeders/FavoritePosterVoteSeeder.php`

### 2. Controller
- **Controller**: `app/Http/Controllers/dashboard/FavoritePosterController.php`

### 3. Views
- `resources/views/dashboard/pages/exhibition/favorite/index.blade.php` - Halaman utama
- `resources/views/dashboard/pages/exhibition/favorite/edit.blade.php` - Form input individual
- `resources/views/dashboard/pages/exhibition/favorite/bulk_edit.blade.php` - Form input kolektif
- `resources/views/dashboard/pages/exhibition/favorite/export_pdf.blade.php` - Template PDF export

### 4. Routes & Navigation
- Routes ditambahkan di `routes/web.php`
- Navigation menu diupdate di `resources/views/dashboard/layouts/navigation.blade.php`

## Cara Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Jalankan Seeder
Seeder akan mengambil data dari tabel `bpkh_forms` dan `produsen_forms` untuk membuat data awal:
```bash
php artisan db:seed --class=FavoritePosterVoteSeeder
```

### 3. Akses Fitur
Setelah migration dan seeder berhasil, akses fitur melalui:
- URL: `/dashboard/favorite-poster`
- Menu: **Penilaian Poster Favorit > Poster Favorit**

## Fitur yang Tersedia

### 1. Halaman Index
- Menampilkan semua peserta (BPKH & Produsen DG) dalam satu tabel
- Filter berdasarkan kategori (BPKH/Produsen)
- Pencarian berdasarkan nama dan respondent ID
- Export ke Excel dan PDF
- Input kolektif untuk beberapa peserta sekaligus

### 2. Input Penilaian
- **Individual**: Input vote per peserta
- **Kolektif**: Input vote untuk beberapa peserta sekaligus
- Field yang tersedia:
  - Jumlah Vote (required, integer, min: 0)
  - Catatan (optional, max: 1000 karakter)

### 3. Export Data
- Export semua data ke Excel
- Export semua data ke PDF

## Struktur Database

### Tabel: favorite_poster_votes
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| respondent_id | string | ID responden |
| participant_name | string | Nama peserta |
| participant_type | string | 'bpkh' atau 'produsen' |
| petugas | string | Nama petugas |
| vote_count | integer | Jumlah vote (default: 0) |
| notes | text | Catatan tambahan |
| created_at | timestamp | |
| updated_at | timestamp | |

## Catatan Penting

1. **Data Awal**: Seeder akan mengambil data dari `bpkh_forms` dan `produsen_forms`. Pastikan kedua tabel sudah terisi sebelum menjalankan seeder.

2. **Permission**: Fitur ini menggunakan middleware `prevent.admin.view` untuk operasi input/edit, sehingga user dengan role `admin-view` hanya bisa melihat data.

3. **Re-seeding**: Jika ingin mengulang seeder (misalnya ada data baru di bpkh_forms/produsen_forms), jalankan kembali seeder. Data lama akan dihapus (truncate) dan diganti dengan data baru.

4. **Update Data Peserta**: Jika ada perubahan nama peserta di tabel bpkh_forms atau produsen_forms, Anda perlu update manual di tabel favorite_poster_votes atau jalankan ulang seeder.

## Troubleshooting

### Error: Table not found
Pastikan migration sudah dijalankan dengan benar:
```bash
php artisan migrate:status
```

### Error: Seeder gagal
Pastikan tabel `bpkh_forms` dan `produsen_forms` sudah ada dan terisi:
```bash
php artisan tinker
>>> \App\Models\BpkhForm::count()
>>> \App\Models\ProdusenForm::count()
```

### Error: Route not found
Clear cache route:
```bash
php artisan route:clear
php artisan route:cache
```
