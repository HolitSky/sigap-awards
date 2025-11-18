# Panduan Registrasi Peserta dengan Kategori BPKH/Produsen

## Overview
Sistem registrasi peserta telah diupdate dengan fitur:
- Upload foto profil
- Pilihan kategori (BPKH atau Produsen)
- Dropdown dinamis untuk wilayah BPKH atau unit Produsen
- Validasi lengkap

## Struktur Database

### Tabel: `user_peserta`
```sql
- id (primary key)
- name (string)
- email (string, unique)
- password (string, hashed)
- phone (string, nullable)
- institution (string, nullable)
- foto (string, nullable) - Path ke file foto
- kategori (enum: 'bpkh', 'produsen')
- bpkh_id (foreign key ke bpkh_list, nullable)
- produsen_id (foreign key ke produsen_list, nullable)
- status (enum: 'active', 'inactive')
- remember_token
- timestamps
```

### Tabel: `bpkh_list`
```sql
- id (primary key)
- nama_wilayah (string) - Contoh: "BPKH Wilayah I Medan"
- kode_wilayah (string, nullable) - Contoh: "I", "II", "III"
- timestamps
```

### Tabel: `produsen_list`
```sql
- id (primary key)
- nama_unit (string) - Contoh: "Direktorat RPKHPWPH"
- timestamps
```

## Data List

### BPKH Wilayah (22 wilayah)
1. BPKH Wilayah I Medan
2. BPKH Wilayah II Palembang
3. BPKH Wilayah III Pontianak
4. BPKH Wilayah IV Samarinda
5. BPKH Wilayah V Banjarbaru
6. BPKH Wilayah VI Manado
7. BPKH Wilayah VII Makassar
8. BPKH Wilayah VIII Denpasar
9. BPKH Wilayah IX Ambon
10. BPKH Wilayah X Jayapura
11. BPKH Wilayah XI Yogyakarta
12. BPKH Wilayah XII Tanjungpinang
13. BPKH Wilayah XIII Pangkalpinang
14. BPKH Wilayah XIV Kupang
15. BPKH Wilayah XV Gorontalo
16. BPKH Wilayah XVI Palu
17. BPKH Wilayah XVII Manokwari
18. BPKH Wilayah XVIII Banda Aceh
19. BPKH Wilayah XIX Pekanbaru
20. BPKH Wilayah XX Bandar Lampung
21. BPKH Wilayah XXI Palangkaraya
22. BPKH Wilayah XXII Kendari

### Unit Produsen (22 unit)
1. Pusat Pengembangan Hutan Berkelanjutan
2. Direktorat RPKHPWPH
3. Direktorat Pengukuhan KH
4. Direktorat Penggunaan KH
5. Direktorat Perencanaan Konservasi
6. Direktorat Konservasi Kawasan
7. Direktorat Konservasi Spesies dan Genetik
8. Direktorat Pemulihan Ekosistem dan Bina Areal Preservasi
9. Direktorat Pemanfaatan Jasa Lingkungan
10. Direktorat PEPDAS
11. Direktorat Teknik Konservasi Tanah dan Reklamasi Hutan
12. Direktorat Penghijauan dan Perbenihan Tanaman Hutan
13. Direktorat RH
14. Direktorat Rehabilitasi Mangrove
15. Direktorat BRPH
16. Direktorat BUPH
17. Direktorat PUPH
18. Direktorat BPPHH
19. Direktorat PKPS
20. Direktorat PKTHA
21. Direktorat PPSA dan Keperdataan Kehutanan
22. Direktorat Pengendalian Kebakaran Hutan

## Cara Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

Migration akan membuat 3 tabel:
- `bpkh_list` (dibuat pertama)
- `produsen_list` (dibuat kedua)
- `user_peserta` (dibuat terakhir, dengan foreign keys)

### 2. Jalankan Seeder
```bash
php artisan db:seed --class=BpkhListSeeder
php artisan db:seed --class=ProdusenListSeeder
```

Atau jalankan semua seeder sekaligus dengan menambahkan ke `DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        BpkhListSeeder::class,
        ProdusenListSeeder::class,
    ]);
}
```

Lalu jalankan:
```bash
php artisan db:seed
```

### 3. Buat Symbolic Link untuk Storage
```bash
php artisan storage:link
```

Ini diperlukan agar foto yang diupload bisa diakses dari public folder.

## Form Registrasi

### Field yang Tersedia:
1. **Nama Lengkap** (required)
2. **Email** (required, unique)
3. **No. Telepon** (optional)
4. **Instansi/Perusahaan** (optional)
5. **Foto Profil** (optional, max 2MB, format: JPG/JPEG/PNG)
6. **Kategori** (required, pilihan: BPKH atau Produsen)
7. **Wilayah BPKH** (required jika kategori = BPKH)
8. **Unit Produsen** (required jika kategori = Produsen)
9. **Password** (required, min 6 karakter)
10. **Konfirmasi Password** (required)

### Validasi:
- Email harus unique (tidak boleh duplikat)
- Password minimal 6 karakter
- Password confirmation harus sama dengan password
- Foto maksimal 2MB dengan format JPG, JPEG, atau PNG
- Jika kategori BPKH dipilih, maka Wilayah BPKH wajib diisi
- Jika kategori Produsen dipilih, maka Unit Produsen wajib diisi

### Fitur Dinamis:
- Dropdown wilayah/unit akan muncul otomatis sesuai kategori yang dipilih
- Jika pilih BPKH → muncul dropdown Wilayah BPKH
- Jika pilih Produsen → muncul dropdown Unit Produsen

