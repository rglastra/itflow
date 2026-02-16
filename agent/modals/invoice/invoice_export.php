<?php

require_once '../../../includes/modal_header.php';

$client_id = intval($_GET['client_id'] ?? 0);

ob_start();

?>

<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fa fa-fw fa-download mr-2"></i><?php echo __('Export Invoices to CSV'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" autocomplete="off">
    <input type="hidden" name="client_id" value="<?= $client_id ?>">
    <div class="modal-body">

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
        <button type="submit" name="export_invoices_csv" class="btn btn-primary text-bold"><i class="fas fa-fw fa-download mr-2"></i><?php echo __('Download CSV'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
