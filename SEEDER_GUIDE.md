# Presentation Session Seeder Guide

## 📌 Overview
Seeder untuk populate data awal sesi presentasi BPKH dan Produsen DG ke dalam database.

## 🎯 Purpose
- Mengisi data default sesi presentasi
- Memudahkan setup awal sistem
- Dapat di-run ulang untuk reset data sesi

---

## 📊 Data yang Di-seed

### **BPKH Sessions (12 participants)**

#### **Sesi 1** (4 participants)
1. BPKH Wilayah III Pontianak
2. BPKH Wilayah VII Makassar
3. BPKH Wilayah XII Tanjung Pinang
4. BPKH Wilayah XX Bandar Lampung

#### **Sesi 3** (4 participants)
1. BPKH Wilayah V Banjarbaru
2. BPKH Wilayah IX Ambon
3. BPKH Wilayah XVII Manokwari
4. BPKH Wilayah XXI Palangkaraya

#### **Sesi 5** (4 participants)
1. BPKH Wilayah I Medan
2. BPKH Wilayah VIII Denpasar
3. BPKH Wilayah XI Yogyakarta
4. BPKH Wilayah XVIII Banda Aceh

---

### **Produsen Sessions (6 participants)**

#### **Sesi 2** (3 participants)
1. Direktorat Penggunaan Kawasan Hutan
2. Direktorat Perencanaan dan Evaluasi Pengelolaan Daerah Aliran Sungai
3. Direktorat Pengendalian Kebakaran Hutan

#### **Sesi 4** (3 participants)
1. Direktorat Bina Usaha Pemanfaatan Hutan
2. Direktorat Rehabilitasi Mangrove
3. Direktorat Penyiapan Kawasan Perhutanan Sosial

---

## 🚀 How to Run

### **1. Run Seeder**
```bash
php artisan db:seed --class=PresentationSesionSeeder
```

### **2. Run All Seeders (if included in DatabaseSeeder)**
```bash
php artisan db:seed
```

### **3. Fresh Migration + Seed**
```bash
php artisan migrate:fresh --seed
```

---

## 🔧 How It Works

### **Process Flow:**
```
1. Truncate existing session data
   ↓
2. Loop through BPKH sessions array
   ↓
3. For each participant name:
   - Find in bpkh_presentation_assesment table
   - Get respondent_id
   - Create record in sesi_bpkh_presentasi
   - Increment order
   ↓
4. Loop through Produsen sessions array
   ↓
5. For each participant name:
   - Find in produsen_presentation_assesment table
   - Get respondent_id
   - Create record in sesi_produsen_presentasi
   - Increment order
   ↓
6. Display summary
```

### **Code Logic:**
```php
// Find participant by name
$participant = BpkhPresentationAssesment::where('nama_bpkh', $namaBpkh)->first();

if ($participant) {
    // Create session record
    BpkhPresentationSession::create([
        'session_name' => $sessionName,
        'respondent_id' => $participant->respondent_id,
        'nama_bpkh' => $namaBpkh,
        'order' => $order,
        'is_active' => true
    ]);
} else {
    // Warn if not found
    $this->command->warn("✗ BPKH not found: {$namaBpkh}");
}
```

---

## ⚠️ Important Notes

### **Prerequisites:**
1. ✅ Tables must exist:
   - `bpkh_presentation_assesment`
   - `produsen_presentation_assesment`
   - `sesi_bpkh_presentasi`
   - `sesi_produsen_presentasi`

2. ✅ Participant data must exist in assessment tables
   - Seeder will lookup by `nama_bpkh` or `nama_instansi`
   - If not found, will show warning but continue

3. ✅ Seeder will **truncate** existing session data
   - All current session assignments will be deleted
   - Use with caution in production!

---

## 📝 Output Example

### **Success Output:**
```
INFO  Seeding database.

✓ Added to Sesi 1: BPKH Wilayah III Pontianak
✓ Added to Sesi 1: BPKH Wilayah VII Makassar
✓ Added to Sesi 1: BPKH Wilayah XII Tanjung Pinang
✓ Added to Sesi 1: BPKH Wilayah XX Bandar Lampung
...
✓ Added to Sesi 2: Direktorat Penggunaan Kawasan Hutan
...

========================================
Presentation Session Seeding Completed!
========================================
BPKH Sessions: 12 participants
Produsen Sessions: 6 participants
```

### **Warning Output (if participant not found):**
```
✓ Added to Sesi 1: BPKH Wilayah III Pontianak
✗ BPKH not found in assessment table: BPKH Wilayah VII Makassar
✓ Added to Sesi 1: BPKH Wilayah XII Tanjung Pinang
```

---

## 🔄 Updating Seeder Data

### **To Add New Participant:**
```php
'Sesi 1' => [
    'BPKH Wilayah III Pontianak',
    'BPKH Wilayah VII Makassar',
    'BPKH Wilayah XII Tanjung Pinang',
    'BPKH Wilayah XX Bandar Lampung',
    'BPKH Wilayah NEW Example'  // ← Add here
],
```

