# Collective Scoring Order Fix

## 📌 Issue
Urutan peserta di halaman **Penilaian Kolektif** tidak sesuai dengan urutan di sesi. Seharusnya urutan peserta mengikuti urutan yang sudah diatur di database sesi.

## 🎯 Expected Behavior

### **Example: Sesi 2 (Produsen)**
**Order in Session Management:**
1. Direktorat Penggunaan Kawasan Hutan
2. Direktorat Perencanaan dan Evaluasi Pengelolaan Daerah Aliran Sungai
3. Direktorat Pengendalian Kebakaran Hutan

**Order in Collective Scoring:**
Should be the **SAME** as above ✅

---

## 🔧 Solution Implemented

### **Before (Wrong Order):**
```php
// Get forms data - No ordering
$forms = BpkhPresentationAssesment::whereIn('respondent_id', $selectedIds)->get();
```
❌ Order is random/based on database insertion order

### **After (Correct Order):**
```php
// Get forms data
$forms = BpkhPresentationAssesment::whereIn('respondent_id', $selectedIds)->get();

// Sort forms based on session order
$sessionOrder = [];
$sessionData = BpkhPresentationSession::whereIn('respondent_id', $selectedIds)
    ->orderBy('session_name')
    ->orderBy('order')
    ->get();

foreach ($sessionData as $index => $session) {
    $sessionOrder[$session->respondent_id] = $index;
}

// Sort forms collection based on session order
$forms = $forms->sortBy(function($form) use ($sessionOrder) {
    return $sessionOrder[$form->respondent_id] ?? 999;
})->values();
```
✅ Order follows session configuration

---

## 📊 How It Works

### **Step-by-Step Process:**

1. **Get Selected Participants**
   ```php
   $selectedIds = explode(',', $ids);
   $forms = BpkhPresentationAssesment::whereIn('respondent_id', $selectedIds)->get();
   ```

2. **Fetch Session Order Data**
   ```php
   $sessionData = BpkhPresentationSession::whereIn('respondent_id', $selectedIds)
       ->orderBy('session_name')  // Sort by session first
       ->orderBy('order')          // Then by order within session
       ->get();
   ```

3. **Create Order Mapping**
   ```php
   $sessionOrder = [];
   foreach ($sessionData as $index => $session) {
       $sessionOrder[$session->respondent_id] = $index;
   }
   ```
   Result:
   ```php
   [
       'RESP001' => 0,  // First in order
       'RESP002' => 1,  // Second in order
       'RESP003' => 2,  // Third in order
   ]
   ```

4. **Sort Forms Collection**
   ```php
   $forms = $forms->sortBy(function($form) use ($sessionOrder) {
       return $sessionOrder[$form->respondent_id] ?? 999;
   })->values();
   ```
   - If `respondent_id` found in `$sessionOrder`, use that index
   - If not found, use `999` (push to end)
   - `->values()` resets array keys to 0, 1, 2, ...

---

## 🎯 Example Scenario

### **Sesi 2 Configuration (in database):**
```
session_name | respondent_id | nama_instansi                    | order
-------------|---------------|----------------------------------|------
Sesi 2       | PROD001       | Direktorat Penggunaan...         | 1
Sesi 2       | PROD002       | Direktorat Perencanaan...        | 2
Sesi 2       | PROD003       | Direktorat Pengendalian...       | 3
```

### **User Clicks "Sesi 2" Button:**
- Checkboxes for PROD001, PROD002, PROD003 are checked
- User clicks "Penilaian Kolektif"
- IDs passed: `?ids=PROD001,PROD002,PROD003`

### **Controller Processing:**
```php
// 1. Fetch forms (unordered)
$forms = [
    PROD003 => {...},  // Random order from DB
    PROD001 => {...},
    PROD002 => {...}
];

// 2. Fetch session order
$sessionData = [
    0 => {respondent_id: 'PROD001', order: 1},
    1 => {respondent_id: 'PROD002', order: 2},
    2 => {respondent_id: 'PROD003', order: 3}
];

// 3. Create mapping
$sessionOrder = [
    'PROD001' => 0,
    'PROD002' => 1,
    'PROD003' => 2
];

// 4. Sort forms
$forms = [
    0 => PROD001 {...},  // Correct order!
    1 => PROD002 {...},
    2 => PROD003 {...}
];
```

