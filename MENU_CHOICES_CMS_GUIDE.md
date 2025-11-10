# Menu Choices CMS - Panduan Implementasi

## Overview
Sistem CMS untuk mengelola menu choices di landing page secara dinamis. Mendukung 2 mode tampilan:
1. **Dengan Main Menu Modal** - Tombol main menu yang membuka modal berisi pilihan menu
2. **Langsung Tampil** - Semua menu langsung ditampilkan tanpa modal

## Struktur Database

### Tabel: `menu_choices`
- `id` - Primary key
- `main_menu_title` - Judul main menu modal (nullable, required jika use_main_menu = true)
- `use_main_menu` - Boolean, true = pakai modal, false = langsung tampil
- `menu_items` - JSON array berisi menu items: `[{title, link, icon}]`
- `is_active` - Boolean, **hanya 1 yang boleh aktif**
- `timestamps` - created_at, updated_at

### Contoh JSON menu_items:
```json
[
  {
    "title": "Vote Pengelola IGT Terbaik 2025",
    "type": "link",
    "link": "https://form.sigap-award.site/voting2025",
    "icon": "🗳️"
  },
  {
    "title": "Upload Poster",
    "type": "modal",
    "link": null,
    "icon": "🖼️",
    "submenu": [
      {
        "title": "Upload Poster BPKH",
        "link": "https://form.sigap-award.site/upload-poster-bpkh"
      },
      {
        "title": "Upload Poster Produsen",
        "link": "https://form.sigap-award.site/upload-poster-produsen"
      }
    ]
  },
  {
    "title": "Kriteria Poster SIGAP Award 2025",
    "type": "link",
    "link": "/poster-criteria",
    "icon": "📋"
  }
]
```

**Menu Item Types:**
- `type: "link"` - Direct link (buka URL langsung)
- `type: "modal"` - Buka modal dengan sub-menu pilihan

## Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Seed Data (Optional)
```bash
php artisan db:seed --class=MenuChoiceSeeder
```

Seeder akan membuat 2 contoh:
1. Menu dengan main menu modal (aktif)
2. Menu langsung tampil (tidak aktif)

### 3. Akses CMS
- **URL**: `/cms/menu-choice`
- **Akses**: Hanya Superadmin
- **Fitur**:
  - CRUD Menu Choices
  - **Hanya 1 menu choice yang boleh aktif** (auto-deactivate yang lain)
  - Dynamic menu items (bisa tambah/hapus menu item)
  - 2 Mode tampilan: Modal atau Direct

## Cara Menggunakan CMS

### Tambah Menu Choice

1. Klik "Tambah Menu Choice"
2. Pilih **Mode Tampilan**:
   - **Dengan Main Menu Modal**: Tampilkan tombol yang buka modal
   - **Langsung Tampil**: Tampilkan semua menu langsung
3. Isi form:
   - **Mode Tampilan**: Pilih "Dengan Main Menu Modal" atau "Langsung Tampil"
   - **Judul Main Menu**: Isi jika pilih mode modal (contoh: "Menu SIGAP Award 2025")
   - **Menu Items**: Tambah menu items dengan klik "Tambah Menu Item"
     - **Judul Menu**: Nama menu (contoh: "Upload Poster")
     - **Tipe**: Pilih "Direct Link" atau "Modal (Sub-menu)"
     - **Link URL**: Isi jika pilih Direct Link
     - **Icon**: Emoji atau icon class (optional)
     - **Sub-Menu**: Jika pilih Modal, tambah sub-menu items:
       - Klik "Tambah Sub-Menu"
       - Isi judul dan link untuk setiap sub-menu
   - **Aktif**: Centang untuk menampilkan di landing page (yang lain otomatis non-aktif)
4. Klik "Simpan"

### Edit Menu Choice

1. Klik tombol edit (icon pensil)
2. Ubah data yang diperlukan
3. Tambah/hapus menu items sesuai kebutuhan
4. Klik "Update"

### Hapus Menu Choice

1. Klik tombol hapus (icon tempat sampah)
2. Konfirmasi penghapusan

## Tampilan di Landing Page

File: `resources/views/landing/pages/home/partials/box-form-choice.blade.php`

### Mode 1: Dengan Main Menu Modal (use_main_menu = true)
```
┌─────────────────────────────┐
│   📋 Menu SIGAP Award 2025  │  ← Tombol Main Menu
└─────────────────────────────┘

Klik tombol → Buka Modal:
┌─────────────────────────────┐
│  Menu SIGAP Award 2025   ×  │
├─────────────────────────────┤
│ 🗳️ Vote Pengelola IGT       │
│ 📋 Kriteria Poster          │
│ 📑 Rekapan Presentasi       │
│ 👨‍⚖️ Lihat CV Juri            │
└─────────────────────────────┘
```

### Mode 2: Langsung Tampil (use_main_menu = false)
```
┌─────────────────────────────┐
│ 🗳️ Vote Pengelola IGT       │
└─────────────────────────────┘
┌─────────────────────────────┐
│ 📝 Formulir Pendaftaran BPKH│
└─────────────────────────────┘
┌─────────────────────────────┐
│ 🏭 Formulir Pendaftaran     │
│    Produsen                 │
└─────────────────────────────┘
```

