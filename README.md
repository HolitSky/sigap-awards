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

- [Laravel 12](https://laravel.com) - Framework PHP yang elegan & powerful
- Modern JavaScript dengan pendekatan OOP
- Responsive Design dengan CSS modern
- Countdown Timer & Dynamic UI Components
- Terhubung dengan Platform https://tally.so/ sebagai form

## ✨ Fitur

- 🏠 **Landing Page Modern**
  - Tampilan yang elegan dan responsif
  - Countdown timer menuju tanggal peluncuran
  - Komponen UI yang dinamis dan interaktif

- 📊 **Framework Penilaian IGIF**
  - Adopsi standar global untuk penilaian geospasial
  - Kriteria yang disesuaikan dengan konteks kehutanan Indonesia
  - Terintegrasi dengan platform Tally.so untuk pengisian form penilaian
  - Sistem penilaian yang transparan dan terukur

- 🔄 **Integrasi Google Sheets**
  - Sinkronisasi otomatis data form dari Google Sheets
  - Pengelolaan data responden yang real-time
  - Penyimpanan metadata lengkap dari setiap submission

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
php artisan bpkh:sync-sheets
```

Command ini akan:
- Mengambil data terbaru dari Google Sheets
- Memperbarui atau menambahkan record baru ke database
- Menyimpan metadata lengkap sesuai urutan kolom di spreadsheet
- Mencatat waktu sinkronisasi terakhir

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
