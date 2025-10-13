# Storage Upload Troubleshooting Guide

## Problem: 403 Error when accessing uploaded images

### Checklist untuk Fix Error 403:

## 1. **Pastikan Symbolic Link Sudah Dibuat**
```bash
php artisan storage:link
```

Jika muncul error "The [public/storage] link already exists", hapus dulu:
```bash
# Windows
rmdir public\storage
# atau
del public\storage

# Lalu buat ulang
php artisan storage:link
```

## 2. **Cek Permission Folder (di Server Linux/Unix)**
```bash
# Set permission untuk storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (ganti www-data dengan user web server Anda)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## 3. **Cek di Windows (XAMPP/Laragon)**
- Pastikan folder `storage/app/public` ada
- Pastikan folder `public/storage` adalah symbolic link ke `storage/app/public`
- Jalankan command prompt/PowerShell sebagai Administrator untuk membuat symlink

## 4. **Test Storage Configuration**
Akses URL ini di browser:
```
http://your-domain.com/api/test-storage
```

Harusnya return JSON seperti ini:
```json
{
  "storage_path": "D:\\path\\to\\storage\\app\\public",
  "storage_exists": true,
  "storage_writable": true,
  "public_path": "D:\\path\\to\\public\\storage",
  "public_exists": true,
  "is_link": true,
  "link_target": "D:\\path\\to\\storage\\app\\public"
}
```

## 5. **Cek File .env**
Pastikan APP_URL sudah benar:
```env
APP_URL=http://localhost
# atau
APP_URL=http://your-domain.com
```

## 6. **Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 7. **Test Upload Manual**
Buat file test di `storage/app/public/test.txt` dengan isi "Hello World"

Lalu akses di browser:
```
http://your-domain.com/storage/test.txt
```

Jika bisa diakses, berarti storage link sudah benar.

## 8. **Cek .htaccess di public folder**
Pastikan ada rules untuk mengizinkan akses ke storage:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/storage/
    # ... other rules
</IfModule>
```

## 9. **Alternative: Gunakan Public Disk Langsung**
Jika masih error, ubah di controller:
```php
// Dari ini:
$path = $file->storeAs('profile_images', $filename, 'public');

// Ke ini (langsung ke public folder):
$file->move(public_path('uploads/profile_images'), $filename);
$user->profile_image = 'uploads/profile_images/' . $filename;
```

Dan di view:
```blade
<img src="{{ asset($user->profile_image) }}">
```

## 10. **Debug Upload**
Tambahkan di controller untuk debug:
```php
if ($request->hasFile('profile_image')) {
    $file = $request->file('profile_image');
    
    \Log::info('File uploaded:', [
        'original_name' => $file->getClientOriginalName(),
        'size' => $file->getSize(),
        'mime' => $file->getMimeType(),
        'is_valid' => $file->isValid(),
    ]);
}
```

Cek log di `storage/logs/laravel.log`

## Common Issues:

### Issue 1: Symbolic link tidak berfungsi di Windows
**Solution**: Jalankan Command Prompt/PowerShell sebagai Administrator

### Issue 2: Permission denied di Linux
**Solution**: Set permission 775 untuk storage folder

### Issue 3: File terupload tapi 403 saat diakses
**Solution**: Cek ownership folder (chown) dan SELinux settings

### Issue 4: Path salah di database
**Solution**: Pastikan path disimpan sebagai `profile_images/filename.jpg` bukan full path

### Issue 5: APP_URL salah
**Solution**: Update .env dan jalankan `php artisan config:clear`

