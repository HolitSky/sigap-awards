# Quick Start - CMS Pemenang SIGAP Award 2025

## 🚀 Setup Cepat

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Seed Data BPKH & Produsen (Jika Belum Ada)
```bash
php artisan db:seed --class=BpkhListSeeder
php artisan db:seed --class=ProdusenListSeeder
```

### 3. Buat Storage Link (Jika Belum Ada)
```bash
php artisan storage:link
```

### 4. Clear Cache
```bash
php artisan optimize:clear
```

## 📍 Akses CMS

**URL**: `/cms/pemenang-sigap`

**Menu**: Dashboard → Pemenang SIGAP AWARD 2025 → Input Pemenang

**Permission**: Superadmin only (`@can('see-admin-menus')`)

## 📝 Cara Penggunaan

### Tambah Pemenang Baru

1. Klik tombol **"Tambah Pemenang"**
2. Isi form:
   - **Kategori**: Pilih kategori (Poster Terbaik, Poster Favorit, dll)
   - **Tipe Peserta**: BPKH atau Produsen
   - **Nama Pemenang**: Pilih dari dropdown (auto-load)
   - **Juara**: Juara 1/2/3/Harapan
   - **Urutan**: Angka untuk sorting (default: 0)
   - **Deskripsi**: Opsional
   - **Foto**: Opsional (JPG/PNG, max 2MB)
   - **Status**: Centang untuk aktif
3. Klik **"Simpan"**

### Edit Pemenang

1. Klik icon **pensil** (🖊️) pada baris data
2. Update data yang diperlukan
3. Klik **"Update"**

### Hapus Pemenang

1. Klik icon **trash** (🗑️) pada baris data
2. Konfirmasi penghapusan
3. Data dan foto akan terhapus

## 🎯 Kategori Pemenang

| Kategori | Deskripsi |
|----------|-----------|
| **Poster Terbaik** | Pemenang poster terbaik |
| **Poster Favorit** | Pemenang poster favorit |
| **Pengelola IGT Terbaik** | Pengelola IGT terbaik |
| **Inovasi BPKH Terbaik** | Inovasi terbaik BPKH |
| **Inovasi Produsen Terbaik** | Inovasi terbaik Produsen DG |

## 🏆 Peringkat Juara

- 🥇 **Juara 1**
- 🥈 **Juara 2**
- 🥉 **Juara 3**
- ⭐ **Juara Harapan**

## 💡 Tips

### Select2 Auto-Load
Nama pemenang akan otomatis dimuat setelah memilih **Tipe Peserta**. Jika tidak muncul, pastikan data BPKH/Produsen sudah di-seed.

### Upload Foto
- Format: JPG, PNG
- Max size: 2MB
- Preview otomatis sebelum upload
- Foto lama otomatis terhapus saat update

### Urutan Tampil
Gunakan field **Urutan** untuk mengatur urutan tampil di halaman publik. Semakin kecil angka, semakin atas posisinya.

## ⚠️ Troubleshooting

### Nama Pemenang Tidak Muncul
```bash
# Seed data BPKH dan Produsen
php artisan db:seed --class=BpkhListSeeder
php artisan db:seed --class=ProdusenListSeeder
```

### Foto Tidak Tampil
```bash
# Buat storage link
php artisan storage:link
```

### Error 500 Saat Upload
```bash
# Set permissions (Linux/Mac)
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Windows: Pastikan folder writable
```

## 📁 File Locations

### Foto Pemenang
- **Storage**: `storage/app/public/pemenang-sigap/`
- **Public URL**: `/storage/pemenang-sigap/filename.jpg`

### Views
- **Index**: `resources/views/dashboard/pages/cms/pemenang-sigap/index.blade.php`
- **Modals**: `resources/views/dashboard/pages/cms/pemenang-sigap/modals.blade.php`
- **Scripts**: `resources/views/dashboard/pages/cms/pemenang-sigap/scripts.blade.php`

### Backend
- **Controller**: `app/Http/Controllers/dashboard/PemenangSigapController.php`
- **Model**: `app/Models/PemenangSigap.php`
- **Migration**: `database/migrations/2025_01_20_000001_create_pemenang_sigap_table.php`

## 🔗 Routes

| Method | Route | Action |
|--------|-------|--------|
| GET | `/cms/pemenang-sigap` | Index page |
| POST | `/cms/pemenang-sigap` | Store new |
| PUT | `/cms/pemenang-sigap/{id}` | Update |
| DELETE | `/cms/pemenang-sigap/{id}` | Delete |
| GET | `/cms/pemenang-sigap/peserta-list` | Get BPKH/Produsen list |

## ✅ Checklist Setup

- [ ] Migration dijalankan
- [ ] Data BPKH & Produsen sudah di-seed
- [ ] Storage link sudah dibuat
- [ ] Cache sudah di-clear
- [ ] Bisa akses `/cms/pemenang-sigap`
- [ ] Dropdown nama pemenang berfungsi
- [ ] Upload foto berfungsi

---
**Ready to use!** 🎉
