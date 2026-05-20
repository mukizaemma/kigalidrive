# Database Rollback Guide

## Overview
This guide helps you safely revert your database when replacing the current project with a previous version (before AI edits).

## Key Database Changes Made During AI Edits

### 1. **Hotels Table - Status Field**
- **Change**: Properties created by users now default to `status = 'Pending'` instead of `'Active'`
- **Migration**: No new migration was created - this is a code-level change
- **⚠️ IMPORTANT**: The original migration only allows `'Active'` or `'Inactive'` in the enum. If you have properties with `status = 'Pending'`, you MUST convert them before rollback, or MySQL will throw an error.
- **Impact**: Existing properties with `status = 'Active'` will remain active, but new ones created during AI edits will be `'Pending'` (which may not be supported by old code)

### 2. **Hotel Rooms Table - added_by Field**
- **Change**: The `added_by` field is now required when creating rooms
- **Migration**: Field already existed in the migration - we just ensured it's always populated
- **Impact**: Old code might not populate this field, causing errors

### 3. **No New Database Tables or Columns**
- All changes were code-level, not database structure changes
- No new migrations were created during the AI editing session

## Rollback Steps

### Option 1: Keep Current Database (Recommended if you have data)

If you want to keep your existing data:

1. **Backup your database first:**
   ```bash
   mysqldump -u your_username -p your_database_name > backup_before_rollback.sql
   ```

2. **Update existing records:**
   - If your old code expects all properties to be `'Active'`, update pending ones:
     ```sql
     UPDATE hotels SET status = 'Active' WHERE status = 'Pending';
     ```

3. **Ensure hotel_rooms have added_by:**
   - If any rooms are missing `added_by`, set them to the hotel owner:
     ```sql
     UPDATE hotel_rooms hr
     INNER JOIN hotels h ON hr.hotel_id = h.id
     SET hr.added_by = h.added_by
     WHERE hr.added_by IS NULL OR hr.added_by = 0;
     ```

4. **Revert your code** to the previous version

5. **Test the application** to ensure everything works

### Option 2: Fresh Database (If you don't need existing data)

If you want a completely fresh start:

1. **Backup current database** (just in case):
   ```bash
   mysqldump -u your_username -p your_database_name > backup_current.sql
   ```

2. **Drop and recreate database:**
   ```sql
   DROP DATABASE your_database_name;
   CREATE DATABASE your_database_name;
   ```

3. **Run migrations from scratch:**
   ```bash
   php artisan migrate:fresh
   # OR if you have seeders:
   php artisan migrate:fresh --seed
   ```

4. **Revert your code** to the previous version

### Option 3: Selective Rollback (Keep data, fix structure)

If you want to keep data but ensure compatibility:

1. **Backup database:**
   ```bash
   mysqldump -u your_username -p your_database_name > backup.sql
   ```

2. **Check and fix pending properties (REQUIRED):**
   ```sql
   -- Check for pending properties
   SELECT COUNT(*) FROM hotels WHERE status = 'Pending';
   SELECT id, name, status FROM hotels WHERE status = 'Pending';
   ```
   - **⚠️ CRITICAL**: The original hotels table enum only allows `'Active'` or `'Inactive'`
   - **MUST convert all 'Pending' to 'Active'** before rollback:
     ```sql
     UPDATE hotels SET status = 'Active' WHERE status = 'Pending';
     ```
   - If you don't do this, MySQL will throw an error when querying hotels with 'Pending' status

3. **Fix hotel_rooms added_by field:**
   ```sql
   -- Check for rooms without added_by
   SELECT COUNT(*) FROM hotel_rooms WHERE added_by IS NULL OR added_by = 0;
   
   -- Fix them
   UPDATE hotel_rooms hr
   INNER JOIN hotels h ON hr.hotel_id = h.id
   SET hr.added_by = h.added_by
   WHERE hr.added_by IS NULL OR hr.added_by = 0;
   ```

4. **Revert code and test**

## Important Checks Before Rollback

### 1. Check Current Database State
```sql
-- Check properties status distribution
SELECT status, COUNT(*) as count FROM hotels GROUP BY status;

-- Check rooms without added_by
SELECT COUNT(*) FROM hotel_rooms WHERE added_by IS NULL OR added_by = 0;

-- Check for any pending properties
SELECT id, name, status, added_by FROM hotels WHERE status = 'Pending';
```

### 2. Check Your Old Code Expectations

