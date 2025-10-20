<div align="center">

<img src="public/sigap-assets/images/favicon.ico" width="128" height="128" alt="SIGAP Logo">

# 🌳 SIGAP Award 2025

### Penilaian Komponen Tata Kelola Informasi Geospasial Kehutanan

[Tentang](#tentang) •
[Teknologi](#teknologi) •
[Fitur](#fitur) •
[Instalasi](#instalasi) •
[Kontribusi](#kontribusi)

</div>

## 🎯 Tentang

SIGAP Award 2025 adalah laman homepage platform penilaian untuk Tata Kelola Informasi Geospasial Kehutanan yang mengadopsi standar **Integrated Geospatial Information Framework (IGIF)** secara global. Platform ini dirancang khusus untuk:

- **Unit Produsen Data & Informasi Kehutanan**
- **Unit Pelaksana Teknis - Balai Pemantapan Kawasan Hutan (BPKH)**

di lingkungan Kementerian Kehutanan Indonesia.

## 💻 Teknologi

Proyek ini dibangun menggunakan teknologi modern:

### Backend
- [Laravel 11](https://laravel.com) - Framework PHP yang elegan & powerful
- PHP 8.2+ dengan type safety
- MySQL Database untuk data persistence
- Google Sheets API untuk integrasi data

### Frontend
- Modern JavaScript (ES6+) dengan jQuery
- Blade Template Engine
- Bootstrap 5 untuk UI components
- CSS3 dengan animations & transitions
- Glass-morphism design patterns
- Responsive design untuk semua device

### Libraries & Tools
- Maatwebsite Excel untuk export data
- Mews Captcha untuk security
- Lottie untuk animations
- DataTables untuk table management

## ✨ Fitur

### 🏠 Landing Page
- **Homepage Modern & Responsif**
  - Tampilan elegan dengan glass-morphism design
  - Countdown timer menuju tanggal peluncuran
  - Komponen UI yang dinamis dan interaktif
  - Pengumuman peserta lolos tahap presentasi

### 🔐 Sistem Autentikasi
- **Login dengan Captcha**
  - Keamanan berlapis dengan captcha verification
  - Session management yang aman
  - Role-based access control (Admin, Asesor, Viewer)

### 👥 User Management
- **Manajemen Pengguna Lengkap**
  - CRUD user dengan role assignment
  - Filter dan pencarian user
  - Bulk actions untuk efisiensi
  - Export data user

### 📊 Dashboard Penilaian

#### **Presentation Module**
- **Penilaian Tahap Presentasi**
  - Penilaian individual per peserta
  - Sistem scoring dengan bobot kriteria
  - Session management (Sesi A, B, C)
  - Real-time progress tracking
  - Export hasil penilaian ke Excel
  - Visual feedback dengan progress indicators

#### **Exhibition Module**
- **Penilaian Tahap Exhibition**
  - Collective scoring untuk multiple peserta
  - Bulk selection dengan session grouping
  - Active state indicators untuk session buttons
  - Validasi data sebelum submit
  - Auto-save functionality
  - Export batch scoring results

### 🔄 Integrasi Google Sheets
- **Sinkronisasi Otomatis**
  - Sync data BPKH dan Produsen dari Google Sheets
  - Real-time data updates
  - Metadata preservation
  - Error handling dan logging
  - Scheduled sync support

### 📈 Fitur Penilaian IGIF
- **Framework Penilaian Komprehensif**
  - Adopsi standar IGIF global
  - 9 Pilar penilaian dengan sub-kriteria
  - Sistem bobot yang terukur
  - Validasi scoring otomatis
  - Perhitungan nilai akhir real-time

### 📢 Pengumuman
- **Halaman Announcement**
  - List peserta lolos tahap presentasi
  - Kategorisasi: Produsen & BPKH
  - Design modern dengan animations
  - Fully responsive untuk semua device
  - Sticky footer layout

## 🚀 Instalasi

```bash
# Clone repositori
git clone https://your-repository/sigap-awards.git

# Masuk ke direktori proyek
cd sigap-awards

# Install dependensi
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi Google Sheets (tambahkan ke .env)
GOOGLE_SHEETS_ID=your_spreadsheet_id
GOOGLE_SHEETS_RANGE=Sheet1!A:ZZ
GOOGLE_API_KEY=your_api_key

# Jalankan migrasi database
php artisan migrate

# Sinkronisasi data bpkh dan produsen dari Google Sheets
php artisan bpkh:sync-sheets
php artisan produsen:sync-sheets

# Compile assets
npm run dev

# Jalankan server
php artisan serve
```

### 📥 Sinkronisasi Data

Untuk memperbarui data dari Google Sheets, jalankan:

```bash
# Sync data BPKH
php artisan bpkh:sync-sheets

# Sync data Produsen
php artisan produsen:sync-sheets
```

Command ini akan:
- Mengambil data terbaru dari Google Sheets
- Memperbarui atau menambahkan record baru ke database
- Menyimpan metadata lengkap sesuai urutan kolom di spreadsheet
- Mencatat waktu sinkronisasi terakhir

## 📋 Struktur Modul

### Dashboard Modules
```
dashboard/
├── presentation/
│   ├── bpkh/          # Penilaian presentasi BPKH
│   └── produsen/      # Penilaian presentasi Produsen
├── exhibition/
│   ├── bpkh/          # Penilaian exhibition BPKH
│   └── produsen/      # Penilaian exhibition Produsen
└── user-management/   # Manajemen user & roles
```

### Roles & Permissions
- **Admin**: Full access ke semua fitur
- **Asesor**: Akses penilaian presentation & exhibition
- **Viewer**: Read-only access untuk melihat data

## 🎯 Workflow Penilaian

1. **Tahap Presentation**
   - Asesor login ke dashboard
   - Pilih kategori (BPKH/Produsen)
   - Pilih peserta dan session
   - Isi form penilaian per pilar
   - Submit dan lihat hasil real-time

2. **Tahap Exhibition**
   - Asesor masuk ke modul exhibition
   - Pilih multiple peserta (bulk selection)
   - Atau gunakan session button untuk auto-select
   - Isi collective scoring
   - Submit batch assessment

3. **Export & Reporting**
   - Export hasil ke Excel
   - View summary statistics
   - Track assessment progress

## 🆕 Recent Updates

### Version 2.0 (October 2025)
- ✅ **Exhibition Module Enhancement**
  - Added session button active state indicators
  - Improved bulk selection UX
  - Enhanced visual feedback for user actions
  
- ✅ **Announcement Page**
  - New announcement page for qualified participants
  - Modern glass-morphism design
  - Fully responsive layout with sticky footer
  - Smooth animations and transitions

- ✅ **UI/UX Improvements**
  - Enhanced responsive design for all viewports
  - Improved scroll behavior on small screens
  - Better visual hierarchy and spacing
  - Consistent color scheme across modules

- ✅ **Bug Fixes**
  - Fixed overflow issues on small viewports
  - Resolved session button state management
  - Fixed link navigation in landing page
  - Improved form validation feedback

## 🤝 Kontribusi

Kami sangat menghargai kontribusi dari komunitas. Jika Anda ingin berkontribusi:

1. Fork repositori ini
2. Buat branch fitur (`git checkout -b fitur/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur/AmazingFeature`)
5. Buat Pull Request

## 📝 Lisensi

Dikembangkan oleh Kementerian Kehutanan Indonesia © 2024

---

<div align="center">
Dibuat dengan 🔥 untuk Tata Kelola Informasi Geospasial Kehutanan yang lebih baik
</div>