## Upload Foto

### Spesifikasi:
- **Format**: JPG, JPEG, PNG
- **Ukuran Maksimal**: 2MB
- **Storage Path**: `storage/app/public/peserta-photos/`
- **Public URL**: `storage/peserta-photos/filename.jpg`

### Cara Akses Foto:
```blade
@if($user->foto)
    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto">
@endif
```

## Model Relationships

### UserPeserta Model
```php
// Relasi ke BPKH
public function bpkh()
{
    return $this->belongsTo(BpkhList::class, 'bpkh_id');
}

// Relasi ke Produsen
public function produsen()
{
    return $this->belongsTo(ProdusenList::class, 'produsen_id');
}

// Accessor untuk nama kategori
public function getKategoriNameAttribute()
{
    if ($this->kategori === 'bpkh') {
        return $this->bpkh ? $this->bpkh->nama_wilayah : '-';
    } elseif ($this->kategori === 'produsen') {
        return $this->produsen ? $this->produsen->nama_unit : '-';
    }
    return '-';
}
```

### Cara Menggunakan:
```php
$user = UserPeserta::find(1);

// Get kategori
echo $user->kategori; // 'bpkh' atau 'produsen'

// Get nama wilayah/unit
echo $user->kategori_name; // "BPKH Wilayah I Medan" atau "Direktorat RPKHPWPH"

// Get relasi langsung
if ($user->kategori === 'bpkh') {
    echo $user->bpkh->nama_wilayah;
} else {
    echo $user->produsen->nama_unit;
}
```

## Controller Logic

### Register Method
```php
public function register(Request $request)
{
    // Validasi
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:user_peserta,email',
        'password' => 'required|string|min:6|confirmed',
        'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        'kategori' => 'required|in:bpkh,produsen',
        'bpkh_id' => 'required_if:kategori,bpkh|exists:bpkh_list,id',
        'produsen_id' => 'required_if:kategori,produsen|exists:produsen_list,id',
    ]);

    // Handle foto upload
    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('peserta-photos', 'public');
    }

    // Set bpkh_id or produsen_id based on kategori
    if ($request->kategori === 'bpkh') {
        $data['bpkh_id'] = $request->bpkh_id;
        $data['produsen_id'] = null;
    } else {
        $data['produsen_id'] = $request->produsen_id;
        $data['bpkh_id'] = null;
    }

    $user = UserPeserta::create($data);
    
    // Auto login
    Auth::guard('peserta')->login($user);
    
    return redirect()->route('peserta.dashboard');
}
```

## Dashboard Display

Dashboard akan menampilkan:
- **Foto Profil** (jika ada, atau icon default)
- **Nama Lengkap**
- **Email**
- **No. Telepon**
- **Instansi**
- **Kategori** (badge BPKH atau Produsen)
- **Wilayah/Unit** (sesuai kategori)
- **Status** (badge Active/Inactive)
- **Tombol Logout**

## File-file yang Dibuat/Diupdate

### Migrations:
- `2025_10_24_130857_create_produsen_list_table.php`
- `2025_10_24_130858_create_bpkh_list_table.php`
- `2025_10_24_130859_create_user_peserta_table.php` (updated)

### Seeders:
- `BpkhListSeeder.php`
- `ProdusenListSeeder.php`

### Models:
- `UserPeserta.php` (updated)
- `BpkhList.php` (new)
- `ProdusenList.php` (new)

### Controllers:
- `DashboardPeserta/AuthController.php` (updated)

### Views:
- `peserta-auth/daftar.blade.php` (updated)
- `peserta-dashboard/dashboard.blade.php` (updated)

## Testing

### 1. Test Registrasi BPKH:
1. Buka `/peserta/daftar`
2. Isi form dengan data lengkap
3. Pilih kategori "BPKH"
4. Pilih salah satu wilayah BPKH
5. Upload foto (optional)
6. Submit form
7. Cek apakah redirect ke dashboard
8. Cek apakah data tersimpan dengan benar

### 2. Test Registrasi Produsen:
1. Buka `/peserta/daftar`
2. Isi form dengan data lengkap
3. Pilih kategori "Produsen"
4. Pilih salah satu unit Produsen
5. Upload foto (optional)
6. Submit form
7. Cek apakah redirect ke dashboard
8. Cek apakah data tersimpan dengan benar

### 3. Test Validasi:
- Email duplikat → harus error
- Password < 6 karakter → harus error
- Password tidak match → harus error
- Foto > 2MB → harus error
- Kategori BPKH tanpa pilih wilayah → harus error
- Kategori Produsen tanpa pilih unit → harus error

## Troubleshooting

### Foto tidak muncul
- Pastikan sudah jalankan `php artisan storage:link`
- Cek permission folder `storage/app/public/peserta-photos`
- Cek apakah file ada di `storage/app/public/peserta-photos/`

### Dropdown tidak muncul
- Cek JavaScript di browser console
- Pastikan jQuery sudah loaded
- Cek apakah element ID sudah benar

### Data list kosong
- Pastikan sudah jalankan seeder
- Cek di database apakah data sudah ada
- Query: `SELECT * FROM bpkh_list` dan `SELECT * FROM produsen_list`

### Foreign key error saat migrate
- Pastikan urutan migration benar (list table dulu, baru user_peserta)
- Atau hapus foreign key constraint sementara
