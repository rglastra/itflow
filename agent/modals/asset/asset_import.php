<?php

require_once '../../../includes/modal_header.php';

$client_id = intval($_GET['client_id'] ?? 0);

ob_start();

?>

<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fa fa-fw fa-desktop mr-2"></i><?php echo __('Import Assets'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="client_id" value="<?= $client_id ?>">

    <div class="modal-body">
        <p><strong><?php echo __('format csv file with headings & data:'); ?></strong><br><?php echo __('Name'); ?>, <?php echo __('description'); ?>, <?php echo __('type'); ?>, <?php echo __('make'); ?>, <?php echo __('model'); ?>, <?php echo __('serial'); ?>, <?php echo __('os'); ?>, <?php echo __('purchase date'); ?>, <?php echo __('assigned to'); ?>, <?php echo __('location'); ?>, <?php echo __('physical location'); ?>, <?php echo __('notes'); ?></p>
        <hr>
        <div class="form-group my-4">
            <input type="file" class="form-control-file" name="file" accept=".csv" required>
        </div>
        <hr>
        <div><?php echo __('download'); ?> <a href="post.php?download_assets_csv_template=<?php echo $client_id; ?>"><?php echo __('sample csv template'); ?></a></div>
        <small class="text-muted"><?php echo __('note: purchase date must be in the format yyyy-mm-dd. spreadsheet tools may automatically reformat dates.'); ?></small>
    </div>
    <div class="modal-footer">
        <button type="submit" name="import_assets_csv" class="btn btn-primary text-bold"><i class="fa fa-check mr-2"></i><?php echo __('Import'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
