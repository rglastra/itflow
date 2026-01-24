# ITFlow Fork Development TODO

**Repository**: rglastra/itflow  
**Upstream**: itflow-org/itflow  
**Branch**: master  
**Date**: January 24, 2025

## Completed ✅

### Task #1: Comprehensive i18n Support (PR #1255 + #1256 + Dutch translations)
**Branch**: feature/i18n-comprehensive  
**Status**: Complete - Ready for PR  
**Commits**: 9

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
- ✅ Database migration for client_language column (integrated into database_updates.php)
- ✅ UI in agent portal to set client language
- ✅ Migration documentation for Docker deployments

**Database Changes:**
- New column: `clients.client_language` (varchar(10), nullable)
- Database version: 2.3.8 → 2.3.9
- Automatic migration via Admin → System Settings → Database

**Critical Requirement Met:**
"We CANNOT be sending emails in English to Dutch clients" - ✅ RESOLVED

### Task #2: Mollie Payment Gateway Integration
**Branch**: feature/i18n-comprehensive  
**Status**: Complete - Ready for Testing  
**Commits**: 1

**Implemented Features:**
- ✅ Mollie PHP SDK integration (plugins/mollie-api-php with custom init.php)
- ✅ Payment link generation (guest_pay_invoice_mollie.php)
- ✅ Webhook handler for payment confirmations (guest_mollie_webhook.php)
- ✅ Admin UI for Mollie provider configuration
- ✅ "Pay Now" button in invoice view (guest_view_invoice.php)
- ✅ Payment links in invoice emails with i18n button text
- ✅ Payment status tracking:
  - Paid (full/partial)
  - Cancelled
  - Expired
  - Failed
- ✅ Automatic gateway fee expense tracking
- ✅ Email notifications for payment receipts
- ✅ Multi-language support (English, German, Dutch)

**Payment Flow:**
1. Admin configures Mollie in payment_providers table
2. Agent sends invoice → Email includes "Pay Now" button
3. Client clicks button → Redirected to Mollie payment page
4. Client completes payment → Mollie webhook updates invoice status
5. System sends payment receipt email in client's language
6. Gateway fees automatically logged as expenses

**Usage:**
1. Admin → Payment Providers → Add Mollie
2. Enter Mollie API keys (live_ or test_)
3. Configure account, vendor, expense settings
4. Send invoices → Payment links automatically included

## Tasks Remaining

- [ ] Complete remaining email internationalization (~40 locations)
- [ ] Internationalize remaining client portal pages (ticket.php, ticket_add.php, tickets.php)
- [ ] Test Mollie payment flow in production
- [ ] Create PR to upstream

## Next Steps
1. Test Mollie integration with live API key
2. Create PR to upstream (itflow-org/itflow) for full i18n + Mollie features
3. Address review feedback
4. Continue with remaining i18n coverage

