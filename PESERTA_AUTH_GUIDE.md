# Panduan Autentikasi Peserta SIGAP Awards

## Overview
Sistem autentikasi terpisah untuk peserta dengan tabel, model, guard, dan routes sendiri yang independen dari sistem autentikasi admin/juri.

## Struktur Database

### Tabel: `user_peserta`
- `id` - Primary key
- `name` - Nama lengkap peserta
- `email` - Email (unique)
- `password` - Password (hashed)
- `phone` - Nomor telepon (nullable)
- `institution` - Nama instansi/perusahaan (nullable)
- `status` - Status akun (active/inactive)
- `remember_token` - Token untuk "remember me"
- `timestamps` - Created at & Updated at

## Konfigurasi

### Auth Guard
Guard: `peserta`
- Driver: session
- Provider: user_peserta
- Model: `App\Models\UserPeserta`

### Routes
**Prefix:** `/peserta`

**Guest Routes (tidak perlu login):**
- `GET /peserta/login` - Halaman login
- `POST /peserta/login` - Proses login
- `GET /peserta/daftar` - Halaman registrasi
- `POST /peserta/daftar` - Proses registrasi

**Protected Routes (perlu login):**
- `GET /peserta/dashboard` - Dashboard peserta
- `POST /peserta/logout` - Logout

## Cara Menggunakan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Akses Halaman
- **Login:** `http://your-domain.com/peserta/login`
- **Registrasi:** `http://your-domain.com/peserta/daftar`
- **Dashboard:** `http://your-domain.com/peserta/dashboard`

### 3. Registrasi Peserta Baru
1. Buka halaman `/peserta/daftar`
2. Isi form dengan data:
   - Nama Lengkap (required)
   - Email (required, unique)
   - No. Telepon (optional)
   - Instansi/Perusahaan (optional)
   - Password (required, min 6 karakter)
   - Konfirmasi Password (required)
3. Klik "Daftar Sekarang"
4. Setelah berhasil, otomatis login dan redirect ke dashboard

### 4. Login Peserta
1. Buka halaman `/peserta/login`
2. Masukkan email dan password
3. Centang "Ingat Saya" jika ingin tetap login
4. Klik "Masuk"

## Penggunaan di Controller

### Mengakses User yang Login
```php
use Illuminate\Support\Facades\Auth;

// Get authenticated peserta
$peserta = Auth::guard('peserta')->user();

// Check if peserta is authenticated
if (Auth::guard('peserta')->check()) {
    // User is logged in
}

// Get specific field
$name = Auth::guard('peserta')->user()->name;
$email = Auth::guard('peserta')->user()->email;
```

### Middleware untuk Protect Routes
```php
// Dalam routes/web.php
Route::middleware(['auth:peserta'])->group(function () {
    // Protected routes untuk peserta
});
```

### Logout Peserta
```php
Auth::guard('peserta')->logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
```

## Penggunaan di Blade View

### Cek Status Login
```blade
@auth('peserta')
    <p>Selamat datang, {{ Auth::guard('peserta')->user()->name }}</p>
@endauth

@guest('peserta')
    <a href="{{ route('peserta.login') }}">Login</a>
@endguest
```

### Menampilkan Data User
```blade
{{ Auth::guard('peserta')->user()->name }}
{{ Auth::guard('peserta')->user()->email }}
{{ Auth::guard('peserta')->user()->phone ?? 'Tidak ada' }}
{{ Auth::guard('peserta')->user()->institution ?? 'Tidak ada' }}
```

## Fitur

### ✅ Registrasi
- Form registrasi lengkap dengan validasi
- Password minimal 6 karakter
- Email harus unique
- Auto login setelah registrasi berhasil

### ✅ Login
- Login dengan email dan password
- Remember me functionality
- Validasi credentials
- Session management

### ✅ Dashboard
- Menampilkan informasi peserta
- Tombol logout
- Protected dengan middleware auth:peserta

### ✅ Security
- Password di-hash menggunakan bcrypt
- CSRF protection
- Session regeneration setelah login
- Guard terpisah dari admin

## Perbedaan dengan Auth Admin

| Fitur | Admin/Juri | Peserta |
|-------|-----------|---------|
| Tabel | `users` | `user_peserta` |
| Guard | `web` | `peserta` |
| Model | `UserModel` | `UserPeserta` |
| Routes Prefix | `/` | `/peserta` |
| CAPTCHA | Ya | Tidak |
| Registrasi | Tidak ada | Ada |

## Troubleshooting

### Error: "Class 'App\Models\UserPeserta' not found"
Pastikan file `app/Models/UserPeserta.php` sudah dibuat dan namespace-nya benar.

### Error: "Table 'user_peserta' doesn't exist"
Jalankan migration: `php artisan migrate`

### Tidak bisa login
1. Cek apakah email dan password benar
2. Pastikan status user adalah 'active'
3. Cek di database apakah data user ada

### Redirect ke halaman yang salah setelah login
Pastikan menggunakan guard yang benar: `Auth::guard('peserta')`

## File-file Terkait

- **Migration:** `database/migrations/2025_10_24_130859_create_user_peserta_table.php`
- **Model:** `app/Models/UserPeserta.php`
- **Controller:** `app/Http/Controllers/DashboardPeserta/AuthController.php`
- **Config:** `config/auth.php`
- **Routes:** `routes/web.php`
- **Views:**
  - `resources/views/peserta-auth/layout-peserta.blade.php`
  - `resources/views/peserta-auth/login.blade.php`
  - `resources/views/peserta-auth/daftar.blade.php`
  - `resources/views/peserta-dashboard/dashboard.blade.php`