### **To Add New Session:**
```php
$bpkhSessions = [
    'Sesi 1' => [...],
    'Sesi 3' => [...],
    'Sesi 5' => [...],
    'Sesi 7' => [  // ← Add new session
        'BPKH Wilayah A',
        'BPKH Wilayah B'
    ]
];
```

### **After Updating:**
```bash
php artisan db:seed --class=PresentationSesionSeeder
```

---

## 🧪 Testing

### **1. Check if data seeded correctly:**
```bash
php artisan tinker
```

```php
// Check BPKH sessions
\App\Models\BpkhPresentationSession::all();

// Check Produsen sessions
\App\Models\ProdusenPresentationSession::all();

// Count by session
\App\Models\BpkhPresentationSession::where('session_name', 'Sesi 1')->count();
```

### **2. Verify in database:**
```sql
-- BPKH Sessions
SELECT session_name, COUNT(*) as total 
FROM sesi_bpkh_presentasi 
GROUP BY session_name;

-- Produsen Sessions
SELECT session_name, COUNT(*) as total 
FROM sesi_produsen_presentasi 
GROUP BY session_name;
```

### **3. Test in UI:**
1. Login as Superadmin
2. Go to "Manajemen Sesi Presentasi"
3. Verify all sessions are populated
4. Check if participants are listed correctly

---

## 🔧 Troubleshooting

### **Issue: "BPKH not found in assessment table"**
**Cause:** Participant name doesn't match exactly in assessment table

**Solution:**
1. Check exact name in `bpkh_presentation_assesment` table
2. Update seeder with correct name (case-sensitive, spaces matter)
3. Or add the participant to assessment table first

### **Issue: "Duplicate entry" error**
**Cause:** Seeder trying to add same respondent_id twice

**Solution:**
1. Seeder should truncate first (check if truncate() is called)
2. Or manually clear tables:
```sql
TRUNCATE TABLE sesi_bpkh_presentasi;
TRUNCATE TABLE sesi_produsen_presentasi;
```

### **Issue: "Table doesn't exist"**
**Cause:** Migration not run yet

**Solution:**
```bash
php artisan migrate
```

---

## 📊 Database State After Seeding

### **sesi_bpkh_presentasi:**
| id | session_name | respondent_id | nama_bpkh | order | is_active |
|----|--------------|---------------|-----------|-------|-----------|
| 1 | Sesi 1 | RESP001 | BPKH Wilayah III Pontianak | 1 | 1 |
| 2 | Sesi 1 | RESP002 | BPKH Wilayah VII Makassar | 2 | 1 |
| 3 | Sesi 1 | RESP003 | BPKH Wilayah XII Tanjung Pinang | 3 | 1 |
| ... | ... | ... | ... | ... | ... |

**Total:** 12 records

### **sesi_produsen_presentasi:**
| id | session_name | respondent_id | nama_instansi | order | is_active |
|----|--------------|---------------|---------------|-------|-----------|
| 1 | Sesi 2 | PROD001 | Direktorat Penggunaan... | 1 | 1 |
| 2 | Sesi 2 | PROD002 | Direktorat Perencanaan... | 2 | 1 |
| ... | ... | ... | ... | ... | ... |

**Total:** 6 records

---

## ✅ Verification Checklist

After running seeder, verify:

- [ ] No error messages during seeding
- [ ] All 12 BPKH participants added
- [ ] All 6 Produsen participants added
- [ ] Sessions visible in "Manajemen Sesi Presentasi" page
- [ ] Session buttons appear in presentation pages
- [ ] Clicking session button auto-checks correct participants
- [ ] No duplicate entries in database

---

## 🎯 Use Cases

### **1. Initial Setup**
Run seeder after fresh installation to populate default sessions.

### **2. Reset Sessions**
Run seeder to reset all session assignments to default configuration.

### **3. Testing**
Run seeder in development/staging to have consistent test data.

### **4. Production Deployment**
Run once during initial deployment, then manage via UI.

---

## ⚠️ Production Warning

**CAUTION:** This seeder will **DELETE ALL** existing session data!

In production:
1. ✅ Run only once during initial setup
2. ✅ Backup database before running
3. ✅ After initial seed, manage sessions via UI
4. ❌ Don't run again unless you want to reset all sessions

---

## 📝 Summary

**File:** `database/seeders/PresentationSesionSeeder.php`

**Command:** `php artisan db:seed --class=PresentationSesionSeeder`

**Data Seeded:**
- 12 BPKH participants across 3 sessions (Sesi 1, 3, 5)
- 6 Produsen participants across 2 sessions (Sesi 2, 4)

**Features:**
- ✅ Auto-lookup respondent_id from assessment tables
- ✅ Maintains order within sessions
- ✅ Validates participant existence
- ✅ Provides detailed logging
- ✅ Safe to re-run (truncates first)

---

**Last Updated**: 2025-10-20  
**Version**: 1.0  
**Status**: ✅ Ready to Use
