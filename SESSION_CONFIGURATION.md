# Session Configuration - Auto Logout Setting

## 📌 Overview
Aplikasi SIGAP Awards menggunakan session untuk autentikasi user. Session timeout sudah diatur menjadi **12 jam** sebelum auto logout.

## ⚙️ Current Configuration

### Session Settings:
- **Session Driver**: `database`
- **Session Lifetime**: `720 minutes` (12 jam)
- **Expire on Close**: `false` (session tetap aktif meski browser ditutup)
- **Session Encryption**: `false`

## 🔧 Cara Update Session Timeout

### 1. Edit File `.env`
Buka file `.env` di root project dan ubah nilai `SESSION_LIFETIME`:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=720    # 720 menit = 12 jam
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

### 2. Contoh Durasi Session Lainnya:

| Durasi | Nilai (menit) | Setting |
|--------|---------------|---------|
| 30 menit | 30 | `SESSION_LIFETIME=30` |
| 1 jam | 60 | `SESSION_LIFETIME=60` |
| 2 jam | 120 | `SESSION_LIFETIME=120` |
| 6 jam | 360 | `SESSION_LIFETIME=360` |
| **12 jam** | **720** | `SESSION_LIFETIME=720` ✅ |
| 24 jam | 1440 | `SESSION_LIFETIME=1440` |
| 1 minggu | 10080 | `SESSION_LIFETIME=10080` |

### 3. Clear Cache (Setelah Update)
Setelah mengubah `.env`, jalankan perintah berikut untuk apply changes:

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

Atau jika pakai composer:
```bash
composer dump-autoload
```

## 📁 File Konfigurasi

### 1. `config/session.php` (Main Config)
```php
'lifetime' => (int) env('SESSION_LIFETIME', 120),
```
Default: 120 menit (2 jam), override dengan `.env`

### 2. `.env` (Environment Variables)
```env
SESSION_LIFETIME=720
```

### 3. Session Storage
Session disimpan di **database** (table `sessions`):
- Driver: `database`
- Table: `sessions`
- Connection: default MySQL

## 🔐 Fitur Session

### Auto Logout
- User akan **auto logout** setelah **12 jam** tidak ada aktivitas
- Setiap request ke server akan **refresh session timeout**
- Idle time dihitung dari request terakhir

### Keep Alive
Session akan tetap aktif selama:
- ✅ User aktif browsing di aplikasi
- ✅ User melakukan action (klik, submit form, dll)
- ✅ Browser masih terbuka dan user tidak idle

Session akan expire jika:
- ❌ User tidak ada aktivitas selama 12 jam berturut-turut
- ❌ User klik "Logout" manual
- ❌ Session di-clear dari server

## 🗂️ Session Database Table

Struktur table `sessions`:
```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INT NOT NULL
);
```

## ⚡ Best Practices

1. **Production Environment**:
   - Set `SESSION_DRIVER=database` untuk stability
   - Enable `SESSION_ENCRYPT=true` untuk security
   - Set `SESSION_SECURE_COOKIE=true` jika pakai HTTPS

2. **Development Environment**:
   - Bisa pakai `SESSION_DRIVER=file` untuk faster testing
   - Set lifetime lebih pendek untuk testing auto logout

3. **Maintenance**:
   - Laravel otomatis cleanup old sessions via `lottery` system
   - Default: 2% chance per request untuk cleanup
   - Bisa manual cleanup: `php artisan session:clear`

## 🔍 Troubleshooting

### Issue: Session masih expire terlalu cepat
**Solusi**:
1. Cek nilai `SESSION_LIFETIME` di `.env`
2. Run `php artisan config:cache`
3. Clear browser cookies
4. Test di incognito window

### Issue: User logout otomatis padahal masih aktif
**Kemungkinan penyebab**:
- Browser blocking cookies
- Multiple tabs dengan session berbeda
- Server time/timezone tidak sync

**Solusi**:
1. Check browser cookie settings
2. Ensure `SESSION_DOMAIN` correct
3. Sync server timezone

### Issue: Session tidak persist setelah browser close
**Solusi**:
1. Set `SESSION_EXPIRE_ON_CLOSE=false` di `.env`
2. Check browser "Clear cookies on exit" setting

## 📝 Notes

- Session timeout = **12 jam (720 menit)** sejak aktivitas terakhir
- Bukan 12 jam sejak login, tapi **idle time** 12 jam
- Session akan di-refresh setiap ada request
- Cookie name: `sigap-awards-session`

## ✅ Current Status

✅ Session lifetime sudah diset ke **720 menit (12 jam)**  
✅ Session driver: **database**  
✅ Expire on close: **disabled**  
✅ Ready to use!

---

**Last Updated**: 2025-01-20  
**Version**: 1.0
