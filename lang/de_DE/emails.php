<?php
/**
 * German (DE) - Email Translations
 * Email templates for invoices, quotes, tickets, and notifications
 */

global $lang;

// Email Greetings
$lang['email_hello'] = 'Hallo';
$lang['email_regards'] = 'Mit freundlichen Grüßen';
$lang['email_thank_you'] = 'Vielen Dank';

// Invoice Emails
$lang['email_invoice_subject_paid'] = 'Rechnung %s Quittung';
$lang['email_invoice_subject'] = 'Rechnung %s';
$lang['email_invoice_body_paid'] = 'Hallo %s,<br><br>Bitte klicken Sie auf den unten stehenden Link, um Ihre Rechnung bezüglich "%s" als <b>bezahlt</b> markiert anzusehen.<br><br><a href="%s">Rechnungslink</a><br><br><br>--<br>%s - Buchhaltung<br>%s<br>%s';
$lang['email_invoice_body'] = 'Hallo %s,<br><br>Bitte sehen Sie sich die Details Ihrer Rechnung bezüglich "%s" unten an.<br><br>Rechnung: %s<br>Ausstellungsdatum: %s<br>Gesamt: %s<br>Offener Betrag: %s<br>Fälligkeitsdatum: %s<br><br><br>Um Ihre Rechnung anzusehen, klicken Sie bitte <a href="%s">hier</a>.<br><br><br>--<br>%s - Buchhaltung<br>%s<br>%s';
$lang['email_invoice_line_invoice'] = 'Rechnung';
$lang['email_invoice_line_issue_date'] = 'Ausstellungsdatum';
$lang['email_invoice_line_total'] = 'Gesamt';
$lang['email_invoice_line_balance'] = 'Offener Betrag';
$lang['email_invoice_line_due_date'] = 'Fälligkeitsdatum';
$lang['email_invoice_view_link'] = 'Um Ihre Rechnung anzusehen, klicken Sie bitte';
$lang['email_invoice_view_here'] = 'hier';
$lang['email_invoice_signature_billing'] = 'Buchhaltung';

// Quote Emails
$lang['email_quote_subject'] = 'Angebot [%s]';
$lang['email_quote_body'] = 'Hallo %s,<br><br>Vielen Dank für Ihre Anfrage. Wir freuen uns, Ihnen folgendes Angebot unterbreiten zu können.<br><br><br>%s<br>Gesamtkosten: %s<br><br><br>Sehen Sie sich Ihr Angebot online an und nehmen Sie es <a href="%s">hier</a> an<br><br><br>--<br>%s - Vertrieb<br>%s<br>%s';
$lang['email_quote_thank_you'] = 'Vielen Dank für Ihre Anfrage. Wir freuen uns, Ihnen folgendes Angebot unterbreiten zu können.';
$lang['email_quote_total_cost'] = 'Gesamtkosten';
$lang['email_quote_view_accept'] = 'Sehen Sie sich Ihr Angebot online an und nehmen Sie es an';
$lang['email_quote_signature_sales'] = 'Vertrieb';

// Ticket Emails - New Ticket
$lang['email_ticket_new_subject'] = 'ITFlow - Neues Ticket - %s: %s';
$lang['email_ticket_new_body'] = 'Hallo, <br><br>Dies ist eine Benachrichtigung, dass ein neues Ticket in ITFlow erstellt wurde. <br>Kunde: %s<br>Priorität: %s<br>Link: %s <br><br><b>%s</b><br>%s';
$lang['email_ticket_new_notification'] = 'Dies ist eine Benachrichtigung, dass ein neues Ticket in ITFlow erstellt wurde.';
$lang['email_ticket_new_client'] = 'Kunde';
$lang['email_ticket_new_priority'] = 'Priorität';
$lang['email_ticket_new_link'] = 'Link';

