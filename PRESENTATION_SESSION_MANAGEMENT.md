# Presentation Session Management - Dynamic System

## 📌 Overview
Sistem manajemen sesi presentasi yang **dinamis** untuk Superadmin. Superadmin dapat mengatur peserta presentasi untuk setiap sesi melalui dashboard, tidak lagi hardcoded di controller.

## 🎯 Features

### ✅ **Dynamic Session Management**
- Superadmin dapat menambah/hapus peserta dari sesi
- Data sesi disimpan di database
- Auto-update di halaman penilaian juri

### ✅ **Select2 Integration**
- Dropdown dengan search untuk memilih BPKH/Produsen
- Data diambil dari table `bpkh_presentation_assesment` dan `produsen_presentation_assesment`
- Hanya menampilkan peserta yang belum terdaftar di sesi manapun

### ✅ **Session Completion Tracking**
- Button sesi otomatis hijau dengan centang jika sudah dinilai
- Per-user tracking (setiap juri punya progress sendiri)

---

## 🗂️ Database Structure

### **Table: `sesi_bpkh_presentasi`**
```sql
CREATE TABLE sesi_bpkh_presentasi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    session_name VARCHAR(255),      -- 'Sesi 1', 'Sesi 3', 'Sesi 5'
    respondent_id VARCHAR(255),     -- ID dari bpkh_presentation_assesment
    nama_bpkh VARCHAR(255),         -- Nama BPKH
    order INT DEFAULT 0,            -- Urutan dalam sesi
    is_active BOOLEAN DEFAULT TRUE, -- Status aktif
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX(session_name),
    INDEX(respondent_id)
);
```

### **Table: `sesi_produsen_presentasi`**
```sql
CREATE TABLE sesi_produsen_presentasi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    session_name VARCHAR(255),      -- 'Sesi 2', 'Sesi 4'
    respondent_id VARCHAR(255),     -- ID dari produsen_presentation_assesment
    nama_instansi VARCHAR(255),     -- Nama Instansi
    order INT DEFAULT 0,            -- Urutan dalam sesi
    is_active BOOLEAN DEFAULT TRUE, -- Status aktif
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX(session_name),
    INDEX(respondent_id)
);
```

---

## 📁 Files Structure

```
app/
├── Http/Controllers/dashboard/
│   ├── PresentationSessionController.php    ← NEW (Session Management)
│   ├── BpkhPresentationController.php       ← UPDATED (Dynamic sessions)
│   └── ProdusenPresentationController.php   ← UPDATED (Dynamic sessions)
├── Models/
│   ├── BpkhPresentationSession.php          ← NEW
│   └── ProdusenPresentationSession.php      ← NEW
database/
├── migrations/
│   ├── 2025_10_20_155631_create_sesi_bpkh_presentasi_table.php
│   └── 2025_10_20_155651_create_sesi_produsen_presentasi_table.php
resources/views/dashboard/
├── pages/presentation_session/
│   └── index.blade.php                      ← NEW (Management UI)
└── layouts/
    └── navigation.blade.php                 ← UPDATED (Add menu)
routes/
└── web.php                                  ← UPDATED (Add routes)
```

---

## 🔧 Implementation Details

### **1. Models**

#### **BpkhPresentationSession.php**
```php
class BpkhPresentationSession extends Model
{
    protected $table = 'sesi_bpkh_presentasi';
    
    protected $fillable = [
        'session_name',
        'respondent_id',
        'nama_bpkh',
        'order',
        'is_active'
    ];
    
    // Get all sessions grouped by session name
    public static function getGroupedSessions()
    {
        return self::where('is_active', true)
            ->orderBy('session_name')
            ->orderBy('order')
            ->get()
            ->groupBy('session_name');
    }
    
    // Get participants for a specific session
    public static function getSessionParticipants($sessionName)
    {
        return self::where('session_name', $sessionName)
            ->where('is_active', true)
            ->orderBy('order')
            ->pluck('nama_bpkh')
            ->toArray();
    }
}
```

### **2. Controller - Dynamic Sessions**

#### **Before (Hardcoded):**
```php
$sessions = [
    'Sesi 1' => [
        'BPKH Wilayah III Pontianak',
        'BPKH Wilayah VII Makassar',
        // ...
    ]
];
```

#### **After (Dynamic):**
```php
// Get from database
$sessionsGrouped = BpkhPresentationSession::getGroupedSessions();
$sessions = [];
foreach ($sessionsGrouped as $sessionName => $participants) {
    $sessions[$sessionName] = $participants->pluck('nama_bpkh')->toArray();
}
```

