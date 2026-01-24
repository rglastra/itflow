<?php
/**
 * Nederlands (NL) - E-mail Vertalingen
 * E-mailsjablonen voor facturen, offertes, tickets en notificaties
 */

global $lang;

// E-mail Begroetingen
$lang['email_hello'] = 'Hallo';
$lang['email_regards'] = 'Met vriendelijke groet';
$lang['email_thank_you'] = 'Bedankt';

// Factuur E-mails
$lang['email_invoice_subject_paid'] = 'Factuur %s Ontvangstbevestiging';
$lang['email_invoice_subject'] = 'Factuur %s';
$lang['email_invoice_body_paid'] = 'Hallo %s,<br><br>Klik op onderstaande link om uw factuur betreffende "%s" gemarkeerd als <b>betaald</b> te bekijken.<br><br><a href="%s">Factuur Link</a><br><br><br>--<br>%s - Financiële Administratie<br>%s<br>%s';
$lang['email_invoice_body'] = 'Hallo %s,<br><br>Bekijk hieronder de details van uw factuur betreffende "%s".<br><br>Factuur: %s<br>Factuurdatum: %s<br>Totaal: %s<br>Openstaand Saldo: %s<br>Vervaldatum: %s<br><br><br>Om uw factuur te bekijken, klik <a href="%s">hier</a>.<br><br><br>--<br>%s - Financiële Administratie<br>%s<br>%s';
$lang['email_invoice_line_invoice'] = 'Factuur';
$lang['email_invoice_line_issue_date'] = 'Factuurdatum';
$lang['email_invoice_line_total'] = 'Totaal';
$lang['email_invoice_line_balance'] = 'Openstaand Saldo';
$lang['email_invoice_line_due_date'] = 'Vervaldatum';
$lang['email_invoice_view_link'] = 'Om uw factuur te bekijken, klik';
$lang['email_invoice_view_here'] = 'hier';
$lang['email_invoice_signature_billing'] = 'Financiële Administratie';

// Offerte E-mails
$lang['email_quote_subject'] = 'Offerte [%s]';
$lang['email_quote_body'] = 'Hallo %s,<br><br>Bedankt voor uw aanvraag, wij zijn verheugd u de volgende offerte te mogen aanbieden.<br><br><br>%s<br>Totale Kosten: %s<br><br><br>Bekijk en accepteer uw offerte online <a href="%s">hier</a><br><br><br>--<br>%s - Verkoop<br>%s<br>%s';
$lang['email_quote_thank_you'] = 'Bedankt voor uw aanvraag, wij zijn verheugd u de volgende offerte te mogen aanbieden.';
$lang['email_quote_total_cost'] = 'Totale Kosten';
$lang['email_quote_view_accept'] = 'Bekijk en accepteer uw offerte online';
$lang['email_quote_signature_sales'] = 'Verkoop';

// Ticket E-mails - Nieuw Ticket
$lang['email_ticket_created_subject'] = 'Ticket Aangemaakt [%s] - %s';
$lang['email_ticket_created_body'] = '<i style=\'color: #808080\'>##- Typ uw reactie boven deze regel -##</i><br><br>Hallo %s,<br><br>Een ticket betreffende "%s" is voor u aangemaakt.<br><br>--------------------------------<br>%s--------------------------------<br><br>Ticket: %s<br>Onderwerp: %s<br>Status: Open<br>Portaal: <a href=\'%s\'>Ticket bekijken</a><br><br>--<br>%s - Ondersteuning<br>%s<br>%s';
$lang['email_ticket_new_subject'] = 'ITFlow - Nieuw Ticket - %s: %s';
$lang['email_ticket_new_body'] = 'Hallo, <br><br>Dit is een melding dat er een nieuw ticket is aangemaakt in ITFlow. <br>Klant: %s<br>Prioriteit: %s<br>Link: %s <br><br><b>%s</b><br>%s';
$lang['email_ticket_new_notification'] = 'Dit is een melding dat er een nieuw ticket is aangemaakt in ITFlow.';
$lang['email_ticket_new_client'] = 'Klant';
$lang['email_ticket_new_priority'] = 'Prioriteit';
$lang['email_ticket_new_link'] = 'Link';

// Ticket E-mails - Reactie/Update
$lang['email_ticket_reply_subject'] = 'ITFlow Ticket bijgewerkt - [%s] %s';
$lang['email_ticket_reply_body'] = 'Hallo %s,<br><br>Een nieuwe reactie is toegevoegd aan onderstaand ticket, bekijk ITFlow voor volledige details.<br><br>Klant: %s<br>Ticket: %s<br>Onderwerp: %s<br><br>%s';
$lang['email_ticket_reply_notification'] = 'Een reactie is toegevoegd aan uw ticket.';
$lang['email_ticket_view'] = 'Ticket Bekijken';
$lang['email_ticket_signature_support'] = 'Ondersteuning';

