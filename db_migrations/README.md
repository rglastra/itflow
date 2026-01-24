# Database Migration: Add Client Language Support

## Overview
This migration adds per-client language preference support to ITFlow, allowing each client to receive emails and view their portal in their preferred language.

## Changes
- Adds `client_language` column to `clients` table
- Creates index on `client_language` for performance

## Installation Instructions

### For New Installations
No action needed - include this in the base `db.sql` schema.

### For Existing Installations

#### Option 1: Manual SQL Execution (Recommended)
Run the SQL directly in your MySQL/MariaDB database:

```bash
mysql -u your_user -p your_database < db_migrations/001_add_client_language.sql
```

Or via phpMyAdmin/Adminer:
1. Open your database management tool
2. Select the ITFlow database
3. Run the SQL from `db_migrations/001_add_client_language.sql`

#### Option 2: Via Command Line
```bash
cd /path/to/itflow
mysql -u itflow_user -p itflow_db < db_migrations/001_add_client_language.sql
```

## Verification
After running the migration, verify the column was added:

```sql
DESCRIBE clients;
```

You should see `client_language` column of type `varchar(10)` after `client_currency_code`.

## Rollback (if needed)
```sql
ALTER TABLE `clients` DROP COLUMN `client_language`;
DROP INDEX `idx_client_language` ON `clients`;
```

## Notes
- The column defaults to NULL, which means "use system default language"
- Existing clients will have NULL until language is set explicitly
- No data migration needed - NULL is a valid state
- Supported languages: en_US, de_DE, nl_NL (can be extended)
