<?php

require_once '../../../includes/modal_header.php';

$credential_id = intval($_GET['id']);

$sql = mysqli_query($mysqli, "SELECT * FROM credentials WHERE credential_id = $credential_id LIMIT 1");

$row = mysqli_fetch_assoc($sql);
$credential_name = nullable_htmlentities($row['credential_name']);
$credential_description = nullable_htmlentities($row['credential_description']);
$credential_uri = nullable_htmlentities($row['credential_uri']);
$credential_uri_2 = nullable_htmlentities($row['credential_uri_2']);
$credential_username = nullable_htmlentities(decryptLoginEntry($row['credential_username']));
$credential_password = nullable_htmlentities(decryptLoginEntry($row['credential_password']));
$credential_otp_secret = nullable_htmlentities($row['credential_otp_secret']);
$credential_id_with_secret = '"' . $row['credential_id'] . '","' . $row['credential_otp_secret'] . '"';
if (empty($credential_otp_secret)) {
    $otp_display = "-";
} else {
    $otp_display = "<span onmouseenter='showOTPViaCredentialID($credential_id)'><i class='far fa-clock'></i> <span id='otp_$credential_id'><i>Hover..</i></span></span>";
}
$credential_note = nullable_htmlentities($row['credential_note']);
$credential_created_at = nullable_htmlentities($row['credential_created_at']);

// Generate the HTML form content using output buffering.
ob_start();
?>

<div class="modal-header bg-dark text-white">
    <div class="d-flex align-items-center">
        <i class="fas fa-fw fa-key fa-2x mr-3"></i>
        <div>
            <h5 class="modal-title mb-0"><?php echo $credential_name; ?></h5>
            <div class="text-muted"><?php echo $credential_description ?: '-'; ?></div>
        </div>
    </div>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>

<div class="modal-body bg-light">

    <!-- Credential Details Card -->
    <div class="card mb-3 shadow-sm rounded">
        <div class="card-body">
            <h6 class="text-secondary"><i class="fas fa-info-circle mr-2"></i><?php echo __('Details'); ?></h6>
            <div class="row">
                <div class="col-sm-6">
                    <div><strong><?php echo __('Username / ID'); ?>:</strong> <?php echo !empty($credential_username) ? htmlspecialchars($credential_username) : '<span class="text-muted">' . __('Not Available') . '</span>'; ?></div>
                    <div><strong><?php echo __('Password / Key'); ?>:</strong> <?php echo !empty($credential_password) ? '••••••••' : '<span class="text-muted">' . __('Not Available') . '</span>'; ?></div>
                </div>
                <div class="col-sm-6">
                    <div><strong><?php echo __('OTP'); ?>:</strong> <?php echo $otp_display; ?></div>
                    <div><strong><?php echo __('Created'); ?>:</strong> <?php echo $credential_created_at ?: '<span class="text-muted">' . __('Not Available') . '</span>'; ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Links Card -->
    <div class="card mb-3 shadow-sm rounded">
        <div class="card-body">
            <h6 class="text-secondary"><i class="fas fa-link mr-2"></i><?php echo __('URIs'); ?></h6>
            <div>
                <?php if(!empty($credential_uri)) { ?>
                    <div><strong><?php echo __('URI'); ?>:</strong> <a href="<?php echo sanitize_url($credential_uri); ?>" target="_blank" class="text-primary"><?php echo htmlspecialchars($credential_uri); ?></a></div>
                <?php } ?>
                <?php if(!empty($credential_uri_2)) { ?>
                    <div><strong><?php echo __('URI 2'); ?>:</strong> <a href="<?php echo sanitize_url($credential_uri_2); ?>" target="_blank" class="text-primary"><?php echo htmlspecialchars($credential_uri_2); ?></a></div>
                <?php } ?>
                <?php if(empty($credential_uri) && empty($credential_uri_2)) { ?>
                    <span class="text-muted"><?php echo __('No URIs provided'); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Notes Card -->
    <div class="card mb-3 shadow-sm rounded">
        <div class="card-body">
            <h6 class="text-secondary"><i class="fas fa-sticky-note mr-2"></i><?php echo __('Notes'); ?></h6>
            <div>
                <?php echo !empty($credential_note) ? nl2br(htmlspecialchars($credential_note)) : '<span class="text-muted">' . __('No notes') . '</span>'; ?>
            </div>
        </div>
    </div>

</div>

<script src="js/credential_show_otp_via_id.js"></script>

<?php
require_once '../../../includes/modal_footer.php';
