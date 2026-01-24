# Database Migration: Add Client Language Support

## Overview
This migration adds per-client language preference support to ITFlow, allowing each client to receive emails and view their portal in their preferred language.

**Database Version**: 2.3.8 → 2.3.9

## Changes
- Adds `client_language` column to `clients` table (varchar(10), nullable)
- Creates index on `client_language` for performance
- Updates database version to 2.3.9

## Installation Instructions

### For Docker Deployments (Recommended)

ITFlow has an automatic database migration system. When you update your Docker container:

1. **Pull the latest changes** from your fork:
   ```bash
   docker compose exec itflow git pull origin master
   ```

2. **Access the admin panel** and navigate to:
   ```
   Admin → System Settings → Database
   ```
   
3. **Click "Update Database"** - ITFlow will automatically apply all pending migrations including this one

4. **Verify** the migration completed successfully by checking the database version shows `2.3.9`

### Alternative: Manual Database Update (Docker)

If you prefer to run the update manually:

```bash
# Access the database container
docker compose exec itflow-db mysql -u itflow -p itflow

# Run the migration SQL
ALTER TABLE `clients` ADD COLUMN `client_language` varchar(10) DEFAULT NULL AFTER `client_currency_code`;
CREATE INDEX `idx_client_language` ON `clients` (`client_language`);
UPDATE `settings` SET `config_current_database_version` = '2.3.9';
```

### For Non-Docker/Traditional Installations

The automatic migration system works the same way:
1. Pull the latest code
2. Navigate to Admin → System Settings → Database
3. Click "Update Database"

Or run manually via MySQL/phpMyAdmin using the SQL above.

## Verification

Check the migration was successful:

```sql
-- Check database version
SELECT config_current_database_version FROM settings;
-- Should return: 2.3.9

-- Verify column exists
DESCRIBE clients;
-- Should show client_language column
```

## Rollback (if needed)

```sql
ALTER TABLE `clients` DROP COLUMN `client_language`;
DROP INDEX `idx_client_language` ON `clients`;
UPDATE `settings` SET `config_current_database_version` = '2.3.8';
```

## Notes
- The column defaults to NULL (use system default language)
- Existing clients will have NULL until language is explicitly set
- No data migration needed - NULL is a valid state
- Supported languages: en_US, de_DE, nl_NL (extensible)
- The automatic migration system in `admin/database_updates.php` handles this when updating from 2.3.8 to 2.3.9
