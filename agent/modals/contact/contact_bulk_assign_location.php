<?php

require_once '../../../includes/modal_header.php';

$client_id = intval($_GET['client_id']);
$contact_ids = array_map('intval', $_GET['contact_ids'] ?? []);

$count = count($contact_ids);

ob_start();

?>

<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fa fa-fw fa-map-marker-alt mr-2"></i><?php echo __('Assign Location to'); ?> <strong><?= $count ?></strong> <?php echo __('Contacts'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <?php foreach ($contact_ids as $contact_id) { ?><input type="hidden" name="contact_ids[]" value="<?= $contact_id ?>"><?php } ?>
    <div class="modal-body">

        <div class="form-group">
            <label><?php echo __('Location'); ?></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                </div>
                <select class="form-control select2" name="bulk_location_id">
                    <option value=""><?php echo __('- Select Location -'); ?></option>
                    <?php

                    $sql = mysqli_query($mysqli, "SELECT location_id, location_name FROM locations WHERE location_archived_at IS NULL AND location_client_id = $client_id ORDER BY location_name ASC");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $location_id = intval($row['location_id']);
                        $location_name = nullable_htmlentities($row['location_name']);
                    ?>
                        <option value="<?php echo $location_id; ?>"><?php echo $location_name; ?></option>
                    <?php } ?>

                </select>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" name="bulk_assign_contact_location" class="btn btn-primary text-bold"><i class="fas fa-check mr-2"></i><?php echo __('Assign Location'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