// Ticket Emails - Reply/Update
$lang['email_ticket_reply_subject'] = 'Ticket %s - %s - Antwort';
$lang['email_ticket_reply_body'] = 'Hallo %s,<br><br>Eine Antwort wurde zu Ihrem Ticket hinzugefügt.<br><br>Ticket: %s<br>Betreff: %s<br><br>%s<br><br><a href="%s">Ticket ansehen</a><br><br>--<br>%s<br>Support<br>%s';
$lang['email_ticket_reply_notification'] = 'Eine Antwort wurde zu Ihrem Ticket hinzugefügt.';
$lang['email_ticket_view'] = 'Ticket ansehen';
$lang['email_ticket_signature_support'] = 'Support';

// Ticket Emails - Status Change
$lang['email_ticket_closed_subject'] = 'Ticket %s - Geschlossen';
$lang['email_ticket_closed_body'] = 'Hallo %s,<br><br>Ihr Ticket wurde geschlossen.<br><br>Ticket: %s<br>Betreff: %s<br><br>Bei Fragen antworten Sie bitte auf diese E-Mail.<br><br><a href="%s">Ticket ansehen</a><br><br>--<br>%s<br>Support<br>%s';
$lang['email_ticket_closed_notification'] = 'Ihr Ticket wurde geschlossen.';

$lang['email_ticket_resolved_subject'] = 'Ticket %s - Gelöst';
$lang['email_ticket_resolved_body'] = 'Hallo %s,<br><br>Ihr Ticket wurde gelöst.<br><br>Ticket: %s<br>Betreff: %s<br><br>Falls das Problem weiterhin besteht, antworten Sie bitte auf diese E-Mail.<br><br><a href="%s">Ticket ansehen</a><br><br>--<br>%s<br>Support<br>%s';
$lang['email_ticket_resolved_notification'] = 'Ihr Ticket wurde gelöst.';

// Payment Emails
$lang['email_payment_subject'] = 'Zahlungsbestätigung - %s';
$lang['email_payment_body'] = 'Hallo %s,<br><br>Wir haben Ihre Zahlung erhalten.<br><br>Betrag: %s<br>Datum: %s<br>Referenz: %s<br><br>Vielen Dank für Ihre Zahlung.<br><br>--<br>%s<br>Buchhaltung<br>%s';
$lang['email_payment_received'] = 'Wir haben Ihre Zahlung erhalten.';
$lang['email_payment_amount'] = 'Betrag';
$lang['email_payment_date'] = 'Datum';
$lang['email_payment_reference'] = 'Referenz';
$lang['email_payment_thank_you'] = 'Vielen Dank für Ihre Zahlung.';

// Password Reset Emails
$lang['email_password_reset_subject'] = 'Passwort zurücksetzen angefordert';
$lang['email_password_reset_body'] = 'Hallo %s,<br><br>Es wurde eine Passwortänderung für Ihr Konto angefordert.<br><br>Klicken Sie auf den unten stehenden Link, um Ihr Passwort zurückzusetzen:<br><a href="%s">Passwort zurücksetzen</a><br><br>Dieser Link läuft in 1 Stunde ab.<br><br>Falls Sie diese Anfrage nicht gestellt haben, ignorieren Sie bitte diese E-Mail.<br><br>--<br>%s';
$lang['email_password_reset_request'] = 'Es wurde eine Passwortänderung für Ihr Konto angefordert.';
$lang['email_password_reset_link'] = 'Klicken Sie auf den unten stehenden Link, um Ihr Passwort zurückzusetzen:';
$lang['email_password_reset_expire'] = 'Dieser Link läuft in 1 Stunde ab.';
$lang['email_password_reset_ignore'] = 'Falls Sie diese Anfrage nicht gestellt haben, ignorieren Sie bitte diese E-Mail.';

// Common Email Elements
$lang['email_signature'] = '--';
$lang['email_view_online'] = 'Online ansehen';
$lang['email_if_questions'] = 'Bei Fragen antworten Sie bitte auf diese E-Mail.';
$lang['email_do_not_reply'] = 'Dies ist eine automatische Nachricht. Bitte antworten Sie nicht auf diese E-Mail.';
