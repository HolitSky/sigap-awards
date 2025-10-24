# Panduan Upload Foto Profil Peserta

## Overview
Sistem upload foto profil peserta dengan fitur:
- Custom styled file input (tidak putih/default browser)
- Preview foto sebelum upload
- Validasi client-side dan server-side
- Foto wajib diupload
- Maksimal ukuran 1.5MB (ditampilkan sebagai 1MB di UI)

## Fitur Upload Foto

### 1. Custom File Input
**Masalah Lama:** Input file default browser berwarna putih dan tidak match dengan theme gelap

**Solusi Baru:**
- Custom button dengan styling glassmorphism
- Icon upload yang jelas
- Tampilan nama file yang dipilih
- Hover effect yang smooth

### 2. Preview Foto
**Fitur:**
- Preview otomatis setelah pilih foto
- Ukuran preview max 200x200px
- Border rounded dengan glassmorphism effect
- Tombol hapus untuk remove preview

### 3. Validasi Client-Side (JavaScript)
**Validasi:**
- ✅ Ukuran file max 1.5MB (1,572,864 bytes)
- ✅ Format file: JPG, JPEG, PNG
- ✅ Alert jika validasi gagal
- ✅ Auto clear input jika tidak valid

### 4. Validasi Server-Side (Laravel)
**Validasi:**
- ✅ Required (wajib upload)
- ✅ Image file only
- ✅ Mimes: jpeg, jpg, png
- ✅ Max size: 1536KB (1.5MB)

## Implementasi

### Frontend (View)

#### Custom Styling:
```css
.custom-file-upload {
    display: inline-block;
    padding: 8px 16px;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 4px;
    color: white;
    transition: all 0.3s;
}

.preview-container img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    border: 2px solid rgba(255, 255, 255, 0.3);
}
```

#### HTML Structure:
```html
<div class="file-input-wrapper">
    <label for="foto" class="custom-file-upload">
        <i class="mdi mdi-upload"></i> Pilih Foto
    </label>
    <input type="file" id="foto" name="foto" 
           accept="image/jpeg,image/jpg,image/png" 
           required
           onchange="previewImage(event)">
    <div class="file-name-display" id="fileName">
        Belum ada file dipilih
    </div>
</div>
<small class="text-white-50">Format: JPG, JPEG, PNG. Maksimal 1MB</small>

<div class="preview-container" id="previewContainer">
    <img id="imagePreview" src="" alt="Preview">
    <br>
    <button type="button" class="remove-preview" onclick="removeImage()">
        <i class="mdi mdi-close"></i> Hapus
    </button>
</div>
```

#### JavaScript Functions:
```javascript
function previewImage(event) {
    const file = event.target.files[0];
    const maxSize = 1572864; // 1.5MB
    
    if (file) {
        // Validasi ukuran
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 1.5MB');
            event.target.value = '';
            return;
        }
        
        // Validasi tipe
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung!');
            event.target.value = '';
            return;
        }
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('previewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('foto').value = '';
    document.getElementById('fileName').textContent = 'Belum ada file dipilih';
    document.getElementById('previewContainer').style.display = 'none';
}
```

### Backend (Controller)

#### Validasi:
```php
$validator = Validator::make($request->all(), [
    'foto' => 'required|image|mimes:jpeg,jpg,png|max:1536', // 1.5MB
], [
    'foto.required' => 'Foto profil wajib diupload',
    'foto.image' => 'File harus berupa gambar',
    'foto.mimes' => 'Format foto harus jpeg, jpg, atau png',
    'foto.max' => 'Ukuran foto maksimal 1.5MB',
]);
```

#### Upload & Sanitasi:
```php
// Upload foto
$fotoPath = null;
if ($request->hasFile('foto')) {
    // Sanitasi: resize jika terlalu besar, compress
    $image = Image::make($request->file('foto'));
    
    // Resize jika width > 800px
    if ($image->width() > 800) {
        $image->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
    
    // Save dengan quality 80%
    $filename = time() . '_' . uniqid() . '.jpg';
    $path = 'peserta-photos/' . $filename;
    $image->save(storage_path('app/public/' . $path), 80);
    
    $fotoPath = $path;
}
```

