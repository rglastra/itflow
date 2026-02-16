<?php

require_once "includes/inc_all_reports.php";

enforceUserPermission('module_financial');

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$view = isset($_GET['view']) ? $_GET['view'] : 'quarterly';

$currency_row = mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT company_currency FROM companies WHERE company_id = 1"));
$company_currency = nullable_htmlentities($currency_row['company_currency']);

// GET unique years from expenses, payments and revenues
$sql_all_years = mysqli_query($mysqli, "SELECT DISTINCT(YEAR(item_created_at)) AS all_years FROM invoice_items ORDER BY all_years DESC");

$sql_tax = mysqli_query($mysqli, "SELECT `tax_name` FROM `taxes`");

?>

    <div class="card card-dark">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-balance-scale mr-2"></i><?php echo __('collected_tax_summary'); ?></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <form class="p-3">
                <select onchange="this.form.submit()" class="form-control" name="year">
                    <?php

                    while ($row = mysqli_fetch_assoc($sql_all_years)) {
                        $all_years = intval($row['all_years']);
                        ?>
                        <option <?php if ($year == $all_years) { echo "selected"; } ?> > <?php echo $all_years; ?></option>

                        <?php
                    }
                    ?>

                </select>

                <!-- View Selection Dropdown -->
                <select onchange="this.form.submit()" class="form-control" name="view">
                    <option value="monthly" <?php if ($view == 'monthly') echo "selected"; ?>>Monthly</option>
                    <option value="quarterly" <?php if ($view == 'quarterly') echo "selected"; ?>>Quarterly</option>
                </select>
            </form>

            <div class="table-responsive-sm">
                <table class="table table-sm">
                    <thead class="text-dark">
                    <tr>
                        <th><?php echo __('tax'); ?></th>
                        <?php
                        if ($view == 'monthly') {
                            $months_abbr = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                            for ($i = 0; $i < 12; $i++) {
                                echo "<th class='text-right'>" . __($months_abbr[$i]) . "</th>";
                            }
                        } else {
                            echo "<th class='text-right'>" . __('jan_mar') . "</th>";
                            echo "<th class='text-right'>" . __('apr_jun') . "</th>";
                            echo "<th class='text-right'>" . __('jul_sep') . "</th>";
                            echo "<th class='text-right'>" . __('oct_dec') . "</th>";
                        }
                        ?>
                        <th class="text-right"><?php echo __('total'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    // Running totals
                    $monthly_totals  = array_fill(1, 12, 0.0);
                    $quarterly_totals = array_fill(1, 4, 0.0);
                    $grand_total = 0.0;

                    while ($row = mysqli_fetch_assoc($sql_tax)) {

                        $tax_name_raw = $row['tax_name'];
                        $tax_name = sanitizeInput($tax_name_raw);

                        echo "<tr>";
                        echo "<td>" . nullable_htmlentities($tax_name_raw) . "</td>";

                        if ($view == 'monthly') {

                            for ($i = 1; $i <= 12; $i++) {
                                $monthly_tax = (float) getMonthlyTax($tax_name, $i, $year, $mysqli);

                                // Accumulate totals
                                $monthly_totals[$i] += $monthly_tax;
                                $grand_total += $monthly_tax;

                                echo "<td class='text-right'>" . numfmt_format_currency($currency_format, $monthly_tax, $company_currency) . "</td>";
                            }

                            // Row total = sum of this tax’s 12 months
                            $row_total = 0.0;
                            for ($i = 1; $i <= 12; $i++) {
                                $row_total += (float) getMonthlyTax($tax_name, $i, $year, $mysqli);
                            }
                            echo "<td class='text-right text-bold'>" . numfmt_format_currency($currency_format, $row_total, $company_currency) . "</td>";

                        } else {

                            $row_total = 0.0;
                            for ($q = 1; $q <= 4; $q++) {
                                $quarterly_tax = (float) getQuarterlyTax($tax_name, $q, $year, $mysqli);

                                // Accumulate totals
                                $quarterly_totals[$q] += $quarterly_tax;
                                $grand_total += $quarterly_tax;

                                $row_total += $quarterly_tax;

                                echo "<td class='text-right'>" . numfmt_format_currency($currency_format, $quarterly_tax, $company_currency) . "</td>";
                            }

                            echo "<td class='text-right text-bold'>" . numfmt_format_currency($currency_format, $row_total, $company_currency) . "</td>";
                        }

                        echo "</tr>";
                    }

                    // Totals row
                    echo "<tr>";
                    echo "<th>Total</th>";

                    if ($view == 'monthly') {
                        for ($i = 1; $i <= 12; $i++) {
                            echo "<th class='text-right text-bold'>" . numfmt_format_currency($currency_format, $monthly_totals[$i], $company_currency) . "</th>";
                        }
                    } else {
                        for ($q = 1; $q <= 4; $q++) {
                            echo "<th class='text-right text-bold'>" . numfmt_format_currency($currency_format, $quarterly_totals[$q], $company_currency) . "</th>";
                        }
                    }

                    echo "<th class='text-right text-bold'>" . numfmt_format_currency($currency_format, $grand_total, $company_currency) . "</th>";
                    echo "</tr>";

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once "../../includes/footer.php";
