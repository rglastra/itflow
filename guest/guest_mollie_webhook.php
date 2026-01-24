<?php

/**
 * Mollie Webhook Handler
 * Receives payment status updates from Mollie and updates invoice accordingly
 */

require_once "../config.php";
require_once "../includes/inc_set_timezone.php";
require_once "../functions.php";

// Get Mollie config from payment_providers table
$mollie_provider = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM payment_providers WHERE payment_provider_name = 'Mollie' LIMIT 1"));

if (!$mollie_provider) {
    error_log("Mollie webhook error - Mollie not configured in payment_providers table.");
    http_response_code(500);
    exit("Mollie not configured");
}

$mollie_api_key = nullable_htmlentities($mollie_provider['payment_provider_private_key']);
$mollie_account = intval($mollie_provider['payment_provider_account']);
$mollie_expense_vendor = intval($mollie_provider['payment_provider_expense_vendor']);
$mollie_expense_category = intval($mollie_provider['payment_provider_expense_category']);
$mollie_percentage_fee = floatval($mollie_provider['payment_provider_expense_percentage_fee']);
$mollie_flat_fee = floatval($mollie_provider['payment_provider_expense_flat_fee']);

if (empty($mollie_api_key)) {
    error_log("Mollie webhook error - Mollie API key not set.");
    http_response_code(500);
    exit("Mollie API key not configured");
}

// Get payment ID from webhook
if (!isset($_POST['id'])) {
    error_log("Mollie webhook error - No payment ID provided.");
    http_response_code(400);
    exit("No payment ID");
}

$payment_id = sanitizeInput($_POST['id']);

// Initialize Mollie
require_once '../plugins/mollie-api-php/init.php';

