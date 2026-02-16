<?php

require_once '../../../includes/modal_header.php';

$client_id = intval($_GET['client_id'] ?? 0);

ob_start();

?>
<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fa fa-fw fa-clock mr-2"></i><?php echo __('create_recurring_expense'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" autocomplete="off">
    <div class="modal-body">

        <div class="form-row">

            <div class="form-group col-md">
                <label><?php echo __('frequency'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-sync-alt"></i></span>
                    </div>
                    <select class="form-control select2" name="frequency" required>
                        <option value="1"><?php echo __('Monthly'); ?></option>
                        <option value="2"><?php echo __('Annually'); ?></option>
                    </select>
                </div>
            </div>

            <div class="form-group col-md">
                <label><?php echo __('Month'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                    </div>
                    <select class="form-control select2" name="month" required>
                        <option value=""><?php echo __('- select a month -'); ?></option>
                        <option value="1">01 - <?php echo __(strtolower(__('january'))); ?></option>
                        <option value="2">02 - <?php echo __(strtolower(__('february'))); ?></option>
                        <option value="3">03 - <?php echo __(strtolower(__('march'))); ?></option>
                        <option value="4">04 - <?php echo __(strtolower(__('april'))); ?></option>
                        <option value="5">05 - <?php echo __(strtolower(__('may'))); ?></option>
                        <option value="6">06 - <?php echo __(strtolower(__('june'))); ?></option>
                        <option value="7">07 - <?php echo __(strtolower(__('july'))); ?></option>
                        <option value="8">08 - <?php echo __(strtolower(__('august'))); ?></option>
                        <option value="9">09 - <?php echo __(strtolower(__('september'))); ?></option>
                        <option value="10">10 - <?php echo __(strtolower(__('october'))); ?></option>
                        <option value="11">11 - <?php echo __(strtolower(__('november'))); ?></option>
                        <option value="12">12 - <?php echo __(strtolower(__('december'))); ?></option>
                    </select>
                </div>
            </div>

            <div class="form-group col-md">
                <label><?php echo __('Day'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control" inputmode="numeric" pattern="(1[0-9]|2[0-8]|[1-9])" name="day" placeholder="<?php echo __('enter a day (1-28)'); ?>" required>
                </div>
            </div>

        </div>

        <div class="form-row">
            <div class="form-group col-md">
                <label><?php echo __('Amount'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php echo $currency_format->getSymbol(NumberFormatter::CURRENCY_SYMBOL); ?></span>
                    </div>
                    <input type="text" class="form-control" inputmode="decimal" pattern="-?[0-9]*\.?[0-9]{0,2}" name="amount" placeholder="0.00" required>
                </div>
            </div>
        </div>

        <div class="form-row">

            <div class="form-group col-md">
                <label><?php echo __('Account'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-piggy-bank"></i></span>
                    </div>
                    <select class="form-control select2" name="account" required>
                        <option value=""><?php echo __('- account -'); ?></option>
                        <?php

                        $sql = mysqli_query($mysqli, "SELECT account_id, account_name, opening_balance FROM accounts WHERE account_archived_at IS NULL ORDER BY account_name ASC");
                        while ($row = mysqli_fetch_assoc($sql)) {
                            $account_id = intval($row['account_id']);
                            $account_name = nullable_htmlentities($row['account_name']);
                            $opening_balance = floatval($row['opening_balance']);

                            $sql_payments = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS total_payments FROM payments WHERE payment_account_id = $account_id");
                            $row = mysqli_fetch_assoc($sql_payments);
                            $total_payments = floatval($row['total_payments']);

                            $sql_revenues = mysqli_query($mysqli, "SELECT SUM(revenue_amount) AS total_revenues FROM revenues WHERE revenue_account_id = $account_id");
                            $row = mysqli_fetch_assoc($sql_revenues);
                            $total_revenues = floatval($row['total_revenues']);

                            $sql_expenses = mysqli_query($mysqli, "SELECT SUM(expense_amount) AS total_expenses FROM expenses WHERE expense_account_id = $account_id");
                            $row = mysqli_fetch_assoc($sql_expenses);
                            $total_expenses = floatval($row['total_expenses']);

                            $balance = $opening_balance + $total_payments + $total_revenues - $total_expenses;

                            ?>
                            <option <?php if ($config_default_expense_account == $account_id) { echo "selected"; } ?> value="<?php echo $account_id; ?>"><div class="float-left"><?php echo $account_name; ?></div><div class="float-right"> [$<?php echo number_format($balance, 2); ?>]</div></option>

                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-md">
                <label><?php echo __('Vendor'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
                    </div>
                    <select class="form-control select2" name="vendor" required>
                        <option value=""><?php echo __('- vendor -'); ?></option>
                        <?php

                        $sql = mysqli_query($mysqli, "SELECT vendor_id, vendor_name FROM vendors WHERE vendor_client_id = 0 AND vendor_archived_at IS NULL ORDER BY vendor_name ASC");
                        while ($row = mysqli_fetch_assoc($sql)) {
                            $vendor_id = intval($row['vendor_id']);
                            $vendor_name = nullable_htmlentities($row['vendor_name']);
                            ?>
                            <option value="<?php echo $vendor_id; ?>"><?php echo $vendor_name; ?></option>

                            <?php
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <a class="btn btn-secondary" href="vendors.php" target="_blank"><i class="fas fa-fw fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo __('description'); ?> <strong class="text-danger">*</strong></label>
            <textarea class="form-control" rows="6" name="description" placeholder="<?php echo __('enter a description'); ?>" required></textarea>
        </div>

        <div class="form-group">
            <label><?php echo __('reference'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-file-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="reference" placeholder="<?php echo __('enter a reference'); ?>" maxlength="200">
            </div>
        </div>

        <div class="form-row">

            <div class="form-group col-md">
                <label><?php echo __('Category'); ?> <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-list"></i></span>
                    </div>
                    <select class="form-control select2" name="category" required>
                        <option value=""><?php echo __('- category -'); ?></option>
                        <?php

                        $sql = mysqli_query($mysqli, "SELECT category_id, category_name FROM categories WHERE category_type = 'Expense' AND category_archived_at IS NULL ORDER BY category_name ASC");
                        while ($row = mysqli_fetch_assoc($sql)) {
                            $category_id = intval($row['category_id']);
                            $category_name = nullable_htmlentities($row['category_name']);
                            ?>
                            <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>

                            <?php
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-secondary ajax-modal" type="button"
                            data-modal-url="../admin/modals/category/category_add.php?category=Expense">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>


            </div>

            <?php if ($client_id) { ?>
                <input type="hidden" name="client" value="<?php echo $client_id; ?>">
            <?php } else { ?>

                <div class="form-group col-md">
                    <label><?php echo __('Client'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                        </div>
                        <select class="form-control select2" name="client" required>
                            <option value="0"><?php echo __('- client -'); ?> (<?php echo __('optional'); ?>)</option>
                            <?php

                            $sql = mysqli_query($mysqli, "SELECT client_id, client_name FROM clients ORDER BY client_name ASC");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $client_id_select = intval($row['client_id']);
                                $client_name = nullable_htmlentities($row['client_name']);
                                ?>
                                <option value="<?php echo $client_id_select; ?>"><?php echo $client_name; ?></option>

                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

            <?php } ?>

        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" name="create_recurring_expense" class="btn btn-primary text-bold"><i class="fa fa-fw fa-check mr-2"></i><?php echo __('create'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
