# Halaman Hasil Poster Favorit

## Deskripsi
Halaman ini menampilkan hasil penilaian poster favorit dengan ranking berdasarkan jumlah vote tertinggi. Data diambil dari tabel `favorite_poster_votes` yang menggabungkan BPKH dan Produsen DG.

## File yang Dibuat/Dimodifikasi

### 1. Controller
- **File**: `app/Http/Controllers/dashboard/DashboardController.php`
- **Method Baru**:
  - `favoritePosterResults()` - Menampilkan halaman hasil
  - `exportFavoritePoster()` - Export ke Excel/PDF

### 2. Views
- **File Utama**: `resources/views/dashboard/pages/hasil/favorite_poster_index.blade.php`
- **Template PDF**: `resources/views/dashboard/pages/hasil/exports/favorite_poster_pdf.blade.php`

### 3. Routes
- **GET** `/dashboard/hasil-poster-favorit` - Halaman hasil
- **GET** `/dashboard/hasil-poster-favorit/export` - Export data

### 4. Navigation
- Menu "Hasil Poster Favorit" ditambahkan di sidebar dengan icon heart

## Fitur

### 1. Tampilan Hasil
- **Ranking**: Top 3 mendapat badge emas, perak, perunggu
- **Sorting**: Otomatis diurutkan berdasarkan jumlah vote (tertinggi ke terendah)
- **Data Ditampilkan**:
  - Rank/Peringkat
  - Kategori (BPKH/Produsen DG)
  - Nama Peserta
  - Petugas
  - Jumlah Vote (highlighted)
  - Catatan

### 2. Export Data
- **Excel**: Export semua data ke format .xlsx
- **PDF**: Export semua data ke format .pdf

### 3. DataTables Features
- Search/Filter
- Pagination
- Responsive design
- Sorting per kolom

## Cara Menggunakan

### Akses Halaman
1. Login ke dashboard
2. Klik menu **"Hasil Poster Favorit"** di sidebar
3. Atau akses langsung: `/dashboard/hasil-poster-favorit`

### Export Data
1. Klik tombol **"Export"** di pojok kanan atas
2. Pilih format: **Excel** atau **PDF**
3. File akan otomatis terdownload

## Struktur Data

Data diambil dari tabel `favorite_poster_votes`:
- Diurutkan berdasarkan `vote_count` DESC
- Jika vote sama, diurutkan berdasarkan `participant_name` ASC
- Ranking dihitung otomatis berdasarkan urutan

## Styling

### Badge Ranking
- **Rank 1**: Gradient emas (#FFD700 → #FFA500)
- **Rank 2**: Gradient perak (#C0C0C0 → #808080)
- **Rank 3**: Gradient perunggu (#CD7F32 → #8B4513)
- **Rank 4+**: Badge abu-abu

### Kategori Badge
- **BPKH**: Badge biru (bg-info)
- **Produsen DG**: Badge hijau (bg-primary)

### Vote Count
- Font size: 20px
- Font weight: Bold
- Color: Hijau (#34c38f)

## Integrasi dengan Sistem

Halaman ini terintegrasi dengan:
1. **Input Penilaian**: Data diambil dari halaman `/dashboard/favorite-poster`
2. **Navigation**: Menu sidebar otomatis highlight saat aktif
3. **Export System**: Menggunakan library Maatwebsite Excel dan DomPDF

## Catatan Penting

1. **Data Real-time**: Halaman ini menampilkan data terkini dari database
2. **No Caching**: Setiap akses akan query database langsung
3. **Permission**: Semua user yang login bisa akses halaman ini
4. **Responsive**: Tampilan otomatis menyesuaikan dengan ukuran layar

## Troubleshooting

### Halaman kosong/tidak ada data
- Pastikan sudah ada data di tabel `favorite_poster_votes`
- Jalankan seeder jika belum: `php artisan db:seed --class=FavoritePosterVoteSeeder`

### Export gagal
- Pastikan library Excel dan PDF sudah terinstall
- Check permission folder storage

### Menu tidak muncul
- Clear cache: `php artisan route:clear`
- Refresh browser dengan Ctrl+F5