### **3. Routes**

```php
// Superadmin only
Route::middleware(['role:superadmin'])->group(function () {
    Route::get('/presentation-session', [PresentationSessionController::class, 'index'])
        ->name('dashboard.presentation-session.index');
    
    Route::post('/presentation-session/bpkh', [PresentationSessionController::class, 'storeBpkh'])
        ->name('dashboard.presentation-session.bpkh.store');
    
    Route::post('/presentation-session/produsen', [PresentationSessionController::class, 'storeProdusen'])
        ->name('dashboard.presentation-session.produsen.store');
    
    Route::delete('/presentation-session/bpkh/{id}', [PresentationSessionController::class, 'destroyBpkh'])
        ->name('dashboard.presentation-session.bpkh.destroy');
    
    Route::delete('/presentation-session/produsen/{id}', [PresentationSessionController::class, 'destroyProdusen'])
        ->name('dashboard.presentation-session.produsen.destroy');
});
```

---

## 🎨 User Interface

### **Navigation Menu (Superadmin Only)**
```blade
<li>
    <a href="{{ route('dashboard.presentation-session.index') }}" class="waves-effect">
        <i class="mdi mdi-calendar-clock"></i>
        <span>Manajemen Sesi Presentasi</span>
    </a>
</li>
```

### **Session Management Page**

**Layout:**
```
┌─────────────────────────────────────────────────────┐
│ Sesi Presentasi BPKH                                │
├─────────────────────────────────────────────────────┤
│ [Pilih Sesi ▼] [Pilih BPKH (Select2) ▼] [+ Tambah] │
├─────────────────────────────────────────────────────┤
│ ┌─────────┐  ┌─────────┐  ┌─────────┐              │
│ │ Sesi 1  │  │ Sesi 3  │  │ Sesi 5  │              │
│ ├─────────┤  ├─────────┤  ├─────────┤              │
│ │ • BPKH1 │  │ • BPKH5 │  │ • BPKH9 │              │
│ │ • BPKH2 │  │ • BPKH6 │  │ • BPKH10│              │
│ │ • BPKH3 │  │ • BPKH7 │  │ • BPKH11│              │
│ │ • BPKH4 │  │ • BPKH8 │  │ • BPKH12│              │
│ └─────────┘  └─────────┘  └─────────┘              │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ Sesi Presentasi Produsen DG                         │
├─────────────────────────────────────────────────────┤
│ [Pilih Sesi ▼] [Pilih Produsen ▼] [+ Tambah]       │
├─────────────────────────────────────────────────────┤
│ ┌────────────────────┐  ┌────────────────────┐     │
│ │ Sesi 2             │  │ Sesi 4             │     │
│ ├────────────────────┤  ├────────────────────┤     │
│ │ • Direktorat 1     │  │ • Direktorat 4     │     │
│ │ • Direktorat 2     │  │ • Direktorat 5     │     │
│ │ • Direktorat 3     │  │ • Direktorat 6     │     │
│ └────────────────────┘  └────────────────────┘     │
└─────────────────────────────────────────────────────┘
```

---

## 🚀 Usage Guide

### **For Superadmin:**

#### **1. Add Participant to Session**
1. Login sebagai Superadmin
2. Klik menu **"Manajemen Sesi Presentasi"**
3. Pilih **Nama Sesi** (dropdown)
4. Pilih **BPKH/Produsen** dari dropdown Select2
5. Klik **"Tambah"**
6. Peserta akan muncul di card sesi yang dipilih

#### **2. Remove Participant from Session**
1. Cari peserta di card sesi
2. Klik tombol **Delete** (🗑️) di sebelah nama
3. Confirm deletion
4. Peserta akan dihapus dari sesi

#### **3. View Available Participants**
- Dropdown Select2 hanya menampilkan peserta yang **belum terdaftar** di sesi manapun
- Jika peserta sudah di sesi lain, tidak akan muncul di dropdown

---

### **For Juri:**

#### **1. View Sessions**
1. Login sebagai Juri
2. Buka halaman **Penilaian Presentasi BPKH/Produsen**
3. Lihat button sesi di atas tabel
4. Button menampilkan sesi yang sudah dikonfigurasi oleh Superadmin

#### **2. Auto-Check Participants**
1. Klik button **Sesi** (contoh: "Sesi 1")
2. Checkbox peserta di sesi tersebut akan **auto-checked**
3. Button "Penilaian Kolektif" muncul dengan jumlah tercentang

