# CMS Pemenang SIGAP Award 2025

## Overview
CMS untuk mengelola data pemenang SIGAP Award 2025 dengan berbagai kategori dan peringkat juara.

## Fitur

### Kategori Pemenang
1. **Poster Terbaik** - Pemenang poster terbaik
2. **Poster Favorit** - Pemenang poster favorit
3. **Pengelola IGT Terbaik** - Pengelola Informasi Geospasial Tematik terbaik
4. **Inovasi BPKH Terbaik** - Inovasi terbaik dari BPKH
5. **Inovasi Produsen DG Terbaik** - Inovasi terbaik dari Produsen Data Geospasial

### Tipe Peserta
- **BPKH** - Balai Pemantapan Kawasan Hutan
- **Produsen** - Produsen Data Geospasial

### Peringkat Juara
- **Juara 1** 🏆
- **Juara 2** 🥈
- **Juara 3** 🥉
- **Juara Harapan** ⭐

## Struktur Database

### Tabel: `pemenang_sigap`

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| kategori | enum | Kategori pemenang |
| tipe_peserta | enum | BPKH atau Produsen |
| nama_pemenang | string | Nama BPKH/Produsen pemenang |
| juara | enum | Peringkat juara |
| deskripsi | text | Deskripsi pencapaian |
| foto_path | string | Path foto pemenang |
| urutan | integer | Urutan tampil |
| is_active | boolean | Status aktif |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## File Structure

```
app/
├── Http/Controllers/dashboard/
│   └── PemenangSigapController.php
├── Models/
│   └── PemenangSigap.php
database/
├── migrations/
│   └── 2025_01_20_000001_create_pemenang_sigap_table.php
resources/views/dashboard/pages/cms/pemenang-sigap/
├── index.blade.php
├── modals.blade.php
└── scripts.blade.php
routes/
└── web.php (updated)
```

## Installation

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Seed Data (Optional)
Jika belum ada data BPKH dan Produsen:
```bash
php artisan db:seed --class=BpkhListSeeder
php artisan db:seed --class=ProdusenListSeeder
```

### 3. Create Storage Link (if not exists)
```bash
php artisan storage:link
```

## Usage

### Akses CMS
1. Login sebagai Superadmin
2. Navigasi: **Dashboard > Pemenang SIGAP AWARD 2025 > Input Pemenang**
3. URL: `/cms/pemenang-sigap`

### Tambah Pemenang
1. Klik tombol **"Tambah Pemenang"**
2. Pilih **Kategori** pemenang
3. Pilih **Tipe Peserta** (BPKH/Produsen)
4. Pilih **Nama Pemenang** dari dropdown (auto-load berdasarkan tipe)
5. Pilih **Juara** (Juara 1/2/3/Harapan)
6. Isi **Urutan Tampil** (untuk sorting)
7. Isi **Deskripsi** (optional)
8. Upload **Foto** (optional, max 2MB)
9. Set **Status Aktif**
10. Klik **"Simpan"**

### Edit Pemenang
1. Klik tombol **Edit** (icon pensil) pada data yang ingin diubah
2. Update data yang diperlukan
3. Klik **"Update"**

### Hapus Pemenang
1. Klik tombol **Delete** (icon trash) pada data yang ingin dihapus
2. Konfirmasi penghapusan
3. Data akan dihapus beserta fotonya (jika ada)

## API Endpoints

### CMS Routes (Superadmin Only)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/cms/pemenang-sigap` | List semua pemenang |
| POST | `/cms/pemenang-sigap` | Tambah pemenang baru |
| PUT | `/cms/pemenang-sigap/{id}` | Update pemenang |
| DELETE | `/cms/pemenang-sigap/{id}` | Hapus pemenang |
| GET | `/cms/pemenang-sigap/peserta-list` | Get list BPKH/Produsen |

## Model Methods

### Static Methods
```php
PemenangSigap::getKategoriOptions()      // Get kategori options
PemenangSigap::getTipePesertaOptions()   // Get tipe peserta options
PemenangSigap::getJuaraOptions()         // Get juara options
```

### Scopes
```php
PemenangSigap::byKategori('poster_terbaik')  // Filter by kategori
PemenangSigap::byTipePeserta('bpkh')         // Filter by tipe
PemenangSigap::active()                       // Only active
PemenangSigap::ordered()                      // Order by urutan
```

### Accessors
```php
$pemenang->kategori_label        // Get kategori label
$pemenang->tipe_peserta_label    // Get tipe peserta label
$pemenang->juara_label           // Get juara label
```

## Features

### ✅ Dynamic Select2
- Nama pemenang auto-load berdasarkan tipe peserta
- Search functionality untuk kemudahan pencarian

### ✅ Image Upload
- Support format: JPG, PNG
- Max size: 2MB
- Auto preview sebelum upload
- Auto delete old image saat update

### ✅ Validation
- Required fields validation
- File type & size validation
- Unique constraint handling

### ✅ AJAX Operations
- No page reload untuk tambah/edit/hapus
- Real-time feedback dengan SweetAlert2
- Loading states pada buttons

### ✅ Responsive Design
- Mobile-friendly interface
- Bootstrap 5 components
- Modern UI/UX

## Security

- ✅ CSRF Protection
- ✅ Superadmin only access (`@can('see-admin-menus')`)
- ✅ File upload validation
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade templating)

## Troubleshooting

### Issue: Nama pemenang tidak muncul
**Solution**: Pastikan data BPKH/Produsen sudah di-seed
```bash
php artisan db:seed --class=BpkhListSeeder
php artisan db:seed --class=ProdusenListSeeder
```

### Issue: Foto tidak muncul
**Solution**: Buat storage link
```bash
php artisan storage:link
```

### Issue: Error 500 saat upload
**Solution**: Check folder permissions
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## Future Enhancements

- [ ] Export data pemenang ke PDF/Excel
- [ ] Bulk upload pemenang
- [ ] History/audit log
- [ ] Email notification ke pemenang
- [ ] Public page untuk display pemenang
- [ ] Certificate generator

## Notes

- Pastikan folder `storage/app/public/pemenang-sigap` writable
- Foto disimpan di `storage/app/public/pemenang-sigap/`
- Accessible via `/storage/pemenang-sigap/`
- Data BPKH dan Produsen harus sudah ada di database

### Database Column Names

**Tabel BPKH List (`bpkh_list`)**:
- Kolom nama: `nama_wilayah` (bukan `nama_bpkh`)
- Contoh: "BPKH Wilayah VII Makassar"

**Tabel Produsen List (`produsen_list`)**:
- Kolom nama: `nama_unit` (bukan `nama_produsen`)
- Contoh: "Pusat Pengembangan Hutan Berkelanjutan"

---
**Created**: 2025-01-20  
**Version**: 1.0  
**Author**: Development Team
