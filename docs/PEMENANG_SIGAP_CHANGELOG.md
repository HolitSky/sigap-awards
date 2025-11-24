# Changelog - CMS Pemenang SIGAP Award 2025

## Version 1.0.1 - 2025-01-20

### 🐛 Bug Fixes

**Fixed: Column not found error untuk BPKH dan Produsen list**

**Issue**: 
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'nama_bpkh' in 'order clause'
```

**Root Cause**:
Controller menggunakan nama kolom yang salah:
- Menggunakan `nama_bpkh` → seharusnya `nama_wilayah`
- Menggunakan `nama_produsen` → seharusnya `nama_unit`

**Solution**:
Updated `PemenangSigapController.php`:

1. **Method `index()`**:
   ```php
   // Before
   $bpkhList = BpkhList::orderBy('nama_bpkh')->get();
   $produsenList = ProdusenList::orderBy('nama_produsen')->get();
   
   // After
   $bpkhList = BpkhList::orderBy('nama_wilayah')->get();
   $produsenList = ProdusenList::orderBy('nama_unit')->get();
   ```

2. **Method `getPesertaList()`**:
   ```php
   // Before (BPKH)
   $data = BpkhList::orderBy('nama_bpkh')->get()->map(function($item) {
       return [
           'id' => $item->nama_bpkh,
           'text' => $item->nama_bpkh
       ];
   });
   
   // After (BPKH)
   $data = BpkhList::orderBy('nama_wilayah')->get()->map(function($item) {
       return [
           'id' => $item->nama_wilayah,
           'text' => $item->nama_wilayah
       ];
   });
   
   // Before (Produsen)
   $data = ProdusenList::orderBy('nama_produsen')->get()->map(function($item) {
       return [
           'id' => $item->nama_produsen,
           'text' => $item->nama_produsen
       ];
   });
   
   // After (Produsen)
   $data = ProdusenList::orderBy('nama_unit')->get()->map(function($item) {
       return [
           'id' => $item->nama_unit,
           'text' => $item->nama_unit
       ];
   });
   ```

**Files Changed**:
- ✅ `app/Http/Controllers/dashboard/PemenangSigapController.php`
- ✅ `docs/CMS_PEMENANG_SIGAP_AWARD.md` (updated notes)

**Testing**:
- ✅ Page `/cms/pemenang-sigap` loads without error
- ✅ Dropdown "Nama Pemenang" loads correctly for BPKH
- ✅ Dropdown "Nama Pemenang" loads correctly for Produsen

---

## Version 1.0.0 - 2025-01-20

### 🎉 Initial Release

**Features**:
- ✅ CRUD operations untuk pemenang SIGAP Award 2025
- ✅ 5 kategori pemenang
- ✅ Support BPKH dan Produsen
- ✅ 4 peringkat juara
- ✅ Image upload dengan preview
- ✅ Dynamic Select2 untuk nama pemenang
- ✅ AJAX operations
- ✅ Responsive design
- ✅ Complete documentation

**Files Created**:
- Migration: `2025_01_20_000001_create_pemenang_sigap_table.php`
- Model: `PemenangSigap.php`
- Controller: `PemenangSigapController.php`
- Views: `index.blade.php`, `modals.blade.php`, `scripts.blade.php`
- Routes: 5 endpoints
- Docs: `CMS_PEMENANG_SIGAP_AWARD.md`, `PEMENANG_SIGAP_QUICK_START.md`

---

## Database Schema Reference

### Tabel: `bpkh_list`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| **nama_wilayah** | string | Nama BPKH (e.g., "BPKH Wilayah VII Makassar") |
| kode_wilayah | string | Kode wilayah |
| created_at | timestamp | - |
| updated_at | timestamp | - |

### Tabel: `produsen_list`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| **nama_unit** | string | Nama Produsen (e.g., "Direktorat RPKHPWPH") |
| created_at | timestamp | - |
| updated_at | timestamp | - |

### Tabel: `pemenang_sigap`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| kategori | enum | Kategori pemenang |
| tipe_peserta | enum | BPKH atau Produsen |
| **nama_pemenang** | string | Nama dari `nama_wilayah` atau `nama_unit` |
| juara | enum | Peringkat juara |
| deskripsi | text | Deskripsi |
| foto_path | string | Path foto |
| urutan | integer | Urutan tampil |
| is_active | boolean | Status aktif |
| created_at | timestamp | - |
| updated_at | timestamp | - |

---

**Note**: Selalu gunakan `nama_wilayah` untuk BPKH dan `nama_unit` untuk Produsen!
