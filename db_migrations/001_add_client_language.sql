-- Add client_language column to clients table
-- This allows per-client language override for emails and client portal
-- If NULL, falls back to system default language

ALTER TABLE `clients` 
ADD COLUMN `client_language` varchar(10) DEFAULT NULL AFTER `client_currency_code`;

-- Index for faster lookups
CREATE INDEX `idx_client_language` ON `clients` (`client_language`);