// Ticket E-mails - Status Wijziging
$lang['email_ticket_closed_subject'] = 'Ticket %s - Gesloten';
$lang['email_ticket_closed_body'] = 'Hallo %s,<br><br>Uw ticket is gesloten.<br><br>Ticket: %s<br>Onderwerp: %s<br><br>Bij vragen kunt u reageren op deze e-mail.<br><br><a href="%s">Ticket Bekijken</a><br><br>--<br>%s<br>Ondersteuning<br>%s';
$lang['email_ticket_closed_notification'] = 'Uw ticket is gesloten.';

$lang['email_ticket_resolved_subject'] = 'Ticket %s - Opgelost';
$lang['email_ticket_resolved_body'] = 'Hallo %s,<br><br>Uw ticket is opgelost.<br><br>Ticket: %s<br>Onderwerp: %s<br><br>Als het probleem aanhoudt, reageer dan op deze e-mail.<br><br><a href="%s">Ticket Bekijken</a><br><br>--<br>%s<br>Ondersteuning<br>%s';
$lang['email_ticket_resolved_notification'] = 'Uw ticket is opgelost.';

// Wachtwoord Resetten E-mails
$lang['email_password_reset_subject'] = 'Wachtwoord resetten voor %s Klantportaal';
$lang['email_password_reset_body'] = 'Hallo %s,<br><br>Iemand (waarschijnlijk u) heeft een nieuw wachtwoord aangevraagd voor uw account op het %s Klantportaal. <br><br><b>Klik <a href=\'%s\'>hier</a> om uw wachtwoord te resetten.</b> <br><br>Of kopieer en plak deze URL in uw browser:<br> %s<br><br><i>Als u deze wijziging niet heeft aangevraagd, kunt u deze e-mail negeren.</i><br><br>--<br>%s - Ondersteuning<br>%s<br>%s';
$lang['email_password_reset_confirm_subject'] = 'Wachtwoord reset bevestiging voor %s Klantportaal';
$lang['email_password_reset_confirm_body'] = 'Hallo %s,<br><br>Uw wachtwoord voor uw account op het %s Klantportaal is succesvol gereset. U bent klaar om in te loggen! <br><br><b>Als u uw wachtwoord niet heeft gereset, neem dan DIRECT contact op.</b><br><br>--<br>%s - Ondersteuning<br>%s<br>%s';
$lang['email_password_reset_request'] = 'Er is een wachtwoord reset aangevraagd voor uw account.';
$lang['email_password_reset_link'] = 'Klik op onderstaande link om uw wachtwoord te resetten:';
$lang['email_password_reset_expire'] = 'Deze link verloopt over 1 uur.';
$lang['email_password_reset_ignore'] = 'Als u deze reset niet heeft aangevraagd, negeer deze e-mail dan.';

// Betalingsontvangst E-mails
$lang['email_payment_received_subject'] = 'Betaling Ontvangen - Factuur %s';
$lang['email_payment_received_body'] = 'Hallo %s,<br><br>Wij hebben uw volledige betaling ontvangen van %s voor factuur <a href=\'%s\'>%s</a>. Bewaar deze e-mail als ontvangstbewijs voor uw administratie.<br><br>Betaald Bedrag: %s<br>Betalingsmethode: %s<br>Betalingsreferentie: %s<br><br>Bedankt voor uw opdracht!<br><br><br>--<br>%s - Financiële Administratie<br>%s<br>%s';
$lang['email_payment_partial_subject'] = 'Gedeeltelijke Betaling Ontvangen - Factuur %s';
$lang['email_payment_partial_body'] = 'Hallo %s,<br><br>Wij hebben een gedeeltelijke betaling ontvangen van %s die is verwerkt voor factuur <a href=\'%s\'>%s</a>. Bewaar deze e-mail als ontvangstbewijs voor uw administratie.<br><br>Betaald Bedrag: %s<br>Betalingsmethode: %s<br>Betalingsreferentie: %s<br>Openstaand Saldo: %s<br><br>Bedankt voor uw opdracht!<br><br><br>~<br>%s - Financiële Administratie<br>%s<br>%s';
$lang['email_payment_subject'] = 'Betalingsontvangst - %s';
$lang['email_payment_body'] = 'Hallo %s,<br><br>Wij hebben uw betaling ontvangen.<br><br>Bedrag: %s<br>Datum: %s<br>Referentie: %s<br><br>Bedankt voor uw betaling.<br><br>--<br>%s<br>Financiële Administratie<br>%s';
$lang['email_payment_received'] = 'Wij hebben uw betaling ontvangen.';
$lang['email_payment_amount'] = 'Bedrag';
$lang['email_payment_date'] = 'Datum';
$lang['email_payment_reference'] = 'Referentie';
$lang['email_payment_thank_you'] = 'Bedankt voor uw betaling.';

// Algemene E-mail Elementen
$lang['email_signature'] = '--';
$lang['email_view_online'] = 'Online bekijken';
$lang['email_if_questions'] = 'Bij vragen kunt u reageren op deze e-mail.';
$lang['email_do_not_reply'] = 'Dit is een geautomatiseerd bericht. Reageer niet op deze e-mail.';
