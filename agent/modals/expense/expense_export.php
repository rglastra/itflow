<?php

require_once '../../../includes/modal_header.php';

ob_start();

?>

<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fa fa-fw fa-download mr-2"></i><?php echo __('Exporting Expenses to CSV'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" autocomplete="off">

    <div class="modal-body">

        <div class="form-group">
            <label><?php echo __('Account'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-piggy-bank"></i></span>
                </div>
                <select class="form-control select2" name="account">
                    <option value=""><?php echo __('- All Accounts -'); ?></option>

                    <?php
                    $sql_accounts_filter = mysqli_query($mysqli, "SELECT * FROM accounts WHERE account_archived_at IS NULL ORDER BY account_name ASC");
                    while ($row = mysqli_fetch_assoc($sql_accounts_filter)) {
                        $account_id = intval($row['account_id']);
                        $account_name = nullable_htmlentities($row['account_name']);
                    ?>
                        <option <?php if ($account_filter == $account_id) { echo "selected"; } ?> value="<?= $account_id ?>"><?= $account_name ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo __('Vendor'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
                </div>
                <select class="form-control select2" name="vendor">
                    <option value=""><?php echo __('- All Vendors -'); ?></option>

                    <?php
                    $sql_vendors_filter = mysqli_query($mysqli, "SELECT * FROM vendors WHERE vendor_client_id = 0 ORDER BY vendor_name ASC");
                    while ($row = mysqli_fetch_assoc($sql_vendors_filter)) {
                        $vendor_id = intval($row['vendor_id']);
                        $vendor_name = nullable_htmlentities($row['vendor_name']);
                    ?>
                        <option <?php if ($vendor_filter == $vendor_id) { echo "selected"; } ?> value="<?= $vendor_id ?>"><?= $vendor_name ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo __('Category'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-list"></i></span>
                </div>
                <select class="form-control select2" name="category">
                    <option value=""><?php echo __('- All Categories -'); ?></option>

                    <?php
                    $sql_categories_filter = mysqli_query($mysqli, "SELECT * FROM categories WHERE category_type = 'Expense' ORDER BY category_name ASC");
                    while ($row = mysqli_fetch_assoc($sql_categories_filter)) {
                        $category_id = intval($row['category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);
                    ?>
                        <option <?php if ($category_filter == $category_id) { echo "selected"; } ?> value="<?= $category_id ?>"><?= $category_name ?></option>
                    <?php
                    }
                    ?>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo __('Date From'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                </div>
                <input type="date" class="form-control" name="date_from" max="2999-12-31">
            </div>
        </div>

        <div class="form-group">
            <label><?php echo __('Date To'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                </div>
                <input type="date" class="form-control" name="date_to" max="2999-12-31">
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" name="export_expenses_csv" class="btn btn-primary text-bold"><i class="fas fa-fw fa-download mr-2"></i><?php echo __('Download CSV'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('Cancel'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
