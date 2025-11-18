# Card Box CMS - Quick Start

## Setup (3 Langkah)

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Seed Data (Optional)
```bash
php artisan db:seed --class=CardBoxSeeder
```

### 3. Akses CMS
Login sebagai Superadmin, lalu buka:
```
/cms/card-box
```

## Fitur Utama

✅ **CRUD** - Tambah, Edit, Hapus card box  
✅ **Drag & Drop** - Ubah urutan tampilan  
✅ **Link URL** - Tombol membuka link eksternal  
✅ **Modal Pop-up** - Tombol membuka modal dengan konten custom  
✅ **Toggle Status** - Aktif/Non-aktif untuk tampilan di landing  

## Cara Pakai

### Tambah Card Box
1. Klik "Tambah Card Box"
2. Isi:
   - Judul (h3)
   - Deskripsi (p)
   - Teks Tombol
   - Pilih: Link URL atau Modal
   - Centang "Aktif" untuk tampil di landing
3. Simpan

### Edit/Hapus
- Klik icon **pensil** untuk edit
- Klik icon **tempat sampah** untuk hapus

### Ubah Urutan
- **Drag & drop** baris tabel menggunakan icon drag

## Hasil di Landing Page
Card box yang **aktif** akan muncul di halaman landing sesuai urutan.

---

📖 **Dokumentasi Lengkap**: Lihat `CARD_BOX_CMS_GUIDE.md`
