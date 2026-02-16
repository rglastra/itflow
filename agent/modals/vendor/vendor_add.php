<?php

require_once '../../../includes/modal_header.php';

$client_id = intval($_GET['client_id'] ?? 0);

ob_start();

?>
<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class="fas fa-fw fa-building mr-2"></i><?php echo __('New Vendor'); ?></h5>
    <button type="button" class="close text-white" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" autocomplete="off">
    <input type="hidden" name="client_id" value="<?= $client_id ?>">

    <div class="modal-body">
        <ul class="nav nav-pills nav-justified mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#pills-details"><?php echo __('Details'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-support"><?php echo __('Support'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pills-notes"><?php echo __('Notes'); ?></a>
            </li>
        </ul>

        <hr>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-details">
                <div class="form-group">
                    <label><?php echo __('Vendor Name'); ?> <strong class="text-danger">*</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" placeholder="<?php echo __('vendor name'); ?>" maxlength="200" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('description'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-angle-right"></i></span>
                        </div>
                        <input type="text" class="form-control" name="description" placeholder="<?php echo __('description'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Account Number'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-fingerprint"></i></span>
                        </div>
                        <input type="text" class="form-control" name="account_number" placeholder="<?php echo __('account number'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Account Manager'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="contact_name" placeholder="<?php echo __('account manager\'s name'); ?>" maxlength="200">
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-support">
                <label><?php echo __('Support Phone'); ?> / <span class="text-secondary"><?php echo __('Extension'); ?></span></label>
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

                <div class="form-group">
                    <label><?php echo __('Support Hours'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                        </div>
                        <input type="text" class="form-control" name="hours" placeholder="<?php echo __('support hours'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Support Email'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" name="email" placeholder="<?php echo __('support email'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Support Website URL'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-globe"></i></span>
                        </div>
                        <input type="text" class="form-control" name="website" placeholder="<?php echo __('do not include http(s)://'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Pin/Code'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
                        </div>
                        <input type="text" class="form-control" name="code" placeholder="<?php echo __('access code or pin'); ?>" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('SLA'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-handshake"></i></span>
                        </div>
                        <input type="text" class="form-control" name="sla" placeholder="<?php echo __('sla response time'); ?>" maxlength="200">
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-notes">
                <div class="form-group">
                    <textarea class="form-control" rows="12" placeholder="<?php echo __('enter some notes'); ?>" name="notes"></textarea>
                </div>
            </div>

        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" name="add_vendor" class="btn btn-primary text-bold"><i class="fas fa-check mr-2"></i><?php echo __('create'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
    </div>
</form>

<?php

require_once '../../../includes/modal_footer.php';