**Note:** Untuk implementasi resize & compress, install package:
```bash
composer require intervention/image
```

## User Experience

### Flow Upload:
1. User klik button "Pilih Foto"
2. Browser file picker muncul
3. User pilih foto
4. **Validasi client-side:**
   - Cek ukuran < 1.5MB
   - Cek format JPG/JPEG/PNG
   - Jika gagal → Alert + clear input
5. **Preview muncul:**
   - Tampil foto yang dipilih
   - Tampil nama file
   - Tampil tombol "Hapus"
6. User submit form
7. **Validasi server-side:**
   - Required check
   - Size check (max 1536KB)
   - Format check
   - Jika gagal → Error message
8. **Upload success:**
   - Foto disimpan ke `storage/app/public/peserta-photos/`
   - Path disimpan ke database
   - Redirect ke dashboard

### Error Handling:

#### Client-Side Errors:
- **File terlalu besar:** "Ukuran file terlalu besar! Maksimal 1.5MB"
- **Format salah:** "Format file tidak didukung! Gunakan JPG, JPEG, atau PNG"

#### Server-Side Errors:
- **Tidak upload:** "Foto profil wajib diupload"
- **Bukan gambar:** "File harus berupa gambar"
- **Format salah:** "Format foto harus jpeg, jpg, atau png"
- **Ukuran besar:** "Ukuran foto maksimal 1.5MB"

## Testing

### Test Cases:

1. **Upload foto valid (< 1.5MB, JPG)**
   - ✅ Preview muncul
   - ✅ Submit berhasil
   - ✅ Foto tersimpan

2. **Upload foto terlalu besar (> 1.5MB)**
   - ✅ Alert muncul
   - ✅ Input di-clear
   - ✅ Preview tidak muncul

3. **Upload file bukan gambar (PDF, DOCX)**
   - ✅ Alert muncul
   - ✅ Input di-clear
   - ✅ Preview tidak muncul

4. **Submit tanpa upload foto**
   - ✅ HTML5 validation: "Please select a file"
   - ✅ Server validation: "Foto profil wajib diupload"

5. **Remove preview**
   - ✅ Klik tombol "Hapus"
   - ✅ Preview hilang
   - ✅ Input di-clear
   - ✅ Nama file reset

## Ukuran File

### Kenapa 1.5MB di backend tapi tampil 1MB di UI?

**Alasan:**
- **Backend (1.5MB):** Memberikan buffer untuk user
- **Frontend (1MB):** Lebih user-friendly, angka bulat
- **Validasi client:** Tetap 1.5MB untuk konsistensi dengan backend

**Best Practice:**
- Tampilkan angka yang mudah diingat (1MB)
- Backend sedikit lebih besar untuk toleransi
- Validasi client sama dengan backend untuk konsistensi

## Optimasi (Optional)

### Image Compression:
Jika ingin auto-compress foto yang diupload:

```php
use Intervention\Image\Facades\Image;

$image = Image::make($request->file('foto'));

// Resize jika terlalu besar
if ($image->width() > 800) {
    $image->resize(800, null, function ($constraint) {
        $constraint->aspectRatio();
    });
}

// Compress dengan quality 80%
$filename = time() . '_' . uniqid() . '.jpg';
$path = 'peserta-photos/' . $filename;
$image->save(storage_path('app/public/' . $path), 80);
```

### Lazy Loading:
Untuk tampilan foto di dashboard:

```html
<img src="{{ asset('storage/' . $user->foto) }}" 
     alt="Foto Profil" 
     loading="lazy">
```

## Troubleshooting

### Preview tidak muncul
- Cek console browser (F12)
- Pastikan JavaScript tidak error
- Cek ID element sudah benar

### Upload gagal
- Cek permission folder `storage/app/public/peserta-photos/`
- Cek `php.ini`: `upload_max_filesize` dan `post_max_size`
- Cek Laravel config: `config/filesystems.php`

### Foto tidak tampil di dashboard
- Jalankan: `php artisan storage:link`
- Cek path foto di database
- Cek file ada di `storage/app/public/peserta-photos/`
