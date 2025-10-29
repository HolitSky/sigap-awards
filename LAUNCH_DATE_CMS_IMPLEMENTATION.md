# Launch Date CMS Implementation

## Overview
CMS untuk mengelola Launch Date yang ditampilkan di halaman landing. Sistem ini memungkinkan superadmin untuk menambah, edit, hapus, dan mengatur urutan launch date dengan fitur single date atau range date.

## Fitur Utama

### 1. **Model & Database**
- **Model**: `App\Models\LaunchDate`
- **Table**: `launch_dates`
- **Fields**:
  - `id` - Primary key
  - `title` - Judul launch date (contoh: "Penganugerahan Sigap Award")
  - `is_range_date` - Boolean untuk menentukan tipe tanggal (single/range)
  - `single_date` - Tanggal tunggal (jika `is_range_date` = false)
  - `start_date` - Tanggal mulai (jika `is_range_date` = true)
  - `end_date` - Tanggal selesai (jika `is_range_date` = true)
  - `is_active` - Status aktif/tidak aktif
  - `order` - Urutan tampilan
  - `timestamps` - Created at & Updated at

### 2. **Controller**
- **Controller**: `App\Http\Controllers\dashboard\CmsController`
- **Methods**:
  - `launchDateIndex()` - Menampilkan halaman manajemen
  - `launchDateStore()` - Menyimpan launch date baru
  - `launchDateUpdate()` - Update launch date
  - `launchDateDestroy()` - Hapus launch date
  - `launchDateUpdateOrder()` - Update urutan dengan drag & drop

### 3. **Routes** (Superadmin Only)
```php
Route::middleware(['role:superadmin'])->group(function () {
    Route::get('/cms/launch-date', [CmsController::class, 'launchDateIndex'])
        ->name('dashboard.cms.launch-date.index');
    Route::post('/cms/launch-date', [CmsController::class, 'launchDateStore'])
        ->name('dashboard.cms.launch-date.store');
    Route::put('/cms/launch-date/{id}', [CmsController::class, 'launchDateUpdate'])
        ->name('dashboard.cms.launch-date.update');
    Route::delete('/cms/launch-date/{id}', [CmsController::class, 'launchDateDestroy'])
        ->name('dashboard.cms.launch-date.destroy');
    Route::post('/cms/launch-date/update-order', [CmsController::class, 'launchDateUpdateOrder'])
        ->name('dashboard.cms.launch-date.update-order');
});
```

### 4. **Views**
- **CMS View**: `resources/views/dashboard/pages/cms/launch-date/index.blade.php`
- **Landing Partial**: `resources/views/landing/pages/home/partials/launch-date.blade.php`

### 5. **Navigation**
Menu CMS Launch Date ditambahkan di sidebar dashboard (hanya untuk superadmin):
```blade
<a href="{{ route('dashboard.cms.launch-date.index') }}" class="waves-effect">
    <i class="mdi mdi-calendar-range"></i>
    <span key="t-forms">Launch Date</span>
</a>
```

## Cara Kerja

### Tambah Launch Date
1. Klik tombol "Tambah Launch Date"
2. Isi form:
   - **Judul**: Nama event/acara
   - **Tipe Tanggal**: 
     - Single Date: Untuk 1 tanggal saja
     - Range Date: Untuk rentang tanggal (misal: 23-24 Oktober)
   - **Tanggal**: Sesuai tipe yang dipilih
   - **Urutan**: Angka untuk menentukan urutan tampilan
   - **Status**: Aktif/Tidak Aktif
3. Klik "Simpan"

### Edit Launch Date
1. Klik tombol edit (icon pensil) pada data yang ingin diubah
2. Ubah data yang diperlukan
3. Klik "Update"

### Hapus Launch Date
1. Klik tombol hapus (icon delete) pada data yang ingin dihapus
2. Konfirmasi penghapusan
3. Data akan terhapus

### Drag & Drop Urutan
- Drag handle (icon drag vertical) di sebelah kiri setiap row
- Drag ke posisi yang diinginkan
- Urutan akan otomatis tersimpan

## Integrasi dengan Landing Page

### HomeController
`App\Http\Controllers\landing\HomeController` sudah diupdate untuk mengambil data launch date dari database:

```php
// Get active launch date from database (dynamic)
$launchDate = LaunchDate::getActiveLaunchDate();

// Fallback to default if no active launch date
$rangeDate = $launchDate ? $launchDate->is_range_date : false;
$rangeDateStart = $launchDate && $launchDate->is_range_date ? $launchDate->start_date : Carbon::create(2025, 10, 23, 0, 0, 0);
$rangeDateEnd = $launchDate && $launchDate->is_range_date ? $launchDate->end_date : Carbon::create(2025, 10, 24, 0, 0, 0);
$singleDate = $launchDate && !$launchDate->is_range_date ? $launchDate->single_date : null;
```

### Landing Partial
Partial `launch-date.blade.php` sudah diupdate untuk menampilkan data dinamis:

```blade
@if($launchDate)
    <span class="launch-date__calendar-label">{{ $launchDate->title }}</span>
    <time class="launch-date__calendar-date" datetime="{{ $launchDate->datetime }}">
        {{ $launchDate->formatted_date }}
    </time>
    <p class="launch-date__calendar-month">{{ $launchDate->month_name }}</p>
@else
    {{-- FALLBACK: Default static content --}}
    <span class="launch-date__calendar-label">Penganugerahan Sigap Award</span>
    <time class="launch-date__calendar-date" datetime="2025-10-23">
        23-24
    </time>
    <p class="launch-date__calendar-month">Oktober</p>
@endif
```

## Model Attributes & Methods

### Attributes
- `formatted_date` - Format tanggal untuk tampilan (contoh: "23-24" atau "23")
- `month_name` - Nama bulan dalam Bahasa Indonesia
- `datetime` - Format datetime untuk HTML attribute

### Static Methods
- `getActiveLaunchDate()` - Mendapatkan launch date yang aktif (is_active = true) dengan order terkecil
- `getAllActiveLaunchDates()` - Mendapatkan semua launch date yang aktif

## Validasi

### Store & Update
- `title` - Required, string, max 255 karakter
- `is_range_date` - Required, boolean
- `single_date` - Required jika `is_range_date` = false
- `start_date` - Required jika `is_range_date` = true
- `end_date` - Required jika `is_range_date` = true, harus >= start_date
- `order` - Integer, min 0
- `is_active` - Boolean

## Seeder
Seeder sudah dibuat untuk data awal:
```bash
php artisan db:seed --class=LaunchDateSeeder
```

Data default:
1. **Penganugerahan Sigap Award** (Range: 23-24 Oktober 2025) - Aktif
2. **Pembukaan Pendaftaran** (Single: 1 Oktober 2025) - Tidak Aktif

## Akses
- **URL CMS**: `/cms/launch-date`
- **Akses**: Hanya Superadmin (middleware: `role:superadmin`)
- **Menu**: Sidebar Dashboard > CMS > Launch Date

## Dependencies
- SortableJS - Untuk drag & drop functionality
- SweetAlert2 - Untuk konfirmasi delete
- jQuery - Untuk AJAX operations

## Testing
1. Login sebagai superadmin
2. Akses menu CMS > Launch Date
3. Test CRUD operations:
   - Tambah launch date baru (single & range)
   - Edit launch date
   - Hapus launch date
   - Drag & drop untuk ubah urutan
4. Cek landing page untuk melihat perubahan data

## Notes
- Hanya launch date dengan `is_active = true` yang akan ditampilkan di landing page
- Jika ada multiple active launch dates, yang ditampilkan adalah yang memiliki `order` terkecil
- Sistem memiliki fallback ke data static jika tidak ada active launch date di database
- Format tanggal otomatis disesuaikan (single: "23", range: "23-24")
- Nama bulan otomatis dalam Bahasa Indonesia

## Migration
Migration file: `database/migrations/2025_10_29_000001_create_launch_dates_table.php`

Untuk rollback:
```bash
php artisan migrate:rollback --step=1
```

## Future Enhancements
- Multi-language support
- Preview sebelum publish
- Schedule publish (auto activate pada tanggal tertentu)
- History/audit log untuk perubahan data
- Bulk operations (activate/deactivate multiple items)