Review your old codebase to see:
- Does it handle `status = 'Pending'` in hotels table?
- Does it require `added_by` when creating hotel_rooms?
- Are there any other status-related logic that might break?

### 3. Data Migration Script (if needed)

If your old code doesn't support 'Pending' status, create a migration:

```php
<?php
// Create: database/migrations/YYYY_MM_DD_HHMMSS_convert_pending_to_active.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert all Pending properties to Active
        DB::table('hotels')
            ->where('status', 'Pending')
            ->update(['status' => 'Active']);
    }

    public function down(): void
    {
        // This is a one-way migration
    }
};
```

## Recommended Approach

**For Production/Important Data:**
1. ✅ Backup database
2. ✅ Check current data state
3. ✅ Update data to match old code expectations
4. ✅ Revert code
5. ✅ Test thoroughly

**For Development/Test Data:**
1. ✅ Backup (optional but recommended)
2. ✅ Fresh migration: `php artisan migrate:fresh --seed`
3. ✅ Revert code
4. ✅ Test

## Post-Rollback Verification

After reverting, verify:

1. **Properties load correctly:**
   ```bash
   php artisan tinker
   >>> \App\Models\Hotel::count();
   >>> \App\Models\Hotel::where('status', 'Active')->count();
   ```

2. **Rooms load correctly:**
   ```bash
   >>> \App\Models\HotelRoom::count();
   >>> \App\Models\HotelRoom::whereNotNull('added_by')->count();
   ```

3. **Test key functionality:**
   - Create a new property
   - Add a room to a property
   - View properties list
   - Edit a property

## Troubleshooting

### Issue: "Field 'added_by' doesn't have a default value"
**Solution:** Run the SQL update query from Option 3 above to populate missing `added_by` values.

### Issue: Properties with 'Pending' status not showing
**Solution:** Either update them to 'Active' or ensure your old code handles 'Pending' status.

### Issue: Foreign key constraints fail
**Solution:** Check that all foreign key relationships are intact:
```sql
-- Check for orphaned rooms
SELECT hr.* FROM hotel_rooms hr
LEFT JOIN hotels h ON hr.hotel_id = h.id
WHERE h.id IS NULL;

-- Check for orphaned bookings
SELECT hb.* FROM hotel_bookings hb
LEFT JOIN hotels h ON hb.hotel_id = h.id
WHERE hb.hotel_id IS NOT NULL AND h.id IS NULL;
```

## Summary

**Critical Database Changes:**
- ⚠️ **REQUIRED**: Convert all `status = 'Pending'` to `status = 'Active'` in hotels table
- ⚠️ **REQUIRED**: Ensure all `hotel_rooms` have `added_by` populated
- No new database tables or columns were added
- Only code-level changes to how data is inserted

**Safest Approach (Step-by-Step):**

1. **Backup database:**
   ```bash
   mysqldump -u your_username -p your_database_name > backup_before_rollback_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Fix pending properties (REQUIRED):**
   ```sql
   UPDATE hotels SET status = 'Active' WHERE status = 'Pending';
   ```

3. **Fix hotel_rooms added_by (REQUIRED):**
   ```sql
   UPDATE hotel_rooms hr
   INNER JOIN hotels h ON hr.hotel_id = h.id
   SET hr.added_by = h.added_by
   WHERE hr.added_by IS NULL OR hr.added_by = 0;
   ```

4. **Verify fixes:**
   ```sql
   -- Should return 0
   SELECT COUNT(*) FROM hotels WHERE status = 'Pending';
   SELECT COUNT(*) FROM hotel_rooms WHERE added_by IS NULL OR added_by = 0;
   ```

5. **Revert your code** to the previous version

6. **Test the application** thoroughly

## Quick SQL Script (Run Before Rollback)

Run this complete script in your MySQL/phpMyAdmin:

```sql
-- 1. Backup check (verify you have a backup first!)
-- 2. Convert all Pending to Active
UPDATE hotels SET status = 'Active' WHERE status = 'Pending';

-- 3. Fix hotel_rooms added_by
UPDATE hotel_rooms hr
INNER JOIN hotels h ON hr.hotel_id = h.id
SET hr.added_by = h.added_by
WHERE hr.added_by IS NULL OR hr.added_by = 0;

-- 4. Verify
SELECT 'Hotels with Pending status:' as check_type, COUNT(*) as count FROM hotels WHERE status = 'Pending'
UNION ALL
SELECT 'Rooms without added_by:', COUNT(*) FROM hotel_rooms WHERE added_by IS NULL OR added_by = 0;
-- Both should return 0
```

