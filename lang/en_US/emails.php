<?php
/**
 * English (US) - Email Translations
 * Email templates for invoices, quotes, tickets, and notifications
 */

global $lang;

// Email Greetings
$lang['email_hello'] = 'Hello';
$lang['email_regards'] = 'Regards';
$lang['email_thank_you'] = 'Thank you';

// Invoice Emails
$lang['email_invoice_subject_paid'] = 'Invoice %s Receipt';
$lang['email_invoice_subject'] = 'Invoice %s';
$lang['email_invoice_body_paid'] = 'Hello %s,<br><br>Please click on the link below to see your invoice regarding "%s" marked <b>paid</b>.<br><br><a href="%s">Invoice Link</a><br><br><br>--<br>%s - Billing<br>%s<br>%s';
$lang['email_invoice_body'] = 'Hello %s,<br><br>Please view the details of your invoice regarding "%s" below.<br><br>Invoice: %s<br>Issue Date: %s<br>Total: %s<br>Balance Due: %s<br>Due Date: %s<br><br><br>To view your invoice, please click <a href="%s">here</a>.<br><br><br>--<br>%s - Billing<br>%s<br>%s';
$lang['email_invoice_line_invoice'] = 'Invoice';
$lang['email_invoice_line_issue_date'] = 'Issue Date';
$lang['email_invoice_line_total'] = 'Total';
$lang['email_invoice_line_balance'] = 'Balance Due';
$lang['email_invoice_line_due_date'] = 'Due Date';
$lang['email_invoice_view_link'] = 'To view your invoice, please click';
$lang['email_invoice_view_here'] = 'here';
$lang['email_invoice_signature_billing'] = 'Billing';

// Quote Emails
$lang['email_quote_subject'] = 'Quote [%s]';
$lang['email_quote_body'] = 'Hello %s,<br><br>Thank you for your inquiry, we are pleased to provide you with the following estimate.<br><br><br>%s<br>Total Cost: %s<br><br><br>View and accept your estimate online <a href="%s">here</a><br><br><br>--<br>%s - Sales<br>%s<br>%s';
$lang['email_quote_thank_you'] = 'Thank you for your inquiry, we are pleased to provide you with the following estimate.';
$lang['email_quote_total_cost'] = 'Total Cost';
$lang['email_quote_view_accept'] = 'View and accept your estimate online';
$lang['email_quote_signature_sales'] = 'Sales';

// Ticket Emails - New Ticket
$lang['email_ticket_new_subject'] = 'ITFlow - New Ticket - %s: %s';
$lang['email_ticket_new_body'] = 'Hello, <br><br>This is a notification that a new ticket has been raised in ITFlow. <br>Client: %s<br>Priority: %s<br>Link: %s <br><br><b>%s</b><br>%s';
$lang['email_ticket_new_notification'] = 'This is a notification that a new ticket has been raised in ITFlow.';
$lang['email_ticket_new_client'] = 'Client';
$lang['email_ticket_new_priority'] = 'Priority';
$lang['email_ticket_new_link'] = 'Link';

// Ticket Emails - Reply/Update
$lang['email_ticket_reply_subject'] = 'Ticket %s - %s - Reply';
$lang['email_ticket_reply_body'] = 'Hello %s,<br><br>A reply has been added to your ticket.<br><br>Ticket: %s<br>Subject: %s<br><br>%s<br><br><a href="%s">View Ticket</a><br><br>--<br>%s<br>Support<br>%s';
$lang['email_ticket_reply_notification'] = 'A reply has been added to your ticket.';
$lang['email_ticket_view'] = 'View Ticket';
$lang['email_ticket_signature_support'] = 'Support';

// Ticket Emails - Status Change
$lang['email_ticket_closed_subject'] = 'Ticket %s - Closed';
$lang['email_ticket_closed_body'] = 'Hello %s,<br><br>Your ticket has been closed.<br><br>Ticket: %s<br>Subject: %s<br><br>If you have any questions, please reply to this email.<br><br><a href="%s">View Ticket</a><br><br>--<br>%s<br>Support<br>%s';
$lang['email_ticket_closed_notification'] = 'Your ticket has been closed.';

$lang['email_ticket_resolved_subject'] = 'Ticket %s - Resolved';
$lang['email_ticket_resolved_body'] = 'Hello %s,<br><br>Your ticket has been resolved.<br><br>Ticket: %s<br>Subject: %s<br><br>If the issue persists, please reply to this email.<br><br><a href="%s">View Ticket</a><br><br>--<br>%s<br>Support<br>%s';
$lang['email_ticket_resolved_notification'] = 'Your ticket has been resolved.';

// Payment Emails
$lang['email_payment_subject'] = 'Payment Receipt - %s';
$lang['email_payment_body'] = 'Hello %s,<br><br>We have received your payment.<br><br>Amount: %s<br>Date: %s<br>Reference: %s<br><br>Thank you for your payment.<br><br>--<br>%s<br>Billing<br>%s';
$lang['email_payment_received'] = 'We have received your payment.';
$lang['email_payment_amount'] = 'Amount';
$lang['email_payment_date'] = 'Date';
$lang['email_payment_reference'] = 'Reference';
$lang['email_payment_thank_you'] = 'Thank you for your payment.';

// Password Reset Emails
$lang['email_password_reset_subject'] = 'Password Reset Request';
$lang['email_password_reset_body'] = 'Hello %s,<br><br>A password reset has been requested for your account.<br><br>Click the link below to reset your password:<br><a href="%s">Reset Password</a><br><br>This link will expire in 1 hour.<br><br>If you did not request this reset, please ignore this email.<br><br>--<br>%s';
$lang['email_password_reset_request'] = 'A password reset has been requested for your account.';
$lang['email_password_reset_link'] = 'Click the link below to reset your password:';
$lang['email_password_reset_expire'] = 'This link will expire in 1 hour.';
$lang['email_password_reset_ignore'] = 'If you did not request this reset, please ignore this email.';

// Common Email Elements
$lang['email_signature'] = '--';
$lang['email_view_online'] = 'View online';
$lang['email_if_questions'] = 'If you have any questions, please reply to this email.';
$lang['email_do_not_reply'] = 'This is an automated message. Please do not reply to this email.';