## Technical Details

### Model: `MenuChoice`

**Fillable:**
- main_menu_title, use_main_menu, menu_items, is_active

**Casts:**
- use_main_menu: boolean
- is_active: boolean
- menu_items: array

**Scopes:**
- `active()` - Filter yang aktif

**Helper Methods:**
- `usesMainMenu()` - Cek apakah menggunakan main menu
- `getMenuItems()` - Get menu items array

### Controller: `CmsController`

**Methods:**
- `menuChoiceIndex()` - Tampilkan halaman CMS
- `menuChoiceStore(Request $request)` - Simpan menu choice baru
- `menuChoiceUpdate(Request $request, $id)` - Update menu choice
- `menuChoiceDestroy($id)` - Hapus menu choice

**Validation Rules:**
```php
'main_menu_title' => 'nullable|string|max:100',
'use_main_menu' => 'required|boolean',
'menu_items' => 'required|json',
```

**Auto-Deactivate Logic:**
- Saat save/update dengan `is_active = true`, semua menu choice lain otomatis di-set `is_active = false`
- Ini memastikan hanya 1 menu choice yang aktif di landing page

### Landing Controller: `HomeController`

Di method `index()`, data menu choice dikirim ke view:
```php
$menuChoice = MenuChoice::active()->first();
return view('landing.pages.home.index', compact(..., 'menuChoice'));
```

### Routes
```php
// Superadmin only
Route::get('/cms/menu-choice', [CmsController::class, 'menuChoiceIndex']);
Route::post('/cms/menu-choice', [CmsController::class, 'menuChoiceStore']);
Route::put('/cms/menu-choice/{id}', [CmsController::class, 'menuChoiceUpdate']);
Route::delete('/cms/menu-choice/{id}', [CmsController::class, 'menuChoiceDestroy']);
```

## Files Created/Modified

### New Files:
1. **Migration**: `database/migrations/2024_11_10_070600_create_menu_choices_table.php`
2. **Model**: `app/Models/MenuChoice.php`
3. **Seeder**: `database/seeders/MenuChoiceSeeder.php`
4. **Views**:
   - `resources/views/dashboard/pages/cms/menu-choices/index.blade.php`
   - `resources/views/dashboard/pages/cms/menu-choices/modals.blade.php`
   - `resources/views/dashboard/pages/cms/menu-choices/scripts.blade.php`

### Modified Files:
1. **Controller**: `app/Http/Controllers/dashboard/CmsController.php` - Added menu choice methods
2. **Routes**: `routes/web.php` - Added menu choice routes
3. **Landing Controller**: `app/Http/Controllers/landing/HomeController.php` - Added menuChoice query
4. **Landing View**: `resources/views/landing/pages/home/partials/box-form-choice.blade.php` - Dynamic rendering
5. **Navigation**: `resources/views/dashboard/layouts/navigation.blade.php` - Added Menu Choices link

## Use Cases

### Use Case 1: Event dengan Multiple Forms
**Mode**: Langsung Tampil
```
Menu Items:
- Formulir Pendaftaran BPKH
- Formulir Pendaftaran Produsen
- Upload Poster
- Upload Presentasi
```

### Use Case 2: Event dengan Berbagai Informasi
**Mode**: Dengan Main Menu Modal
```
Main Menu: "Menu SIGAP Award 2025"
Menu Items:
- Kriteria Poster
- Rekapan Presentasi
- CV Juri
- Pengumuman Peserta
```

### Use Case 3: Voting Event
**Mode**: Langsung Tampil
```
Menu Items:
- Vote Pengelola IGT Terbaik 2025
```

## Tips

1. **Icon**: Bisa pakai emoji (🗳️, 📋, 📑) atau Material Design Icons class (mdi-vote, mdi-file)
2. **Link**: Bisa internal route atau external URL
3. **Jumlah Menu**: Tidak ada batasan, tapi untuk UX yang baik maksimal 5-7 menu items
4. **Mode Selection**: 
   - Gunakan **Modal** jika ada banyak menu (>3)
   - Gunakan **Direct** jika menu sedikit (1-3) atau ingin user langsung akses

## Troubleshooting

**Q: Menu tidak muncul di landing page?**
A: Pastikan menu choice sudah di-set **Aktif** dan minimal ada 1 menu item

**Q: Modal tidak muncul saat klik tombol?**
A: Pastikan mode **Dengan Main Menu Modal** dipilih dan JavaScript tidak error

**Q: Semua menu tidak aktif?**
A: Sistem hanya mengizinkan 1 aktif. Edit salah satu dan centang Aktif.

## Summary

Menu Choices CMS memberikan fleksibilitas penuh untuk mengatur menu di landing page:
- ✅ 2 Mode tampilan (Modal atau Direct)
- ✅ Dynamic menu items dengan icon
- ✅ Auto-deactivate untuk single active menu
- ✅ Easy to manage via CMS
- ✅ JSON-based menu structure