#### **3. Session Completion Status**
- Button **Abu-abu** = Belum dinilai semua peserta
- Button **Hijau + Centang ✓** = Sudah dinilai semua peserta di sesi

---

## 🔄 Data Flow

### **Add Participant Flow:**
```
Superadmin Input
    ↓
PresentationSessionController::storeBpkh()
    ↓
Validate: session_name, respondent_id
    ↓
Get participant data from BpkhPresentationAssesment
    ↓
Check if already exists in any session
    ↓
Get max order for this session
    ↓
Create new record in sesi_bpkh_presentasi
    ↓
Redirect back with success message
```

### **Juri View Sessions Flow:**
```
Juri opens presentation page
    ↓
BpkhPresentationController::index()
    ↓
BpkhPresentationSession::getGroupedSessions()
    ↓
Group participants by session_name
    ↓
Convert to array format
    ↓
Check completion status per user
    ↓
Pass to view with $sessions & $completedSessions
    ↓
Render buttons with dynamic data
```

---

## ✅ Validation Rules

### **Add Participant:**
- `session_name`: Required, string
- `respondent_id`: Required, string, must exist in assessment table
- Participant cannot be in multiple sessions simultaneously
- Auto-increment order within session

### **Delete Participant:**
- Must be existing record
- Soft delete or hard delete (currently hard delete)

---

## 🎯 Benefits

### **Before (Hardcoded):**
- ❌ Harus edit code untuk ubah sesi
- ❌ Perlu deploy ulang setiap perubahan
- ❌ Tidak fleksibel
- ❌ Rawan error saat edit manual

### **After (Dynamic):**
- ✅ Superadmin atur via UI
- ✅ No code changes needed
- ✅ Real-time updates
- ✅ User-friendly interface
- ✅ Select2 dengan search
- ✅ Validation built-in
- ✅ Audit trail (timestamps)

---

## 📊 Example Data

### **sesi_bpkh_presentasi:**
| id | session_name | respondent_id | nama_bpkh | order | is_active |
|----|--------------|---------------|-----------|-------|-----------|
| 1 | Sesi 1 | RESP001 | BPKH Wilayah III Pontianak | 1 | true |
| 2 | Sesi 1 | RESP002 | BPKH Wilayah VII Makassar | 2 | true |
| 3 | Sesi 3 | RESP003 | BPKH Wilayah V Banjarbaru | 1 | true |

### **sesi_produsen_presentasi:**
| id | session_name | respondent_id | nama_instansi | order | is_active |
|----|--------------|---------------|---------------|-------|-----------|
| 1 | Sesi 2 | PROD001 | Direktorat Penggunaan Kawasan Hutan | 1 | true |
| 2 | Sesi 2 | PROD002 | Direktorat Perencanaan... | 2 | true |

---

## 🔐 Security

- **Route Protection**: Middleware `role:superadmin`
- **Validation**: Server-side validation for all inputs
- **CSRF Protection**: Laravel CSRF tokens
- **SQL Injection**: Eloquent ORM protection
- **XSS Protection**: Blade escaping

---

## 🧪 Testing

### **Manual Testing:**

1. **Add BPKH to Sesi 1**
   - Select "Sesi 1"
   - Select "BPKH Wilayah III Pontianak"
   - Click "Tambah"
   - ✅ Should appear in Sesi 1 card

2. **Try Add Same BPKH Again**
   - Select any session
   - Try to select same BPKH
   - ✅ Should not appear in dropdown (already assigned)

3. **Delete Participant**
   - Click delete button
   - Confirm
   - ✅ Should be removed from card
   - ✅ Should appear in dropdown again

4. **Juri View**
   - Login as Juri
   - Open presentation page
   - ✅ Should see session buttons
   - Click "Sesi 1"
   - ✅ Should auto-check participants

---

## 📝 Future Enhancements

- [ ] Drag & drop untuk reorder participants
- [ ] Bulk import dari Excel
- [ ] Export session list to PDF
- [ ] Session scheduling (date/time)
- [ ] Email notification to participants
- [ ] Session history/audit log
- [ ] Clone session feature

---

## ✅ Completion Checklist

- ✅ Migration created & run
- ✅ Models created with helper methods
- ✅ Controller with CRUD operations
- ✅ Routes registered (superadmin only)
- ✅ Navigation menu updated
- ✅ View with Select2 integration
- ✅ Dynamic session loading in presentation pages
- ✅ Session completion tracking
- ✅ Documentation complete

---

**Last Updated**: 2025-10-20  
**Version**: 1.0  
**Status**: ✅ Ready to Use
