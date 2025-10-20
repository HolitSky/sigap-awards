# Dynamic Session Numbers - Fully Flexible System

## 📌 Overview
Sistem penomoran sesi presentasi yang **sepenuhnya dinamis**. Superadmin dapat menambah sesi dengan nomor berapa saja (1, 2, 3, ... 100, dst) dan menentukan apakah sesi tersebut untuk BPKH atau Produsen.

## 🎯 Problem Solved

### **Before (Hardcoded):**
```php
// Fixed sessions in code
'Sesi 1' => [...], // BPKH only
'Sesi 2' => [...], // Produsen only
'Sesi 3' => [...], // BPKH only
'Sesi 4' => [...], // Produsen only
'Sesi 5' => [...], // BPKH only
```
❌ Cannot add Sesi 6, 7, 8, etc without code changes  
❌ Cannot change which type uses which number  
❌ Inflexible and requires developer intervention

### **After (Dynamic):**
```
Superadmin can create:
- Sesi 1 (BPKH)
- Sesi 2 (Produsen)
- Sesi 3 (BPKH)
- Sesi 6 (BPKH)      ← New!
- Sesi 7 (Produsen)  ← New!
- Sesi 10 (BPKH)     ← New!
- Any number!
```
✅ Fully flexible  
✅ No code changes needed  
✅ Superadmin controls everything

---

## 🗂️ Database Structure

### **New Table: `presentation_sessions_config`**
```sql
CREATE TABLE presentation_sessions_config (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    session_name VARCHAR(255),           -- 'Sesi 1', 'Sesi 2', etc.
    session_number INT,                  -- 1, 2, 3, 4, 5, 6, 7, etc.
    session_type ENUM('bpkh', 'produsen'), -- Type of session
    is_active BOOLEAN DEFAULT TRUE,      -- Active status
    order INT DEFAULT 0,                 -- Display order
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    UNIQUE(session_number, session_type), -- Prevent duplicate numbers per type
    INDEX(session_type),
    INDEX(is_active)
);
```

### **Example Data:**
| id | session_name | session_number | session_type | is_active | order |
|----|--------------|----------------|--------------|-----------|-------|
| 1 | Sesi 1 | 1 | bpkh | 1 | 1 |
| 2 | Sesi 3 | 3 | bpkh | 1 | 2 |
| 3 | Sesi 5 | 5 | bpkh | 1 | 3 |
| 4 | Sesi 6 | 6 | bpkh | 1 | 4 |
| 5 | Sesi 2 | 2 | produsen | 1 | 1 |
| 6 | Sesi 4 | 4 | produsen | 1 | 2 |
| 7 | Sesi 7 | 7 | produsen | 1 | 3 |

---

## 📁 Files Created/Modified

### **New Files:**
1. ✅ `database/migrations/2025_10_21_021155_create_presentation_sessions_config_table.php`
2. ✅ `app/Models/PresentationSessionConfig.php`
3. ✅ `database/seeders/PresentationSessionConfigSeeder.php`
4. ✅ `DYNAMIC_SESSION_NUMBERS.md` (this file)

### **Modified Files:**
1. ✅ `app/Http/Controllers/dashboard/PresentationSessionController.php`
   - Added `storeSessionConfig()` method
   - Added `destroySessionConfig()` method
   - Updated `index()` to pass session configs

2. ✅ `app/Http/Controllers/dashboard/BpkhPresentationController.php`
   - Added `PresentationSessionConfig` import

3. ✅ `app/Http/Controllers/dashboard/ProdusenPresentationController.php`
   - Added `PresentationSessionConfig` import

4. ✅ `routes/web.php`
   - Added config store route
   - Added config destroy route

5. ✅ `resources/views/dashboard/pages/presentation_session/index.blade.php`
   - Dynamic session dropdowns
   - Dynamic session cards
   - Add session modals
   - Delete session buttons
   - SweetAlert2 integration

---

## 🚀 Features

### **1. Add New Session**
Superadmin dapat menambah sesi baru dengan nomor berapa saja:

**UI:**
```
[Tambah Sesi Baru] button
  ↓
Modal opens:
  - Nomor Sesi: [6] (input number)
  - Type: BPKH or Produsen (hidden, determined by which button clicked)
  ↓
[Tambah Sesi] button
  ↓
Creates "Sesi 6" for BPKH or Produsen
```

