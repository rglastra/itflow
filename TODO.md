# ITFlow Fork Development TODO

**Repository**: rglastra/itflow  
**Upstream**: itflow-org/itflow  
**Branch**: master  
**Date**: January 24, 2026

## Completed ✅

### Task #1: Comprehensive i18n Support (PR #1255 + #1256 + Dutch translations)
**Branch**: feature/i18n-comprehensive  
**Status**: Complete - Ready for PR  
**Commits**: 6

**Implemented Features:**
- ✅ Core i18n framework (includes/i18n.php)
- ✅ Modular translation files (lang/{locale}/{module}.php)
- ✅ Browser language detection with cookie persistence
- ✅ Per-client language configuration in database
- ✅ Email internationalization for all critical client-facing emails:
  - Invoices (paid/unpaid)
  - Quotes
  - Tickets (creation, replies, status changes)
  - Payment receipts (full/partial)
  - Password resets
- ✅ Agent portal internationalization:
  - Tickets module (ticket.php, ticket_list.php)
  - Clients module
  - Dashboard
  - Navigation (side_nav, top_nav)
- ✅ Client portal internationalization:
  - Invoices (invoices.php)
  - Quotes (quotes.php)
- ✅ Full translations for 3 languages:
  - English (en_US) - 557+ keys
  - German (de_DE) - 557+ keys  
  - Dutch (nl_NL) - 557+ keys
- ✅ Database migration for client_language column
- ✅ UI in agent portal to set client language
- ✅ Migration documentation

**Database Changes:**
- New column: `clients.client_language` (varchar(10), nullable)
- Migration script: `db_migrations/001_add_client_language.sql`

**Critical Requirement Met:**
"We CANNOT be sending emails in English to Dutch clients" - ✅ RESOLVED

## Tasks Remaining

- [ ] Complete remaining email internationalization (~40 locations)
- [ ] Internationalize remaining client portal pages (ticket.php, ticket_add.php, tickets.php)
- [ ] Add Mollie payment integration
- [ ] Test full workflow in deployed environment

## Next Steps
1. Create PR to upstream (itflow-org/itflow)
2. Address review feedback
3. Continue with remaining i18n coverage
4. Implement Mollie payment gateway
