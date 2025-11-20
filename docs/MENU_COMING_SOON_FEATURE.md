# Menu Coming Soon Feature

## Overview
Fitur ini menambahkan tipe menu baru "Coming Soon" yang akan menampilkan modal informasi ketika menu diklik, memberitahu user bahwa fitur sedang dalam tahap persiapan.

## Implementasi

### 1. Dashboard CMS - Menu Choice Management

#### File yang Dimodifikasi:
- `resources/views/dashboard/pages/cms/menu-choices/modals.blade.php`
- `resources/views/dashboard/pages/cms/menu-choices/scripts.blade.php`

#### Perubahan:
- Menambahkan opsi **"Coming Soon"** pada dropdown tipe menu
- Ketika tipe "Coming Soon" dipilih:
  - Field "Link URL" akan disembunyikan
  - Link otomatis diset ke `javascript:void(0)`
  - Tidak ada sub-menu yang bisa ditambahkan

### 2. Landing Page - Display & Modal

#### File yang Dimodifikasi:
- `resources/views/landing/pages/home/partials/box-form-choice.blade.php`

#### Perubahan:
1. **Modal Baru**: Ditambahkan modal "Coming Soon" dengan:
   - Icon ⏳
   - Judul "Sedang Disiapkan"
   - Pesan informatif bahwa fitur sedang dalam persiapan
   - Tombol "Tutup"

2. **Handler Menu Items**: 
   - Menu dengan tipe `coming_soon` akan memanggil fungsi `showComingSoonModal()`
   - Berlaku untuk mode "Direct Display" dan "Modal Menu"

3. **JavaScript Functions**:
   - `showComingSoonModal()` - Menampilkan modal
   - `hideComingSoonModal()` - Menyembunyikan modal
   - Event listeners untuk close button, click outside, dan ESC key

## Cara Penggunaan

### Di Dashboard CMS:

1. Buka **Dashboard > CMS > Menu Choices**
2. Klik **"Tambah Menu Choice"** atau edit yang sudah ada
3. Saat menambah menu item:
   - Isi **Judul Menu** (contoh: "Upload Video")
   - Pilih **Tipe**: "Coming Soon"
   - Field Link URL akan otomatis tersembunyi
   - Isi **Icon** (opsional, contoh: 🎥)
4. Simpan

### Di Landing Page:

Ketika user mengklik menu dengan tipe "Coming Soon":
- Modal akan muncul dengan pesan:
  ```
  ⏳ Sedang Disiapkan
  
  Fitur ini sedang dalam tahap persiapan dan akan segera tersedia.
  
  Mohon ditunggu untuk informasi lebih lanjut. Terima kasih! 🙏
  ```

## Tipe Menu yang Tersedia

1. **Direct Link** - Link langsung ke URL
2. **Modal (Sub-menu)** - Membuka modal dengan pilihan sub-menu
3. **Coming Soon** - Menampilkan modal "Sedang Disiapkan" ✨ (Baru)

## Technical Details

### Data Structure
```json
{
  "title": "Upload Video",
  "type": "coming_soon",
  "icon": "🎥",
  "link": "javascript:void(0)"
}
```

### Modal Styling
Modal menggunakan class yang sama dengan modal lainnya (`modal-overlay`, `modal-content`) untuk konsistensi UI.

### Event Handling
- Click handler: `onclick="showComingSoonModal()"`
- Close methods: Button click, outside click, ESC key
- Smooth transition dengan fade in/out effect

## Benefits

✅ User-friendly: Memberitahu user bahwa fitur sedang dikembangkan  
✅ Flexible: Admin bisa dengan mudah mengubah menu dari "Coming Soon" ke tipe lain  
✅ Consistent: Menggunakan modal pattern yang sama dengan fitur lain  
✅ No broken links: Tidak ada link kosong atau error 404  

## Future Enhancements

- [ ] Custom message per menu item
- [ ] Estimated release date display
- [ ] Email notification subscription
- [ ] Progress indicator

---
**Created**: 2025-01-20  
**Version**: 1.0