**Backend Logic:**
```php
public function storeSessionConfig(Request $request)
{
    // Validate
    $request->validate([
        'session_number' => 'required|integer|min:1',
        'session_type' => 'required|in:bpkh,produsen'
    ]);
    
    // Check if exists
    $exists = PresentationSessionConfig::where('session_number', $request->session_number)
        ->where('session_type', $request->session_type)
        ->exists();
        
    if ($exists) {
        return back()->with('error', 'Sesi sudah ada');
    }
    
    // Create
    PresentationSessionConfig::create([
        'session_name' => 'Sesi ' . $request->session_number,
        'session_number' => $request->session_number,
        'session_type' => $request->session_type,
        'order' => $maxOrder + 1,
        'is_active' => true
    ]);
}
```

### **2. Delete Session**
Superadmin dapat menghapus sesi (jika tidak ada peserta):

**UI:**
```
[X] button on session card
  ↓
SweetAlert confirmation:
  "Hapus Sesi 6?"
  "Sesi hanya bisa dihapus jika tidak ada peserta"
  ↓
[Ya, Hapus!] button
  ↓
Deletes session config
```

**Backend Logic:**
```php
public function destroySessionConfig($id)
{
    $config = PresentationSessionConfig::findOrFail($id);
    
    // Check if has participants
    $hasParticipants = false;
    if ($config->session_type === 'bpkh') {
        $hasParticipants = BpkhPresentationSession::where('session_name', $config->session_name)->exists();
    } else {
        $hasParticipants = ProdusenPresentationSession::where('session_name', $config->session_name)->exists();
    }
    
    if ($hasParticipants) {
        return back()->with('error', 'Tidak dapat menghapus sesi yang masih memiliki peserta');
    }
    
    $config->delete();
}
```

### **3. Dynamic Dropdowns**
Dropdown "Pilih Sesi" otomatis ter-update sesuai konfigurasi:

**Before:**
```blade
<select name="session_name">
    <option value="Sesi 1">Sesi 1</option>
    <option value="Sesi 3">Sesi 3</option>
    <option value="Sesi 5">Sesi 5</option>
</select>
```

**After:**
```blade
<select name="session_name">
    <option value="">Pilih Sesi</option>
    @foreach($bpkhSessionConfigs as $config)
        <option value="{{ $config->session_name }}">{{ $config->session_name }}</option>
    @endforeach
</select>
```

### **4. Dynamic Session Cards**
Session cards otomatis ter-generate sesuai konfigurasi:

**Before:**
```blade
@foreach(['Sesi 1', 'Sesi 3', 'Sesi 5'] as $sessionName)
    <div class="card">...</div>
@endforeach
```

**After:**
```blade
@foreach($bpkhSessionConfigs as $config)
    @php $sessionName = $config->session_name; @endphp
    <div class="card">
        <div class="card-header">
            <h5>{{ $sessionName }}</h5>
            <button class="btn-delete-session-config" data-id="{{ $config->id }}">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
        ...
    </div>
@endforeach
```

---

## 🎯 Use Cases

### **Use Case 1: Add Sesi 6 for BPKH**
```
1. Superadmin opens "Manajemen Sesi Presentasi"
2. Click "Tambah Sesi Baru" (BPKH section)
3. Enter number: 6
4. Click "Tambah Sesi"
5. ✅ "Sesi 6" created for BPKH
6. Dropdown now shows: Sesi 1, Sesi 3, Sesi 5, Sesi 6
7. New card appears for Sesi 6
```

### **Use Case 2: Add Sesi 10 for Produsen**
```
1. Superadmin opens "Manajemen Sesi Presentasi"
2. Click "Tambah Sesi Baru" (Produsen section)
3. Enter number: 10
4. Click "Tambah Sesi"
5. ✅ "Sesi 10" created for Produsen
6. Dropdown now shows: Sesi 2, Sesi 4, Sesi 10
7. New card appears for Sesi 10
```

### **Use Case 3: Delete Unused Session**
```
1. Superadmin wants to remove Sesi 6
2. Click [X] button on Sesi 6 card
3. SweetAlert: "Hapus Sesi 6?"
4. Click "Ya, Hapus!"
5. ✅ Sesi 6 deleted
6. Card removed from view
7. Dropdown updated
```

### **Use Case 4: Try to Delete Session with Participants**
```
1. Superadmin tries to delete Sesi 1
2. Click [X] button
3. Click "Ya, Hapus!"
4. ❌ Error: "Tidak dapat menghapus sesi yang masih memiliki peserta"
5. Must remove participants first
```

---

## 🔧 How It Works

### **Data Flow:**

```
1. Superadmin adds new session
   ↓
2. Stored in presentation_sessions_config table
   ↓
3. Controller fetches active configs
   ↓
4. Passed to view
   ↓
5. Rendered in dropdowns and cards
   ↓
6. Juri sees updated session list
   ↓
7. Can add participants to new sessions
```

