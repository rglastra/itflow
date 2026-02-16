<?php

require_once "includes/inc_all_reports.php";

enforceUserPermission('module_support');

/**
 * Convert seconds to "HH:MM:SS" (supports totals > 24h by using hours > 24)
 */
function secondsToHmsString($seconds) {
    $seconds = (int) max(0, $seconds);
    $hours = intdiv($seconds, 3600);
    $minutes = intdiv($seconds % 3600, 60);
    $secs = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
}

/**
 * 15-minute round up, return decimal hours in 0.25 increments
 * NOTE: In this report, billed hours are calculated per TICKET total
 * (sum of reply time within range, then rounded up to nearest 15 minutes).
 */
function secondsToQuarterHourDecimal($seconds) {
    $seconds = (int) max(0, $seconds);
    if ($seconds === 0) return 0.00;

    $quarters = (int) ceil($seconds / 900); // 900 seconds = 15 minutes
    return $quarters * 0.25;
}

/**
 * Validate YYYY-MM-DD
 */
function isValidDateYmd($s) {
    return is_string($s) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $s);
}

// Default range: current month
$from = isset($_GET['from']) ? $_GET['from'] : date('Y-m-01');
$to   = isset($_GET['to'])   ? $_GET['to']   : date('Y-m-t');

if (!isValidDateYmd($from)) $from = date('Y-m-01');
if (!isValidDateYmd($to))   $to   = date('Y-m-t');

// Inclusive datetime bounds
$from_dt = $from . " 00:00:00";
$to_dt   = $to   . " 23:59:59";

$billable_only = (isset($_GET['billable_only']) && (int)$_GET['billable_only'] === 1) ? 1 : 0;

// Ticket-level billable flag (same as your original report)
$billable_sql = $billable_only ? " AND t.ticket_billable = 1 " : "";

/**
 * Query returns ONLY replies that have time_worked and are within date range.
 * Reply content column = tr.ticket_reply
 */
