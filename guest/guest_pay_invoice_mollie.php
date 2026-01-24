<?php

require_once 'includes/inc_all_guest.php';

// Get invoice and client details
$invoice_url_key = sanitizeInput($_GET['url_key']);
$invoice_id = intval($_GET['invoice_id']);

// Query invoice details
$sql = mysqli_query(
    $mysqli,
    "SELECT * FROM invoices
     LEFT JOIN clients ON invoice_client_id = client_id
     WHERE invoice_id = $invoice_id
     AND invoice_url_key = '$invoice_url_key'
     AND invoice_status NOT IN ('Draft', 'Paid', 'Cancelled')
     LIMIT 1"
);

// Ensure valid invoice
if (!$sql || mysqli_num_rows($sql) !== 1) {
    echo "<br><h2>Oops, something went wrong! Please ensure you have the correct URL and have not already paid this invoice.</h2>";
    error_log("Mollie payment error - Invoice with ID $invoice_id not found or not eligible.");
    exit();
}

$row = mysqli_fetch_array($sql);
$invoice_id = intval($row['invoice_id']);
$invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
$invoice_number = intval($row['invoice_number']);
$invoice_amount = floatval($row['invoice_amount']);
$invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
$client_email = nullable_htmlentities($row['client_email'] ?? '');

// Get company info
$sql_company = mysqli_query($mysqli, "SELECT * FROM companies WHERE company_id = 1");
$company_row = mysqli_fetch_array($sql_company);
$company_name = nullable_htmlentities($company_row['company_name']);
$company_locale = nullable_htmlentities($company_row['company_locale']);

// Add up all payments made to the invoice
$sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments WHERE payment_invoice_id = $invoice_id");
$amount_paid = floatval(mysqli_fetch_array($sql_amount_paid)['amount_paid']);
$balance_to_pay = round($invoice_amount - $amount_paid, 2);

// Check if invoice has balance
if ($balance_to_pay <= 0) {
    echo "<br><h2>This invoice has already been paid in full.</h2>";
    require_once 'includes/guest_footer.php';
    exit();
}

// Get Mollie config from payment_providers table
$mollie_provider = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM payment_providers WHERE payment_provider_name = 'Mollie' LIMIT 1"));

if (!$mollie_provider) {
    echo "<br><h2>Payment provider not configured. Please contact support.</h2>";
    require_once 'includes/guest_footer.php';
    error_log("Mollie payment error - Mollie not configured in payment_providers table.");
    exit();
}

$mollie_api_key = nullable_htmlentities($mollie_provider['payment_provider_private_key']);
$mollie_account = intval($mollie_provider['payment_provider_account']);

if (empty($mollie_api_key)) {
    echo "<br><h2>Payment provider not configured. Please contact support.</h2>";
    require_once 'includes/guest_footer.php';
    error_log("Mollie payment error - Mollie API key not set.");
    exit();
}

// Initialize Mollie
require_once '../plugins/mollie-api-php/init.php';

try {
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey($mollie_api_key);

    // Create payment
    $payment = $mollie->payments->create([
        "amount" => [
            "currency" => $invoice_currency_code,
            "value" => number_format($balance_to_pay, 2, '.', '')
        ],
        "description" => "$company_name - Invoice $invoice_prefix$invoice_number",
        "redirectUrl" => "https://$config_base_url/guest/guest_view_invoice.php?invoice_id=$invoice_id&url_key=$invoice_url_key",
        "webhookUrl" => "https://$config_base_url/guest/guest_mollie_webhook.php",
        "metadata" => [
            "invoice_id" => $invoice_id,
            "client_id" => $client_id,
            "invoice_number" => "$invoice_prefix$invoice_number"
        ]
    ]);

    // Log payment creation
    mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Payment Link Created', history_description = 'Mollie payment initiated for $invoice_currency_code $balance_to_pay - Payment ID: {$payment->id}', history_invoice_id = $invoice_id");
    
    logAction("Invoice", "Payment", "Mollie payment link created for invoice $invoice_prefix$invoice_number - Amount: $invoice_currency_code $balance_to_pay", $client_id, $invoice_id);

    // Redirect to Mollie payment page
    header("Location: " . $payment->getCheckoutUrl());
    exit();

} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "<br><h2>Error creating payment. Please try again or contact support.</h2>";
    error_log("Mollie payment error - API exception: " . $e->getMessage());
    require_once 'includes/guest_footer.php';
    exit();
}

?>
