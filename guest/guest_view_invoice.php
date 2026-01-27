<?php

// Load core config before inc_all_guest to set custom page title
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

if (!isset($_GET['invoice_id'], $_GET['url_key'])) {
    require_once "includes/inc_all_guest.php";
    echo "<br><h2>Oops, something went wrong! Please raise a ticket if you believe this is an error.</h2>";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
    exit();
}

$url_key = sanitizeInput($_GET['url_key']);
$invoice_id = intval($_GET['invoice_id']);

// Load invoice data to set custom page title
$sql = mysqli_query(
    $mysqli,
    "SELECT invoice_prefix, invoice_number, client_language, companies.company_name
    FROM invoices
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN companies ON companies.company_id = 1
    WHERE invoice_id = $invoice_id
    AND invoice_url_key = '$url_key'"
);

if (mysqli_num_rows($sql) !== 1) {
    require_once "includes/inc_all_guest.php";
    echo "<br><h2>Oops, something went wrong! Please raise a ticket if you believe this is an error.</h2>";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
    exit();
}

$row = mysqli_fetch_array($sql);
$invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
$invoice_number = intval($row['invoice_number']);
$client_language = nullable_htmlentities($row['client_language']);
$company_name = nullable_htmlentities($row['company_name']);

// Initialize i18n for page title translation
if ($client_language) {
    // Force client language by setting cookie to prevent i18n priority checks from overriding
    $_COOKIE['itflow_language'] = $client_language;
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/i18n.php';
    i18n_init($client_language);
    $view_invoice_text = __("view_invoice", "View Invoice");
} else {
    $view_invoice_text = "View Invoice";
}

// Set custom page title before including header
$page_title_custom = "$company_name - $view_invoice_text $invoice_prefix$invoice_number";

// Debug: ensure variable persists
global $page_title_custom;

// Now include all guest files with header
require_once "includes/inc_all_guest.php";

// Re-query for full invoice data
$sql = mysqli_query(
    $mysqli,
    "SELECT * FROM invoices
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN locations ON clients.client_id = locations.location_client_id AND location_primary = 1
    LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1
    WHERE invoice_id = $invoice_id
    AND invoice_url_key = '$url_key'"
);

$row = mysqli_fetch_array($sql);