### **Result in View:**
```
Accordion Item 1: Direktorat Penggunaan Kawasan Hutan
Accordion Item 2: Direktorat Perencanaan dan Evaluasi...
Accordion Item 3: Direktorat Pengendalian Kebakaran Hutan
```
✅ **Order matches session configuration!**

---

## 📁 Files Modified

### **1. BpkhPresentationController.php**
**Method:** `bulkScoreForm()`
**Lines:** Added sorting logic after fetching forms

### **2. ProdusenPresentationController.php**
**Method:** `bulkScoreForm()`
**Lines:** Added sorting logic after fetching forms

---

## 🧪 Testing

### **Test Case 1: Single Session**
1. Click "Sesi 1" button
2. Click "Penilaian Kolektif"
3. ✅ Verify order matches Sesi 1 configuration

### **Test Case 2: Multiple Sessions Mixed**
1. Manually check BPKH from Sesi 1 and Sesi 3
2. Click "Penilaian Kolektif"
3. ✅ Verify Sesi 1 participants appear first, then Sesi 3

### **Test Case 3: Manual Selection (No Session)**
1. Manually check random participants
2. Click "Penilaian Kolektif"
3. ✅ Should still work (fallback to 999 order)

---

## 🔍 Edge Cases Handled

### **Case 1: Participant Not in Any Session**
```php
return $sessionOrder[$form->respondent_id] ?? 999;
```
- If `respondent_id` not found in session table
- Use `999` as order (appears at end)
- Prevents errors

### **Case 2: Mixed Sessions**
```php
->orderBy('session_name')
->orderBy('order')
```
- First sorts by session name (Sesi 1, Sesi 2, etc.)
- Then by order within each session
- Maintains correct sequence

### **Case 3: Empty Session Data**
```php
foreach ($sessionData as $index => $session) {
    $sessionOrder[$session->respondent_id] = $index;
}
```
- If no session data found, `$sessionOrder` remains empty
- All participants get `999` order
- Still works, just no specific ordering

---

## ✅ Benefits

### **Before Fix:**
- ❌ Random order in collective scoring
- ❌ Confusing for juri
- ❌ Hard to follow presentation sequence
- ❌ Inconsistent with session buttons

### **After Fix:**
- ✅ Order matches session configuration
- ✅ Easy to follow for juri
- ✅ Consistent with presentation flow
- ✅ Professional appearance
- ✅ Matches physical presentation order

---

## 📊 Performance Impact

### **Additional Queries:**
```php
// 1 extra query per bulk scoring page load
BpkhPresentationSession::whereIn('respondent_id', $selectedIds)
    ->orderBy('session_name')
    ->orderBy('order')
    ->get();
```

### **Performance:**
- ✅ Minimal impact (indexed columns)
- ✅ Only runs once per page load
- ✅ Small dataset (max ~10 participants per session)
- ✅ Worth the UX improvement

---

## 🎯 User Experience Improvement

### **Juri Workflow:**
```
1. View "Penilaian Presentasi" page
2. Click "Sesi 2" button
   → Auto-checks participants in correct order
3. Click "Penilaian Kolektif"
   → Opens scoring page
   → Participants appear in SAME order as button selection
4. Fill scores from top to bottom
   → Follows actual presentation sequence
5. Submit all scores
   → Efficient and organized
```

**Result:** Juri can score participants in the exact order they presented! 🎉

---

## 📝 Summary

**Problem:** Collective scoring order was random/inconsistent

**Solution:** Sort participants based on session order from database

**Implementation:** 
- Fetch session order data
- Create order mapping
- Sort forms collection
- Apply to both BPKH and Produsen controllers

**Result:** Participants now appear in correct presentation order ✅

---

**Last Updated**: 2025-10-20  
**Version**: 1.1  
**Status**: ✅ Fixed & Tested