$stmt = $mysqli->prepare("
    SELECT
        c.client_id,
        c.client_name,
        t.ticket_id,
        t.ticket_prefix,
        t.ticket_number,
        t.ticket_subject,

        tr.ticket_reply_id,
        tr.ticket_reply_created_at,
        tr.ticket_reply_time_worked,
        TIME_TO_SEC(tr.ticket_reply_time_worked) AS reply_time_seconds,
        tr.ticket_reply AS reply_content

    FROM tickets t
    INNER JOIN clients c
        ON c.client_id = t.ticket_client_id

    INNER JOIN ticket_replies tr
        ON tr.ticket_reply_ticket_id = t.ticket_id
        AND tr.ticket_reply_time_worked IS NOT NULL
        AND TIME_TO_SEC(tr.ticket_reply_time_worked) > 0
        AND tr.ticket_reply_created_at BETWEEN ? AND ?

    WHERE c.client_archived_at IS NULL
      $billable_sql

    ORDER BY c.client_name ASC,
             t.ticket_number ASC,
             t.ticket_id ASC,
             tr.ticket_reply_created_at ASC
");
$stmt->bind_param("ss", $from_dt, $to_dt);
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="card">
    <div class="card-header bg-dark py-2">
        <h3 class="card-title mt-2">
            <i class="fas fa-fw fa-life-ring mr-2"></i>
            Client Time Detail Audit Report (<?php echo nullable_htmlentities($from); ?> to <?php echo nullable_htmlentities($to); ?>)
            <?php if ($billable_only) { ?>
                <span class="badge badge-success ml-2">Billable Only</span>
            <?php } ?>
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary d-print-none" onclick="window.print();">
                <i class="fas fa-fw fa-print mr-2"></i>Print
            </button>
        </div>
    </div>

    <div class="card-header d-print-none">
        <!-- Filters -->
        <form class="mb-3">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="mb-1">From</label>
                    <input type="date" class="form-control" name="from" value="<?php echo nullable_htmlentities($from); ?>">
                </div>

                <div class="col-md-3 mb-2">
                    <label class="mb-1">To</label>
                    <input type="date" class="form-control" name="to" value="<?php echo nullable_htmlentities($to); ?>">
                </div>

                <div class="col-md-4 mb-2 d-flex align-items-end">
                    <div class="custom-control custom-checkbox">
                        <input
                            type="checkbox"
                            class="custom-control-input"
                            id="billable_only"
                            name="billable_only"
                            value="1"
                            <?php if ($billable_only) echo 'checked'; ?>
                        >
                        <label class="custom-control-label" for="billable_only">Billable tickets only</label>
                    </div>
                </div>

                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-fw fa-filter mr-1"></i>Apply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive-sm">
        <table class="table table-striped table-sm">
            <thead class="bg-dark">
            <tr>
                <th>Ticket / Replies with Time</th>
                <th class="text-right" style="width: 150px;">Time Worked</th>
                <th class="text-right" style="width: 120px;">Billable (hrs)</th>
            </tr>
            </thead>

            <tbody>
            <?php
            // Helper: print ticket subtotal row
            $printTicketSubtotalRow = function($ticket_label_html, $ticket_seconds) {
                $ticket_billed = secondsToQuarterHourDecimal($ticket_seconds);
                ?>
                <tr class="font-weight-bold">
                    <td class="text-right pr-3">Ticket Total for <?php echo $ticket_label_html; ?></td>
                    <td class="text-right"><?php echo formatDuration(secondsToHmsString($ticket_seconds)); ?></td>
                    <td class="text-right"><?php echo number_format($ticket_billed, 2); ?></td>
                </tr>
                <?php
                return $ticket_billed;
            };

            $current_client_id = null;
            $current_client_name = null;

            $current_ticket_id = null;
            $current_ticket_label_html = null;

            $client_ticket_count = 0;
            $client_time_seconds = 0;
            $client_billed_hours = 0.0;

            $ticket_time_seconds = 0;

            $grand_ticket_count = 0;
            $grand_time_seconds = 0;
            $grand_billed_hours = 0.0;

            $had_rows = false;

            while ($r = mysqli_fetch_assoc($result)) {
                $had_rows = true;

                $client_id = (int)$r['client_id'];
                $client_name_html = nullable_htmlentities($r['client_name']);

                $ticket_id = (int)$r['ticket_id'];
                $ticket_prefix = nullable_htmlentities($r['ticket_prefix']);
                $ticket_number = (int)$r['ticket_number'];
                $ticket_subject_html = nullable_htmlentities($r['ticket_subject']);

                $reply_created_at = $r['ticket_reply_created_at'];
                $reply_seconds = (int)$r['reply_time_seconds'];
                $reply_hms = secondsToHmsString($reply_seconds);

                // Reply content: escape for safety, keep line breaks readable
                $reply_content_raw = $r['reply_content'] ?? '';
                // Remove all HTML tags completely
                $reply_content_clean = strip_tags($reply_content_raw);

                // Normalize line breaks (convert CRLF/CR to LF)
                $reply_content_clean = str_replace(["\r\n", "\r"], "\n", $reply_content_clean);

                // Collapse excessive blank lines (more than 2 into 2)
                $reply_content_clean = preg_replace("/\n{3,}/", "\n\n", $reply_content_clean);

                // Escape safely for output
                $reply_content_html = nl2br(nullable_htmlentities(trim($reply_content_clean)));

                // Close out previous client if client changed
                if ($current_client_id !== null && $client_id !== $current_client_id) {

                    // Close out previous ticket (if any)
                    if ($current_ticket_id !== null) {
                        $ticket_billed = $printTicketSubtotalRow($current_ticket_label_html, $ticket_time_seconds);
                        $client_billed_hours += $ticket_billed;
                        $grand_billed_hours += $ticket_billed;

                        $ticket_time_seconds = 0;
                        $current_ticket_id = null;
                        $current_ticket_label_html = null;

                        echo '<tr><td colspan="3"></td></tr>';
                    }

                    // Client subtotal
                    ?>
                    <tr class="font-weight-bold">
                        <td class="text-right">
                            Total for <?php echo $current_client_name; ?> (<?php echo $client_ticket_count; ?> tickets)
                        </td>
                        <td class="text-right"><?php echo formatDuration(secondsToHmsString($client_time_seconds)); ?></td>
                        <td class="text-right"><?php echo number_format($client_billed_hours, 2); ?></td>
                    </tr>
                    <tr><td colspan="3"></td></tr>
                    <?php

                    // Reset client totals
                    $client_ticket_count = 0;
                    $client_time_seconds = 0;
                    $client_billed_hours = 0.0;
                }

                // Client header
                if ($client_id !== $current_client_id) {
                    $current_client_id = $client_id;
                    $current_client_name = $client_name_html;
                    ?>
                    <tr class="table-active">
                        <td colspan="3" class="font-weight-bold"><?php echo $client_name_html; ?></td>
                    </tr>
                    <?php
                }

                // Ticket label
                $display_ticket = trim($ticket_prefix . $ticket_number);
                if ($display_ticket === '') $display_ticket = (string)$ticket_number;
                $ticket_label_html = nullable_htmlentities($display_ticket) . " - " . $ticket_subject_html;

                // Ticket changed: close previous ticket subtotal
                if ($current_ticket_id !== null && $ticket_id !== $current_ticket_id) {
                    $ticket_billed = $printTicketSubtotalRow($current_ticket_label_html, $ticket_time_seconds);

                    // Add billed totals once per ticket
                    $client_billed_hours += $ticket_billed;
                    $grand_billed_hours += $ticket_billed;

                    echo '<tr><td colspan="3"></td></tr>';

                    // Reset ticket accumulator
                    $ticket_time_seconds = 0;
                    $current_ticket_id = null;
                    $current_ticket_label_html = null;
                }

                // Ticket header (first row for this ticket)
                if ($ticket_id !== $current_ticket_id) {
                    $current_ticket_id = $ticket_id;
                    $current_ticket_label_html = $ticket_label_html;

                    $client_ticket_count++;
                    $grand_ticket_count++;

                    ?>
                    <tr>
                        <td class="font-weight-bold"><?php echo $ticket_label_html; ?></td>
                        <td class="text-right text-muted"></td>
                        <td class="text-right text-muted"></td>
                    </tr>
                    <?php
                }

                // Reply row (indented) - date/time + reply content + time
                ?>
                <tr>
                    <td class="pl-4 text-muted">
                        <i class="far fa-clock mr-1"></i>
                        <?php echo nullable_htmlentities(date('Y-m-d g:i A', strtotime($reply_created_at))); ?>
                        <div class="mt-1 text-body" style="white-space: normal;">
                            <?php echo $reply_content_html; ?>
                        </div>
                    </td>
                    <td class="text-right"><?php echo formatDuration($reply_hms); ?></td>
                    <td class="text-right"><?php echo number_format(secondsToQuarterHourDecimal($reply_seconds), 2); ?></td>
                </tr>
                <?php

                // Totals
                $ticket_time_seconds += $reply_seconds;

                $client_time_seconds += $reply_seconds;
                $grand_time_seconds += $reply_seconds;
            }

            if (!$had_rows) {
                ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No ticket replies with time worked found for this date range.
                    </td>
                </tr>
                <?php
            } else {
                // Close last ticket subtotal
                if ($current_ticket_id !== null) {
                    $ticket_billed = $printTicketSubtotalRow($current_ticket_label_html, $ticket_time_seconds);
                    $client_billed_hours += $ticket_billed;
                    $grand_billed_hours += $ticket_billed;

                    echo '<tr><td colspan="3"></td></tr>';
                }

                // Close last client subtotal
                ?>
                <tr class="font-weight-bold">
                    <td class="text-right">
                        Total for <?php echo $current_client_name; ?> (<?php echo $client_ticket_count; ?> tickets)
                    </td>
                    <td class="text-right"><?php echo formatDuration(secondsToHmsString($client_time_seconds)); ?></td>
                    <td class="text-right"><?php echo number_format($client_billed_hours, 2); ?></td>
                </tr>

                <tr><td colspan="3"></td></tr>

                <!-- Grand totals -->
                <tr class="font-weight-bold">
                    <td class="text-right">
                        Grand Total (<?php echo $grand_ticket_count; ?> tickets)
                    </td>
                    <td class="text-right"><?php echo formatDuration(secondsToHmsString($grand_time_seconds)); ?></td>
                    <td class="text-right"><?php echo number_format($grand_billed_hours, 2); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <small class="text-muted p-2">
            This report shows only ticket replies with time worked within the selected date range.
            Ticket “Billable (hrs)” totals are calculated by summing reply time per ticket within the range,
            then rounding that ticket total up to the nearest 15 minutes (0.25 hours).
            <br>
            Reply content is displayed under each reply timestamp.
        </small>
    </div>
</div>

<?php
require_once "../../includes/footer.php";
