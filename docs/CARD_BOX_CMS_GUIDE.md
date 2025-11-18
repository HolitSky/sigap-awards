# Card Box CMS Implementation Guide

## Overview
Sistem CMS untuk mengelola konten box counter yang dinamis di halaman landing. Superadmin dapat mengelola judul (h3), deskripsi (p), teks tombol, link URL, atau modal pop-up.

## Langkah-langkah Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

Migration akan membuat tabel `card_boxes` dengan kolom:
- `title` - Judul (h3) - Required, 4-100 karakter
- `description` - Deskripsi (p) - Required, 10-500 karakter
- `content_type` - Enum ('text_only', 'link', 'modal') - Required
- `button_text` - Teks tombol - Nullable, required untuk link/modal
- `link_url` - URL link - Nullable, required untuk link
- `modal_content` - Konten modal - Nullable, required untuk modal
- `order` - Integer, urutan tampilan (0-based)
- `is_active` - Boolean, **hanya 1 yang boleh aktif**
- `timestamps` - created_at, updated_at

### 2. Seed Data (Optional)
Jalankan seeder untuk data awal:

```bash
php artisan db:seed --class=CardBoxSeeder
```

Seeder akan membuat 3 contoh data:
1. Card box tipe Text Only (aktif)
2. Card box tipe Link URL (tidak aktif)
3. Card box tipe Modal (tidak aktif)

### 3. Akses CMS
- **URL**: `/cms/card-box`
- **Akses**: Hanya untuk Superadmin
- **Fitur**: 
  - CRUD (Create, Read, Update, Delete)
  - Drag & Drop untuk mengubah urutan
  - **Hanya 1 card box yang boleh aktif** (auto-deactivate yang lain)
  - 3 Tipe Konten: Text Only, Link URL, atau Modal

### 4. Cara Menggunakan CMS

#### Tambah Card Box Baru
1. Klik tombol "Tambah Card Box"
2. Isi form:
   - **Judul**: Judul yang akan ditampilkan (h3)
   - **Deskripsi**: Deskripsi singkat (p)
   - **Tipe Konten**: Pilih salah satu:
     - **Text Only**: Hanya menampilkan judul dan deskripsi (tanpa tombol)
     - **Link URL**: Menampilkan tombol yang membuka URL di tab baru
     - **Modal**: Menampilkan tombol yang membuka pop-up modal
   - **Teks Tombol**: Isi jika pilih Link URL atau Modal
   - **Link URL**: Isi jika pilih Link URL
   - **Konten Modal**: Isi jika pilih Modal
   - **Urutan**: Kosongkan untuk otomatis di akhir
   - **Aktif**: Centang untuk menampilkan di landing page (yang lain otomatis non-aktif)
3. Klik "Simpan"

#### Edit Card Box
1. Klik tombol edit (icon pensil) pada card box yang ingin diubah
2. Ubah data yang diperlukan
3. Klik "Update"

#### Hapus Card Box
1. Klik tombol hapus (icon tempat sampah)
2. Konfirmasi penghapusan
3. Data akan terhapus permanen

#### Ubah Urutan
1. Drag & drop baris tabel menggunakan icon drag
2. Urutan akan otomatis tersimpan
3. Page akan reload untuk menampilkan urutan baru

### 5. Tampilan di Landing Page
File: `resources/views/landing/pages/home/partials/box-counter.blade.php`

Card box yang **aktif** akan ditampilkan di halaman landing sesuai urutan. Jika tidak ada data, akan menampilkan konten default (fallback).

**Contoh Tampilan:**
- Judul ditampilkan sebagai h3
- Deskripsi ditampilkan sebagai paragraf
- **Text Only**: Tidak ada tombol
- **Link URL**: Tombol membuka link di tab baru
- **Modal**: Tombol membuka pop-up dengan konten yang diatur

## Technical Details

### Routes
```php
// Superadmin only
Route::get('/cms/card-box', [CmsController::class, 'cardBoxIndex']);
Route::post('/cms/card-box', [CmsController::class, 'cardBoxStore']);
Route::put('/cms/card-box/{id}', [CmsController::class, 'cardBoxUpdate']);
Route::delete('/cms/card-box/{id}', [CmsController::class, 'cardBoxDestroy']);
Route::post('/cms/card-box/update-order', [CmsController::class, 'cardBoxUpdateOrder']);
```

### Model
`App\Models\CardBox`

**Fillable:**
- title, description, content_type, button_text, link_url, modal_content, order, is_active

**Casts:**
- is_active: boolean

**Constants:**
- TYPE_TEXT_ONLY = 'text_only'
- TYPE_LINK = 'link'
- TYPE_MODAL = 'modal'

**Scopes:**
- `active()` - Filter yang aktif (is_active = true)
- `ordered()` - Urutkan berdasarkan order ASC

**Usage:**
```php
// Get active card boxes ordered
$cardBoxes = CardBox::active()->ordered()->get();
```

### Controller
`App\Http\Controllers\dashboard\CmsController`

**Methods:**
- `cardBoxIndex()` - Tampilkan halaman CMS
- `cardBoxStore(Request $request)` - Simpan card box baru
- `cardBoxUpdate(Request $request, $id)` - Update card box
- `cardBoxDestroy($id)` - Hapus card box
- `cardBoxUpdateOrder(Request $request)` - Update urutan

**Validation Rules:**
```php
'title' => 'required|string|min:4|max:100',
'description' => 'required|string|min:10|max:500',
'content_type' => 'required|in:text_only,link,modal',
'button_text' => 'required_if:content_type,link,modal|nullable|string|max:50',
'link_url' => 'required_if:content_type,link|nullable|url|max:255',
'modal_content' => 'required_if:content_type,modal|nullable|string',
'order' => 'nullable|integer|min:0'
```

**Auto-Deactivate Logic:**
- Saat save/update dengan `is_active = true`, semua card box lain otomatis di-set `is_active = false`
- Ini memastikan hanya 1 card box yang aktif di landing page

### Landing Controller
`App\Http\Controllers\landing\HomeController`

Di method `index()`, data card boxes dikirim ke view:
```php
$cardBoxes = CardBox::active()->ordered()->get();
return view('landing.pages.home.index', compact(..., 'cardBoxes'));
```

## Files Created/Modified

### New Files:
1. `database/migrations/2024_11_10_060400_create_card_boxes_table.php`
2. `app/Models/CardBox.php`
3. `database/seeders/CardBoxSeeder.php`
4. `resources/views/dashboard/pages/cms/card-box/index.blade.php`
5. `resources/views/dashboard/pages/cms/card-box/modals.blade.php`
6. `resources/views/dashboard/pages/cms/card-box/scripts.blade.php`

### Modified Files:
1. `app/Http/Controllers/dashboard/CmsController.php` - Added card box methods
2. `routes/web.php` - Added card box routes
3. `app/Http/Controllers/landing/HomeController.php` - Added box counters query
4. `resources/views/landing/pages/home/partials/box-counter.blade.php` - Dynamic content

## Troubleshooting

### Card box tidak muncul di landing page
- Pastikan status "Aktif" dicentang
- Cek apakah ada data di database
- Clear cache: `php artisan cache:clear`

### Error saat drag & drop
- Pastikan jQuery dan SortableJS sudah ter-load
- Cek console browser untuk error JavaScript

### Modal tidak muncul
- Pastikan "Tipe Aksi" diset ke "Modal"
- Pastikan "Konten Modal" sudah diisi
- Cek console browser untuk error

## Support
Untuk pertanyaan atau issue, hubungi tim development.