try {
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey($mollie_api_key);

    // Retrieve payment from Mollie
    $payment = $mollie->payments->get($payment_id);

    // Get invoice details from metadata
    $invoice_id = intval($payment->metadata->invoice_id);
    $client_id = intval($payment->metadata->client_id);
    $invoice_number = sanitizeInput($payment->metadata->invoice_number);

    // Query invoice to verify it exists and get details
    $invoice_sql = mysqli_query(
        $mysqli,
        "SELECT * FROM invoices
         LEFT JOIN clients ON invoice_client_id = client_id
         LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1
         WHERE invoice_id = $invoice_id
         AND invoice_status NOT IN ('Draft', 'Cancelled')
         LIMIT 1"
    );

    if (!$invoice_sql || mysqli_num_rows($invoice_sql) !== 1) {
        error_log("Mollie webhook error - Invoice with ID $invoice_id not found. Payment ID: $payment_id");
        http_response_code(404);
        exit("Invoice not found");
    }

    $invoice_row = mysqli_fetch_array($invoice_sql);
    $invoice_prefix = nullable_htmlentities($invoice_row['invoice_prefix']);
    $invoice_number_db = intval($invoice_row['invoice_number']);
    $invoice_amount = floatval($invoice_row['invoice_amount']);
    $invoice_currency_code = nullable_htmlentities($invoice_row['invoice_currency_code']);
    $client_name = nullable_htmlentities($invoice_row['client_name']);
    $contact_name = nullable_htmlentities($invoice_row['contact_name'] ?? '');
    $contact_email = nullable_htmlentities($invoice_row['contact_email'] ?? '');

    // Handle payment status
    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        
        // Payment was successful
        $payment_date = date('Y-m-d', strtotime($payment->paidAt));
        $payment_amount = floatval($payment->amount->value);
        $payment_currency = strtoupper($payment->amount->currency);

        // Verify currency matches
        if ($payment_currency !== $invoice_currency_code) {
            error_log("Mollie webhook error - Currency mismatch. Expected $invoice_currency_code, got $payment_currency. Payment ID: $payment_id");
            http_response_code(400);
            exit("Currency mismatch");
        }

        // Check if payment already exists
        $existing_payment = mysqli_query($mysqli, "SELECT payment_id FROM payments WHERE payment_reference = 'Mollie - $payment_id' LIMIT 1");
        
        if (mysqli_num_rows($existing_payment) > 0) {
            error_log("Mollie webhook - Payment already recorded. Payment ID: $payment_id");
            http_response_code(200);
            exit("Payment already recorded");
        }

        // Calculate amount paid so far
        $sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments WHERE payment_invoice_id = $invoice_id");
        $amount_paid_before = floatval(mysqli_fetch_array($sql_amount_paid)['amount_paid']);
        $total_amount_paid = $amount_paid_before + $payment_amount;

        // Insert payment record
        mysqli_query($mysqli, "INSERT INTO payments SET 
            payment_date = '$payment_date', 
            payment_amount = $payment_amount, 
            payment_currency_code = '$payment_currency', 
            payment_account_id = $mollie_account, 
            payment_method = 'Mollie', 
            payment_reference = 'Mollie - $payment_id', 
            payment_invoice_id = $invoice_id");

        $payment_record_id = mysqli_insert_id($mysqli);

        // Update invoice status
        if ($total_amount_paid >= $invoice_amount) {
            // Fully paid
            mysqli_query($mysqli, "UPDATE invoices SET invoice_status = 'Paid' WHERE invoice_id = $invoice_id");
            mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Paid', history_description = 'Invoice paid in full via Mollie - $payment_id', history_invoice_id = $invoice_id");
            
            // Send payment receipt email
            $client_language = getClientLanguage($client_id);
            $email_subject = getEmailText($client_language, 'email_payment_received_subject', ['invoice_number' => "$invoice_prefix$invoice_number_db"]);
            $email_body = getEmailText($client_language, 'email_payment_received_body', [
                'contact_name' => $contact_name,
                'payment_amount' => "$payment_currency $payment_amount",
                'invoice_number' => "$invoice_prefix$invoice_number_db",
                'company_name' => $company_name ?? 'ITFlow'
            ]);

            if (!empty($contact_email)) {
                addToMailQueue([
                    'recipient' => $contact_email,
                    'recipient_name' => $contact_name,
                    'subject' => $email_subject,
                    'body' => $email_body
                ]);
            }

            logAction("Invoice", "Payment", "Received full payment of $payment_currency $payment_amount for invoice $invoice_prefix$invoice_number_db via Mollie", $client_id, $invoice_id);

        } else {
            // Partial payment
            mysqli_query($mysqli, "UPDATE invoices SET invoice_status = 'Partial' WHERE invoice_id = $invoice_id");
            mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Partial', history_description = 'Partial payment of $payment_currency $payment_amount via Mollie - $payment_id', history_invoice_id = $invoice_id");
            
            logAction("Invoice", "Payment", "Received partial payment of $payment_currency $payment_amount for invoice $invoice_prefix$invoice_number_db via Mollie", $client_id, $invoice_id);
        }

        // EXPENSE: Mollie gateway fee as an expense (if configured)
        if ($mollie_expense_vendor > 0 && $mollie_expense_category > 0 && ($mollie_percentage_fee > 0 || $mollie_flat_fee > 0)) {
            $expense_amount = ($payment_amount * $mollie_percentage_fee) + $mollie_flat_fee;
            $expense_description = "Mollie payment processing fee for invoice $invoice_prefix$invoice_number_db (Payment ID: $payment_id)";
            
            mysqli_query($mysqli, "INSERT INTO expenses SET 
                expense_description = '$expense_description',
                expense_amount = $expense_amount,
                expense_currency_code = '$payment_currency',
                expense_date = '$payment_date',
                expense_reference = 'Mollie - $payment_id',
                expense_payment_method = 'Mollie',
                expense_vendor_id = $mollie_expense_vendor,
                expense_client_id = $client_id,
                expense_category_id = $mollie_expense_category,
                expense_account_id = $mollie_account");

            logAction("Expense", "Create", "Created Mollie gateway fee expense for invoice $invoice_prefix$invoice_number_db - Amount: $payment_currency $expense_amount", $client_id);
        }

        // Notify
        appNotify("Invoice Payment Received", "Payment of $payment_currency $payment_amount received via Mollie for invoice $invoice_prefix$invoice_number_db - Client: $client_name", "/agent/invoice.php?invoice_id=$invoice_id", $client_id);

        http_response_code(200);
        echo "Payment processed successfully";

    } elseif ($payment->isOpen()) {
        // Payment is still pending
        error_log("Mollie webhook - Payment still pending. Payment ID: $payment_id");
        http_response_code(200);
        exit("Payment pending");

    } elseif ($payment->isCanceled()) {
        // Payment was canceled
        mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Payment Cancelled', history_description = 'Mollie payment cancelled - $payment_id', history_invoice_id = $invoice_id");
        logAction("Invoice", "Payment", "Mollie payment cancelled for invoice $invoice_prefix$invoice_number_db", $client_id, $invoice_id);

        http_response_code(200);
        echo "Payment cancelled";

    } elseif ($payment->isExpired()) {
        // Payment expired
        mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Payment Expired', history_description = 'Mollie payment expired - $payment_id', history_invoice_id = $invoice_id");
        logAction("Invoice", "Payment", "Mollie payment expired for invoice $invoice_prefix$invoice_number_db", $client_id, $invoice_id);

        http_response_code(200);
        echo "Payment expired";

    } elseif ($payment->isFailed()) {
        // Payment failed
        mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Payment Failed', history_description = 'Mollie payment failed - $payment_id', history_invoice_id = $invoice_id");
        logAction("Invoice", "Payment", "Mollie payment failed for invoice $invoice_prefix$invoice_number_db", $client_id, $invoice_id);

        http_response_code(200);
        echo "Payment failed";

    } else {
        // Unknown status
        error_log("Mollie webhook - Unknown payment status. Payment ID: $payment_id, Status: " . $payment->status);
        http_response_code(200);
        exit("Unknown payment status");
    }

} catch (\Mollie\Api\Exceptions\ApiException $e) {
    error_log("Mollie webhook error - API exception: " . $e->getMessage());
    http_response_code(500);
    exit("API error: " . $e->getMessage());
}

?>
