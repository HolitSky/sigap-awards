# Launch Date - New Date Types Feature

## Overview
Menambahkan 2 tipe tanggal baru untuk Launch Date:
1. **Month Only** - Hanya menampilkan nama bulan (tanpa tanggal)
2. **Coming Soon** - Menampilkan teks "COMING SOON"

## Changes Made

### 1. Database Migration ✅
**File:** `database/migrations/2025_11_24_125604_add_date_type_to_launch_dates_table.php`

Added columns:
- `date_type` (string): 'single', 'range', 'month_only', 'coming_soon'
- `month_year` (string): Format YYYY-MM untuk month_only type

```bash
php artisan migrate
```

### 2. LaunchDate Model ✅
**File:** `app/Models/LaunchDate.php`

**Updated:**
- Added `date_type` and `month_year` to `$fillable`
- Updated `getFormattedDateAttribute()` to handle new types:
  - `month_only`: Returns empty string (no date number)
  - `coming_soon`: Returns 'COMING'
- Updated `getMonthNameAttribute()` to handle new types:
  - `month_only`: Returns month name from `month_year`
  - `coming_soon`: Returns 'SOON'

### 3. CmsController ✅
**File:** `app/Http/Controllers/dashboard/CmsController.php`

**Updated Methods:**
- `launchDateStore()`: Added validation and logic for new date types
- `launchDateUpdate()`: Added validation and logic for new date types

**Validation Rules:**
```php
'date_type' => 'required|in:single,range,month_only,coming_soon',
'month_year' => 'required_if:date_type,month_only|nullable|string',
```

### 4. Dashboard CMS View ✅
**File:** `resources/views/dashboard/pages/cms/launch-date/index.blade.php`

**Updated:**
- Table display to show all 4 date types with badges
- Add Modal form with 4 radio options
- Edit Modal form with 4 radio options
- JavaScript to toggle date fields based on selected type
- Edit button data attributes to include `date-type` and `month-year`

**New Form Fields:**
- Month Only: `<input type="month">` for selecting month and year
- Coming Soon: Info alert (no input needed)

### 5. Landing Page View ✅
**File:** `resources/views/landing/pages/home/partials/launch-date.blade.php`

**Updated Calendar Display:**
```blade
@if($launchDate->date_type == 'month_only')
    {{-- Only show month name, hide date number --}}
    <p class="launch-date__calendar-month" style="font-size: 2.5em;">
        {{ $launchDate->month_name }}
    </p>

@elseif($launchDate->date_type == 'coming_soon')
    {{-- Show "COMING SOON" text --}}
    <time class="launch-date__calendar-date">COMING</time>
    <p class="launch-date__calendar-month">SOON</p>

@else
    {{-- Normal display for single/range --}}
    <time class="launch-date__calendar-date">
        {{ $launchDate->formatted_date }}
    </time>
    <p class="launch-date__calendar-month">
        {{ $launchDate->month_name }}
    </p>
@endif
```

### 6. HomeController ✅
**File:** `app/Http/Controllers/landing/HomeController.php`

**Updated `index()` method:**
- Added switch case for all 4 date types
- `month_only`: Uses first day of month for countdown
- `coming_soon`: Uses far future date (2099-12-31) for countdown

## Usage Guide

### Creating Launch Date via Dashboard

1. **Login** ke dashboard admin
2. Buka **CMS → Launch Date**
3. Klik **Tambah Launch Date**
4. Pilih salah satu tipe:

#### A. Single Date
- Pilih radio "Single Date"
- Isi tanggal
- Tampilan: `01 DESEMBER`

#### B. Range Date
- Pilih radio "Range Date"
- Isi tanggal mulai dan selesai
- Tampilan: `23-24 OKTOBER`

#### C. Month Only (NEW!)
- Pilih radio "Month Only"
- Pilih bulan dan tahun
- Tampilan: **DESEMBER** (hanya bulan, tanpa angka tanggal)

#### D. Coming Soon (NEW!)
- Pilih radio "Coming Soon"
- Tidak perlu isi tanggal
- Tampilan: **COMING SOON**

### Display Examples

```
┌─────────────────────────┐
│  Penganugerahan Sigap   │
│        Award            │
│                         │
│          01             │  ← Single Date
│       DESEMBER          │
└─────────────────────────┘

┌─────────────────────────┐
│  Penganugerahan Sigap   │
│        Award            │
│                         │
│        23-24            │  ← Range Date
│       OKTOBER           │
└─────────────────────────┘

┌─────────────────────────┐
│  Penganugerahan Sigap   │
│        Award            │
│                         │
│                         │
│      DESEMBER           │  ← Month Only (bigger)
└─────────────────────────┘

┌─────────────────────────┐
│  Penganugerahan Sigap   │
│        Award            │
│                         │
│       COMING            │  ← Coming Soon
│        SOON             │
└─────────────────────────┘
```

## Database Structure

### launch_dates table
```
id              INT
title           VARCHAR(255)
date_type       VARCHAR(20)      'single', 'range', 'month_only', 'coming_soon'
is_range_date   BOOLEAN          (legacy, auto-set based on date_type)
single_date     DATE             (for single type)
start_date      DATE             (for range type)
end_date        DATE             (for range type)
month_year      VARCHAR(7)       (for month_only type, format: 2025-12)
is_active       BOOLEAN
order           INT
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

## Testing Checklist

- [x] Migration runs successfully
- [x] Can create launch date with type "single"
- [x] Can create launch date with type "range"
- [x] Can create launch date with type "month_only"
- [x] Can create launch date with type "coming_soon"
- [x] Can edit existing launch dates
- [x] Dashboard table displays all types correctly
- [x] Landing page displays "month_only" correctly (only month name)
- [x] Landing page displays "coming_soon" correctly (COMING SOON text)
- [x] JavaScript countdown works for all types
- [x] Cache cleared after changes

## Notes

- **Month Only** menggunakan hari pertama bulan untuk countdown
- **Coming Soon** menggunakan tanggal jauh di masa depan (2099-12-31) untuk countdown
- Hanya 1 launch date yang boleh aktif pada satu waktu
- Urutan launch date bisa diubah dengan drag & drop
- Semua tipe kompatibel dengan sistem countdown yang sudah ada

## Files Modified

1. `database/migrations/2025_11_24_125604_add_date_type_to_launch_dates_table.php` (NEW)
2. `app/Models/LaunchDate.php`
3. `app/Http/Controllers/dashboard/CmsController.php`
4. `app/Http/Controllers/landing/HomeController.php`
5. `resources/views/dashboard/pages/cms/launch-date/index.blade.php`
6. `resources/views/landing/pages/home/partials/launch-date.blade.php`

---

**Implemented by:** Cascade AI Assistant  
**Date:** 24 November 2025  
**Status:** ✅ COMPLETED