$invoice_id = intval($row['invoice_id']);
$invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
$invoice_number = intval($row['invoice_number']);
$invoice_status = nullable_htmlentities($row['invoice_status']);
$invoice_date = nullable_htmlentities($row['invoice_date']);
$invoice_due = nullable_htmlentities($row['invoice_due']);
$invoice_discount = floatval($row['invoice_discount_amount']);
$invoice_amount = floatval($row['invoice_amount']);
$invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
$invoice_note = nullable_htmlentities($row['invoice_note']);
$invoice_category_id = intval($row['invoice_category_id']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
$client_name_escaped = sanitizeInput($row['client_name']);
$client_language = nullable_htmlentities($row["client_language"]);

// Debug: Check if client language is being loaded
error_log("DEBUG: Client language from DB: " . var_export($client_language, true));

// Force client language by setting cookie temporarily to prevent i18n from overriding
if ($client_language) {
    $_COOKIE['itflow_language'] = $client_language;
    i18n_init($client_language); 
    error_log("DEBUG: i18n initialized with language: $client_language");
    error_log("DEBUG: Test translation for 'invoice': " . __("invoice", "Invoice"));
} else {
    error_log("DEBUG: No client language set, i18n not initialized");
}
$location_address = nullable_htmlentities($row['location_address']);
$location_city = nullable_htmlentities($row['location_city']);
$location_state = nullable_htmlentities($row['location_state']);
$location_zip = nullable_htmlentities($row['location_zip']);
$location_country = nullable_htmlentities($row['location_country']);
$contact_email = nullable_htmlentities($row['contact_email']);
$contact_phone_country_code = nullable_htmlentities($row['contact_phone_country_code']);
$contact_phone = nullable_htmlentities(formatPhoneNumber($row['contact_phone'], $contact_phone_country_code, true));
$contact_extension = nullable_htmlentities($row['contact_extension']);
$contact_mobile_country_code = nullable_htmlentities($row['contact_mobile_country_code']);
$contact_mobile = nullable_htmlentities(formatPhoneNumber($row['contact_mobile'], $contact_mobile_country_code, true));
$client_website = nullable_htmlentities($row['client_website']);
$client_currency_code = nullable_htmlentities($row['client_currency_code']);
$client_net_terms = intval($row['client_net_terms']);

$sql = mysqli_query($mysqli, "SELECT * FROM companies, settings WHERE companies.company_id = settings.company_id AND companies.company_id = 1");
$row = mysqli_fetch_array($sql);

$company_name = nullable_htmlentities($row['company_name']);
$company_address = nullable_htmlentities($row['company_address']);
$company_city = nullable_htmlentities($row['company_city']);
$company_state = nullable_htmlentities($row['company_state']);
$company_zip = nullable_htmlentities($row['company_zip']);
$company_country = nullable_htmlentities($row['company_country']);
$company_phone_country_code = nullable_htmlentities($row['company_phone_country_code']);
$company_phone = nullable_htmlentities(formatPhoneNumber($row['company_phone'], $company_phone_country_code, true));
$company_email = nullable_htmlentities($row['company_email']);
$company_website = nullable_htmlentities($row['company_website']);
$company_tax_id = nullable_htmlentities($row['company_tax_id']);
if ($config_invoice_show_tax_id && !empty($company_tax_id)) {
    $company_tax_id_display = __("tax_id", "Tax ID") . ": $company_tax_id";
} else {
    $company_tax_id_display = "";
}
$company_logo = nullable_htmlentities($row['company_logo']);
if (!empty($company_logo)) {
    $company_logo_base64 = base64_encode(file_get_contents("../uploads/settings/$company_logo"));
}
$company_locale = nullable_htmlentities($row['company_locale']);
$config_invoice_footer = nullable_htmlentities($row['config_invoice_footer']); 

// Get Payment Provide Details
$sql = mysqli_query($mysqli, "SELECT * FROM payment_providers WHERE payment_provider_active = 1 LIMIT 1");
$row = mysqli_fetch_array($sql);
$payment_provider_id = intval($row['payment_provider_id']);
$payment_provider_name = nullable_htmlentities($row['payment_provider_name']);
$payment_provider_threshold = floatval($row['payment_provider_threshold']);

//Set Currency Format - use client language if set, otherwise company locale
$locale_for_currency = !empty($client_language) ? $client_language : $company_locale;
$currency_format = numfmt_create($locale_for_currency, NumberFormatter::CURRENCY);

$invoice_tally_total = 0; // Default

//Set Badge color based off of invoice status
$invoice_badge_color = getInvoiceBadgeColor($invoice_status);

//Update status to Viewed only if invoice_status = "Sent"
if ($invoice_status == 'Sent') {
    mysqli_query($mysqli, "UPDATE invoices SET invoice_status = 'Viewed' WHERE invoice_id = $invoice_id");
}

//Mark viewed in history
mysqli_query($mysqli, "INSERT INTO history SET history_status = '$invoice_status', history_description = 'Invoice viewed - $ip - $os - $browser', history_invoice_id = $invoice_id");

if ($invoice_status !== 'Paid') {
    
    appNotify("Invoice Viewed", "Invoice $invoice_prefix$invoice_number has been viewed by $client_name_escaped - $ip - $os - $browser", "/agent/invoice.php?invoice_id=$invoice_id", $client_id);
    
}
$sql_payments = mysqli_query($mysqli, "SELECT * FROM payments, accounts WHERE payment_account_id = account_id AND payment_invoice_id = $invoice_id ORDER BY payments.payment_id DESC");

//Add up all the payments for the invoice and get the total amount paid to the invoice
$sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments WHERE payment_invoice_id = $invoice_id");
$row = mysqli_fetch_array($sql_amount_paid);
$amount_paid = floatval($row['amount_paid']);

// Calculate the balance owed
$balance = $invoice_amount - $amount_paid;

//check to see if overdue
$invoice_color = $invoice_badge_color; // Default
if ($invoice_status !== "Paid" && $invoice_status !== "Draft" && $invoice_status !== "Cancelled" && $invoice_status !== "Non-Billable") {
    $unixtime_invoice_due = strtotime($invoice_due) + 86400;
    if ($unixtime_invoice_due < time()) {
        $invoice_color = "text-danger";
    }
}

// Invoice individual items
$sql_invoice_items = mysqli_query($mysqli, "SELECT * FROM invoice_items WHERE item_invoice_id = $invoice_id ORDER BY item_order ASC");


// Get Total Account Balance
//Add up all the payments for the invoice and get the total amount paid to the invoice
$sql_invoice_amounts = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS invoice_amounts FROM invoices WHERE invoice_client_id = $client_id AND invoice_status != 'Draft' AND invoice_status != 'Cancelled' AND invoice_status != 'Non-Billable'");
$row = mysqli_fetch_array($sql_invoice_amounts);

$account_balance = floatval($row['invoice_amounts']);

$sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments, invoices WHERE payment_invoice_id = invoice_id AND invoice_client_id = $client_id");
$row = mysqli_fetch_array($sql_amount_paid);

$account_amount_paid = floatval($row['amount_paid']);

$account_balance = $account_balance - $account_amount_paid;
//set Text color on balance
if ($balance > 0) {
    $balance_text_color = "text-danger font-weight-bold";
} else {
    $balance_text_color = "";
}

?>

<div class="card">
    <div class="card-header bg-light d-print-none">
        <div class="row">
            <div class="col-12">
                <?php /* Account balance hidden - too busy
                <h4 class="mt-1"><?php echo __("account_balance", "Account Balance"); ?>: <b><?php echo numfmt_format_currency($currency_format, $account_balance, $invoice_currency_code); ?></b></h4>
                */ ?>
                <div class="float-right">
                    <a class="btn btn-default" href="#" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i><?php echo __("print", "Print"); ?></a>
                    <a class="btn btn-default" href="guest_post.php?export_invoice_pdf=<?php echo $invoice_id; ?>&url_key=<?php echo $url_key; ?>">
                        <i class="fa fa-fw fa-download mr-2"></i><?php echo __("download", "Download"); ?>
                    </a>
                    <?php
                    if ($invoice_status !== "Paid" &&
                        $invoice_status  !== "Cancelled" &&
                        $invoice_status !== "Draft" &&
                        $payment_provider_id &&
                        (
                            $payment_provider_threshold == 0 ||
                            $payment_provider_threshold > $invoice_amount
                        ) 
                    ){ 
                        if ($payment_provider_name === 'Stripe') { ?>
                            <a class="btn btn-success" href="guest_pay_invoice_stripe.php?invoice_id=<?php echo $invoice_id; ?>&url_key=<?php echo $url_key; ?>"><i class="fa fa-fw fa-credit-card mr-2"></i><?php echo __("pay_now_card", "Pay Now (Card)"); ?></a>
                        <?php } elseif ($payment_provider_name === 'Mollie') { ?>
                            <a class="btn btn-success" href="guest_pay_invoice_mollie.php?invoice_id=<?php echo $invoice_id; ?>&url_key=<?php echo $url_key; ?>"><i class="fa fa-fw fa-credit-card mr-2"></i><?php echo __("pay_now", "Pay Now"); ?></a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <!-- Top Row: Logo Left, Company Details Right -->
        <div class="row mb-4">
            <div class="col-sm-6">
                <?php if (!empty($company_logo) && file_exists("../uploads/settings/$company_logo")) { ?>
                    <img class="img-fluid" style="max-width: 200px;" src="<?php echo "../uploads/settings/$company_logo"; ?>" alt="Company logo">
                <?php } ?>
            </div>
            <div class="col-sm-6">
                <div class="text-right">
                    <strong><?php echo $company_name; ?></strong><br>
                    <?php echo $company_address; ?><br>
                    <?php echo $company_zip; ?> <?php echo $company_city; ?><br>
                    <?php if ($company_phone) { echo $company_phone . '<br>'; } ?>
                    <?php if ($config_invoice_show_tax_id && !empty($company_tax_id)) { 
                        echo __("tax_id", "Tax ID") . ": " . $company_tax_id; 
                    } ?>
                </div>
            </div>
        </div>

        <!-- Client Details -->
        <div class="row mb-3">
            <div class="col-sm-6">
                <?php echo $client_name; ?><br>
                <?php if ($location_address) { echo $location_address . '<br>'; } ?>
                <?php if ($location_city) { echo "$location_zip $location_city<br>"; } ?>
            </div>
        </div>

        <!-- FACTUUR Heading -->
        <div class="row mb-3">
            <div class="col-12">
                <h3><strong><?php echo __("invoice", "Invoice"); ?></strong></h3>
            </div>
        </div>

        <!-- Invoice Details Table -->
        <div class="row mb-4">
            <div class="col-sm-8">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td style="width: 180px;"><strong><?php echo __("invoice_number_header", "#"); ?></strong></td>
                        <td><strong><?php echo __("invoice_date_header", "Date"); ?></strong></td>
                        <td><strong><?php echo __("invoice_due_header", "Due"); ?></strong></td>
                        <?php if ($config_invoice_show_tax_id && !empty($company_tax_id)) { ?>
                        <td><strong><?php echo __("tax_id", "BTW-nummer"); ?></strong></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td><?php echo "$invoice_prefix$invoice_number"; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($invoice_date)); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($invoice_due)); ?></td>
                        <?php if ($config_invoice_show_tax_id && !empty($company_tax_id)) { ?>
                        <td><?php echo $company_tax_id; ?></td>
                        <?php } ?>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 text-right d-print-none">
                <span class="badge badge-<?php echo $invoice_badge_color; ?> p-2"><?php echo $invoice_status; ?></span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 35%;"><?php echo __("description", "Description"); ?></th>
                                <th class="text-right" style="width: 15%;"><?php echo __("unit_price", "Stukprijs"); ?></th>
                                <th class="text-right" style="width: 15%;"><?php echo __("amount", "Bedrag"); ?></th>
                                <th class="text-right" style="width: 15%;"><?php echo __("tax_rate", "BTW"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $total_tax = 0.00;
                            $sub_total = 0.00 - $invoice_discount;
                            $item_number = 1;

                            while ($row = mysqli_fetch_array($sql_invoice_items)) {
                                $item_id = intval($row['item_id']);
                                $item_name = nullable_htmlentities($row['item_name']);
                                $item_description = nullable_htmlentities($row['item_description']);
                                $item_quantity = floatval($row['item_quantity']);
                                $item_price = floatval($row['item_price']);
                                $item_tax = floatval($row['item_tax']);
                                $item_total = floatval($row['item_total']);
                                $total_tax = $item_tax + $total_tax;
                                $sub_total = $item_price * $item_quantity + $sub_total;

                                // Get tax percentage for this item
                                $sql_tax = mysqli_query($mysqli, "SELECT tax_percent FROM taxes WHERE tax_id = " . intval($row['item_tax_id']));
                                $tax_row = mysqli_fetch_array($sql_tax);
                                $tax_percent = isset($tax_row['tax_percent']) ? floatval($tax_row['tax_percent']) : 0;

                                ?>

                                <tr>
                                    <td><?php echo $item_number++; ?></td>
                                    <td>
                                        <strong><?php echo $item_name; ?></strong>
                                        <?php if ($item_description) { echo '<br><small>' . nl2br($item_description) . '</small>'; } ?>
                                    </td>
                                    <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_price, $invoice_currency_code); ?></td>
                                    <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_price * $item_quantity, $invoice_currency_code); ?></td>
                                    <td class="text-right"><?php echo number_format($tax_percent, 0); ?>%</td>
                                </tr>

                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals Section -->
        <div class="row mb-3">
            <div class="col-sm-7">
                <?php if (!empty($invoice_note)) { ?>
                    <div class="card">
                        <div class="card-body">
                            <strong><?php echo __("notes", "Notes"); ?>:</strong><br>
                            <?php echo nl2br($invoice_note); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-5">
                <table class="table table-borderless mb-0">
                    <tbody>
                    <tr>
                        <td class="text-right"><strong><?php echo __("subtotal", "Subtotaal"); ?></strong></td>
                        <td class="text-right" style="width: 150px;"><strong><?php echo numfmt_format_currency($currency_format, $sub_total + $invoice_discount, $invoice_currency_code); ?></strong></td>
                    </tr>
                    <?php if ($invoice_discount > 0) { ?>
                        <tr>
                            <td class="text-right"><?php echo __("discount", "Korting"); ?>:</td>
                            <td class="text-right">-<?php echo numfmt_format_currency($currency_format, $invoice_discount, $invoice_currency_code); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($total_tax > 0) { ?>
                        <tr>
                            <td class="text-right"><?php echo __("tax", "BTW"); ?>:</td>
                            <td class="text-right"><?php echo numfmt_format_currency($currency_format, $total_tax, $invoice_currency_code); ?></td>
                        </tr>
                    <?php } ?>
                    <tr style="border-top: 2px solid #000;">
                        <td class="text-right"><strong><?php echo __("total", "Totaal"); ?></strong></td>
                        <td class="text-right"><strong><?php echo numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></strong></td>
                    </tr>
                    <?php if ($amount_paid > 0) { ?>
                        <tr>
                            <td class="text-right"><div class="text-success"><?php echo __("paid", "Betaald"); ?>:</div></td>
                            <td class="text-right text-success"><?php echo numfmt_format_currency($currency_format, $amount_paid, $invoice_currency_code); ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="h5 text-bold">
                        <td class="text-right"><?php echo __("balance", "Openstaand"); ?>:</td>
                        <td class="text-right"><?php echo numfmt_format_currency($currency_format, $balance, $invoice_currency_code); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="mt-5">

        <div class="text-center text-secondary"><?php echo nl2br($config_invoice_footer); ?></div>
    </div>
</div>

<?php

// CURRENT INVOICES

$sql_current_invoices = mysqli_query($mysqli, "SELECT * FROM invoices WHERE invoice_client_id = $client_id AND invoice_due > CURDATE() AND(invoice_status = 'Sent' OR invoice_status = 'Viewed' OR invoice_status = 'Partial') ORDER BY invoice_number DESC");

$current_invoices_count = mysqli_num_rows($sql_current_invoices);

if ($current_invoices_count > 0) { ?>

<div class="card d-print-none card-dark">
    <div class="card-header">
        <strong><i class="fas fa-fw fa-clock mr-2"></i><b><?php echo $current_invoices_count; ?></b> <?php echo __("current_invoices", "Current Invoices"); ?></strong>
    </div>
    <div card="card-body">
        <table class="table table-sm">
            <thead>
            <tr>
                <th class="text-center"><?php echo __("invoice", "Invoice"); ?></th>
                <th><?php echo __("invoice_date", "Date"); ?></th>
                <th><?php echo __("invoice_due", "Due"); ?></th>
                <th class="text-right"><?php echo __("amount", "Amount"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php

            while ($row = mysqli_fetch_array($sql_current_invoices)) {
                $invoice_id = intval($row['invoice_id']);
                $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
                $invoice_number = intval($row['invoice_number']);
                $invoice_date = nullable_htmlentities($row['invoice_date']);
                $invoice_due = nullable_htmlentities($row['invoice_due']);
                $invoice_amount = floatval($row['invoice_amount']);
                $invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
                $invoice_url_key = nullable_htmlentities($row['invoice_url_key']);
                $invoice_tally_total = $invoice_amount + $invoice_tally_total;
                $difference = strtotime($invoice_due) - time();
                $days = floor($difference / (60*60*24));

                ?>

                <tr <?php if ($_GET['invoice_id'] == $invoice_id) { echo "class='table-primary'"; } ?>>
                    <th class="text-center"><a href="guest_view_invoice.php?invoice_id=<?php echo $invoice_id; ?>&url_key=<?php echo $invoice_url_key; ?>"><?php echo "$invoice_prefix$invoice_number"; ?></a></th>
                    <td><?php echo $invoice_date; ?></td>
                    <td><?php echo $invoice_due; ?> (Due in <?php echo $days; ?> Days)</td>
                    <td class="text-right text-bold"><?php echo numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></td>
                </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<?php

}

?>

<?php

// OUTSTANDING INVOICES

$sql_outstanding_invoices = mysqli_query($mysqli, "SELECT * FROM invoices WHERE invoice_client_id = $client_id AND invoice_due < CURDATE() AND(invoice_status = 'Sent' OR invoice_status = 'Viewed' OR invoice_status = 'Partial') ORDER BY invoice_date DESC");

$outstanding_invoices_count = mysqli_num_rows($sql_outstanding_invoices);

if ($outstanding_invoices_count > 0) { ?>

<div class="card d-print-none card-danger">
    <div class="card-header">
        <strong><i class="fa fa-fw fa-exclamation-triangle mr-2"></i><b><?php echo $outstanding_invoices_count; ?></b> Outstanding Invoices</strong>
    </div>
    <div card="card-body">
        <table class="table table-sm">
            <thead>
            <tr>
                <th class="text-center">Invoice</th>
                <th>Date</th>
                <th>Due</th>
                <th class="text-right">Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php

            while ($row = mysqli_fetch_array($sql_outstanding_invoices)) {
                $invoice_id = intval($row['invoice_id']);
                $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
                $invoice_number = intval($row['invoice_number']);
                $invoice_date = nullable_htmlentities($row['invoice_date']);
                $invoice_due = nullable_htmlentities($row['invoice_due']);
                $invoice_amount = floatval($row['invoice_amount']);
                $invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
                $invoice_url_key = nullable_htmlentities($row['invoice_url_key']);
                $invoice_tally_total = $invoice_amount + $invoice_tally_total;
                $difference = time() - strtotime($invoice_due);
                $days = floor($difference / (60*60*24));

                ?>

                <tr <?php if ($_GET['invoice_id'] == $invoice_id) { echo "class='table-primary'"; } ?>>
                    <th class="text-center"><a href="guest_view_invoice.php?invoice_id=<?php echo $invoice_id; ?>&url_key=<?php echo $invoice_url_key; ?>"><?php echo "$invoice_prefix$invoice_number"; ?></a></th>
                    <td><?php echo $invoice_date; ?></td>
                    <td class="text-danger"><?php echo $invoice_due; ?> (Over Due by <?php echo $days; ?> Days)</td>
                    <td class="text-right text-bold"><?php echo numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></td>
                </tr>

                <?php
            }
            ?>

            </tbody>
        </table>
    </div>
</div>

<?php } // End previous unpaid invoices

require_once $_SERVER['DOCUMENT_ROOT']  . '/includes/footer.php';
