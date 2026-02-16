<?php

require_once '../../../includes/modal_header.php';

$client_id = intval($_GET['client_id'] ?? 0);

if ($client_id) {
     $sql_location_select = mysqli_query($mysqli, "SELECT location_id, location_name FROM locations WHERE location_archived_at IS NULL AND location_client_id = $client_id ORDER BY location_name ASC");
} else {
    $sql_client_select = mysqli_query($mysqli, "SELECT client_id, client_name FROM clients WHERE client_archived_at IS NULL $access_permission_query ORDER BY client_name ASC");
}

$sql_tags_select = mysqli_query($mysqli, "SELECT tag_id, tag_name FROM tags WHERE tag_type = 3 ORDER BY tag_name ASC");

ob_start();

?>

<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fas fa-fw fa-user-plus mr-2"></i><?php echo __('New Contact'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <div class="modal-body">

        <ul class="nav nav-pills nav-justified mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#pills-details"><i class="fas fa-fw fa-user mr-2"></i><?php echo __('Details'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-photo"><i class="fas fa-fw fa-image mr-2"></i><?php echo __('Photo'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-access"><i class="fas fa-fw fa-lock mr-2"></i><?php echo __('Access'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-notes"><i class="fas fa-fw fa-edit mr-2"></i><?php echo __('Notes'); ?></a>
            </li>
        </ul>

        <hr>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="pills-details">

                <?php if ($client_id) { ?>
                    <input type="hidden" name="client_id" value="<?= $client_id ?>">
                <?php } else { ?>

                    <div class="form-group">
                        <label><?php echo __('Client'); ?> <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <select class="form-control select2" name="client_id" required>
                                <option value=""><?php echo __('- select client -'); ?></option>
                                <?php

                                while ($row = mysqli_fetch_assoc($sql_client_select)) {
                                    $client_id_select = intval($row['client_id']);
                                    $client_name = nullable_htmlentities($row['client_name']); ?>
                                    <option <?php if ($client_id_select == $client_id) { echo "selected"; } ?> value="<?= $client_id_select ?>"><?= $client_name ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>

                <?php } ?>

                <div class="form-group">
                    <label><?php echo __('Name'); ?> <strong class="text-danger">*</strong> / <span class="text-secondary"><?php echo __('Primary Contact'); ?></span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" placeholder="<?php echo __('Full Name'); ?>" maxlength="200" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="checkbox" name="contact_primary" value="1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Title'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-id-badge"></i></span>
                        </div>
                        <input type="text" class="form-control" name="title" placeholder="<?php echo __('Job Title'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Department / Group'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-users"></i></span>
                        </div>
                        <input type="text" class="form-control" name="department" placeholder="<?php echo __('department or group'); ?>" maxlength="200">
                    </div>
                </div>

                <label><?php echo __('Phone'); ?> / <span class="text-secondary"><?php echo __('Extension'); ?></span></label>
                <div class="form-row">
                    <div class="col-9">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
                                </div>
                                <input type="tel" class="form-control col-2" name="phone_country_code" placeholder="+" maxlength="4">
                                <input type="tel" class="form-control" name="phone" placeholder="<?php echo __('Phone Number'); ?>" maxlength="200">
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="extension" placeholder="<?php echo __('ext.'); ?>" maxlength="200">
                        </div>
                    </div>
                </div>

                <label><?php echo __('Mobile'); ?></label>
                <div class="form-row">
                    <div class="col-9">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fw fa-mobile-alt"></i></span>
                                </div>
                                <input type="tel" class="form-control col-2" name="mobile_country_code" placeholder="+" maxlength="4">
                                <input type="tel" class="form-control" name="mobile" placeholder="<?php echo __('Mobile Phone Number'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Email'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" name="email" id="contact_email" placeholder="<?php echo __('Email Address'); ?>" maxlength="200" onfocusout="contact_email_check()">
                    </div>
                    <div class="mt-2">
                        <span class="text-info" id="contact_check_info"></span>
                    </div>
                </div>

                <?php if($client_id) { ?>
                <div class="form-group">
                    <label><?php echo __('Location'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                        </div>
                        <select class="form-control select2" name="location">
                            <option value=""><?php echo __('- Select Location -'); ?></option>
                            <?php

                            while ($row = mysqli_fetch_assoc($sql_location_select)) {
                                $location_id = intval($row['location_id']);
                                $location_name = nullable_htmlentities($row['location_name']);
                            ?>
                                <option value="<?= $location_id ?>"><?= $location_name ?></option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
                <?php } ?>

            </div>

            <div class="tab-pane fade" id="pills-photo">

                <div class="form-group">
                    <label><?php echo __('Upload Photo'); ?></label>
                    <input type="file" class="form-control-file" name="file" accept="image/*">
                </div>

            </div>

            <div class="tab-pane fade" id="pills-access">

                <div class="form-group">
                    <label><?php echo __('Pin'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
                        </div>
                        <input type="text" class="form-control" name="pin" placeholder="<?php echo __('security code or pin'); ?>" maxlength="255">
                    </div>
                </div>
                <?php if ($config_client_portal_enable == 1) { ?>
                    <div class="authForm">
                        <div class="form-group">
                            <label><?php echo __('Client Portal'); ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fw fa-user-circle"></i></span>
                                </div>
                                <select class="form-control select2 authMethod" name="auth_method">
                                    <option value=""><?php echo __('- No Access -'); ?></option>
                                    <option value="local"><?php echo __('Using Set Password'); ?></option>
                                    <option value="azure"><?php echo __('Using Azure Credentials'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group passwordGroup" style="display: none;">
                            <label><?php echo __('Password'); ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" data-toggle="password" id="password" name="contact_password" placeholder="<?php echo __('password'); ?>" autocomplete="new-password">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-fw fa-eye"></i></span>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default" onclick="generatePassword()">
                                        <i class="fa fa-fw fa-question"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <label><?php echo __('Roles:'); ?></label>
                <div class="form-row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="contactImportantCheckbox" name="contact_important" value="1">
                                <label class="custom-control-label" for="contactImportantCheckbox"><?php echo __('Important'); ?></label>
                                <p class="text-secondary"><small><?php echo __('Pin Top'); ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="contactBillingCheckbox" name="contact_billing" value="1">
                                <label class="custom-control-label" for="contactBillingCheckbox"><?php echo __('Billing'); ?></label>
                                <p class="text-secondary"><small><?php echo __('Receives Invoices'); ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="contactTechnicalCheckbox" name="contact_technical" value="1">
                                <label class="custom-control-label" for="contactTechnicalCheckbox"><?php echo __('Technical'); ?></label>
                                <p class="text-secondary"><small><?php echo __('Access'); ?> </small></p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="tab-pane fade" id="pills-notes">

                <div class="form-group">
                    <textarea class="form-control" rows="8" name="notes" placeholder="<?php echo __('enter some notes'); ?>"></textarea>
                </div>

                <div class="form-group">
                    <label><?php echo __('Tags'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-tags"></i></span>
                        </div>
                        <select class="form-control select2" name="tags[]" data-placeholder="<?php echo __('add some tags'); ?>" multiple>
                            <?php

                            while ($row = mysqli_fetch_assoc($sql_tags_select)) {
                                $tag_id = intval($row['tag_id']);
                                $tag_name = nullable_htmlentities($row['tag_name']);
                                ?>
                                <option value="<?= $tag_id ?>"><?= $tag_name ?></option>
                            <?php } ?>

                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-secondary ajax-modal" type="button"
                                data-modal-url="../admin/modals/tag/tag_add.php?type=3">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" name="add_contact" class="btn btn-primary text-bold"><i class="fas fa-check mr-2"></i><?php echo __('Create'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
    </div>
</form>
<!-- JavaScript to Show/Hide Password Form Group -->
<script>

function generatePassword() {
    jQuery.get(
        "ajax.php", {
            get_readable_pass: 'true'
        },
        function(data) {
            const password = JSON.parse(data);
            document.getElementById("password").value = password;
        }
    );
}

$(document).ready(function() {
    $('.authMethod').on('change', function() {
        var $form = $(this).closest('.authForm');
        if ($(this).val() === 'local') {
            $form.find('.passwordGroup').show();
        } else {
            $form.find('.passwordGroup').hide();
        }
    });
    $('.authMethod').trigger('change');

});
</script>

<script>
    // Checks contact emails
    function contact_email_check() {
        var email = document.getElementById("contact_email").value;
        //Send a GET request to ajax.php as ajax.php?contact_email_check=true&email=email
        jQuery.get(
            "ajax.php",
            {contact_email_check: 'true', email: email},
            function(data) {
                //If we get a response from ajax.php, parse it as JSON
                const contact_check_data = JSON.parse(data);
                document.getElementById("contact_check_info").innerHTML = contact_check_data.message;
            }
        );
    }
</script>

<?php

require_once '../../../includes/modal_footer.php';
