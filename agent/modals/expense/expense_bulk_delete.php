<?php

require_once '../../../includes/modal_header.php';

$expense_ids = array_map('intval', $_GET['expense_ids'] ?? []);

$count = count($expense_ids);

// Generate the HTML form content using output buffering.
ob_start();

?>

<form action="post.php" method="post" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <?php foreach ($expense_ids as $expense_id) { ?> <input type="hidden" name="expense_ids[]" value="<?= $expense_id ?>"><?php } ?>
    <div class="modal-body text-center">

        <div class="mb-4" style="text-align: center;">
            <i class="far fa-10x fa-times-circle text-danger mb-3 mt-3"></i>
            <h2><?php echo __('Are you really, really, really sure?'); ?></h2>
            <h6 class="mb-4 text-secondary"><?php echo sprintf(__('This will permanently delete the selected expense%s. and ALL associated data'), ($count == 1 ? '' : 's')); ?><br><br><?php echo __('This action cannot be undone.'); ?></h6>
            <button type="button" class="btn btn-outline-secondary btn-lg px-5 mr-4" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
            <button type="submit" class="btn btn-danger btn-lg px-5"><?php echo __('Yes, Delete!'); ?></button>
        </div>



        <p class="mb-2">
            <?php echo sprintf(__('This will permanently delete the selected expense%s.'), ($count == 1 ? '' : 's')); ?>
        </p>
        <p class="text-muted small mb-0">
            <?php echo __('This action cannot be undone.'); ?>
        </p>

        <button type="submit" name="bulk_delete_expenses" class="btn btn-danger btn-lg px-5"><i class="fa fa-fw fa-trash mr-2"></i><?php echo __('Delete'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('Cancel'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