### **Model Methods:**

```php
// Get active BPKH sessions
PresentationSessionConfig::getActiveBpkhSessions()
// Returns: Collection of BPKH session configs

// Get active Produsen sessions
PresentationSessionConfig::getActiveProdusenSessions()
// Returns: Collection of Produsen session configs

// Get all active sessions grouped by type
PresentationSessionConfig::getAllActiveSessions()
// Returns: ['bpkh' => [...], 'produsen' => [...]]
```

---

## ✅ Validation Rules

### **Add Session:**
- ✅ `session_number`: Required, integer, min 1
- ✅ `session_type`: Required, must be 'bpkh' or 'produsen'
- ✅ Unique combination of (session_number, session_type)
- ✅ Auto-generates session_name: "Sesi {number}"
- ✅ Auto-increments order

### **Delete Session:**
- ✅ Must exist in database
- ✅ Cannot delete if has participants
- ✅ Must remove participants first

---

## 🎨 UI Components

### **1. Add Session Modal (BPKH)**
```html
<button data-bs-toggle="modal" data-bs-target="#addBpkhSessionModal">
    <i class="mdi mdi-plus-circle"></i> Tambah Sesi Baru
</button>

<div class="modal" id="addBpkhSessionModal">
    <form method="POST" action="{{ route('dashboard.presentation-session.config.store') }}">
        <input type="hidden" name="session_type" value="bpkh">
        <input type="number" name="session_number" min="1" required>
        <button type="submit">Tambah Sesi</button>
    </form>
</div>
```

### **2. Delete Session Button**
```html
<button class="btn-delete-session-config" 
        data-id="{{ $config->id }}"
        data-name="{{ $sessionName }}">
    <i class="mdi mdi-close"></i>
</button>
```

### **3. SweetAlert2 Confirmation**
```javascript
Swal.fire({
    title: 'Hapus Konfigurasi Sesi?',
    html: `Apakah Anda yakin ingin menghapus<br><strong>${name}</strong>?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
});
```

---

## 📊 Example Scenarios

### **Scenario 1: Event with 10 BPKH Sessions**
```
Superadmin creates:
- Sesi 1 (BPKH)
- Sesi 2 (BPKH)
- Sesi 3 (BPKH)
- ...
- Sesi 10 (BPKH)

Result: 10 BPKH sessions available
```

### **Scenario 2: Mixed Numbering**
```
Superadmin creates:
- Sesi 1 (BPKH)
- Sesi 1 (Produsen)  ← Same number, different type ✅
- Sesi 2 (BPKH)
- Sesi 2 (Produsen)  ← Same number, different type ✅

Result: Both types can use same numbers
```

### **Scenario 3: Non-Sequential Numbers**
```
Superadmin creates:
- Sesi 1 (BPKH)
- Sesi 5 (BPKH)   ← Skip 2, 3, 4 ✅
- Sesi 10 (BPKH)  ← Skip 6, 7, 8, 9 ✅
- Sesi 100 (BPKH) ← Any number ✅

Result: Flexible numbering system
```

---

## 🔐 Security & Validation

### **Unique Constraint:**
```sql
UNIQUE(session_number, session_type)
```
- Prevents duplicate session numbers within same type
- BPKH Sesi 1 and Produsen Sesi 1 can coexist ✅
- Two BPKH Sesi 1 cannot coexist ❌

### **Participant Check:**
```php
if ($hasParticipants) {
    return back()->with('error', 'Tidak dapat menghapus sesi yang masih memiliki peserta');
}
```
- Prevents accidental deletion of active sessions
- Must clean up participants first

---

## 🎉 Benefits

| Feature | Before | After |
|---------|--------|-------|
| **Add Session** | Edit code + deploy | Click button in UI |
| **Delete Session** | Edit code + deploy | Click button in UI |
| **Number Limit** | Fixed (1-5) | Unlimited (1-∞) |
| **Flexibility** | Low | High |
| **Developer Needed** | Yes | No |
| **Deployment Required** | Yes | No |
| **User-Friendly** | No | Yes |

---

## 📝 Summary

**Problem:** Session numbers were hardcoded and inflexible

**Solution:** Dynamic session configuration system

**Implementation:**
- New table: `presentation_sessions_config`
- New model: `PresentationSessionConfig`
- Add/Delete session via UI
- Dynamic dropdowns and cards
- Full validation and security

**Result:** Superadmin can manage sessions without developer intervention! 🎉

---

**Last Updated**: 2025-10-21  
**Version**: 2.0  
**Status**: ✅ Fully Dynamic System
