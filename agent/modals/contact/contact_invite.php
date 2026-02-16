<div class="modal" id="contactInviteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title"><i class="fas fa-fw fa-user-plus mr-2"></i><?php echo __('Invite Contact'); ?></h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="post.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">

                <div class="modal-body">

                    <div class="form-group">
                        <label><?php echo __('Email'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="<?php echo __('Email Address'); ?>" maxlength="200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Welcome Letter'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-fw fa-envelope-open-text"></i></span>
                            </div>
                            <select class="form-control select2" name="welcome_letter">
                                <option value="1"><?php echo __('- Select One -'); ?></option>
                                <option value="2"><?php echo __('Standard'); ?></option>
                                <option value="3"><?php echo __('Big Wig'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control" rows="8" name="notes" placeholder="<?php echo __('enter some notes'); ?>"><?php echo $contact_notes; ?></textarea>
                    </div>

                    <div class="form-row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="contactInviteImportantCheckbox" name="contact_important" value="1" >
                                    <label class="custom-control-label" for="contactInviteImportantCheckbox"><?php echo __('Important'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="contactInviteBillingCheckbox" name="contact_billing" value="1" >
                                    <label class="custom-control-label" for="contactInviteBillingCheckbox"><?php echo __('Billing'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="contactInviteTechnicalCheckbox" name="contact_technical" value="1" >
                                    <label class="custom-control-label" for="contactInviteTechnicalCheckbox"><?php echo __('Technical'); ?></label>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" name="invite_contact" class="btn btn-primary text-bold"><i class="fas fa-paper-plane mr-2"></i><?php echo __('Send Invite'); ?></button>
                    <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
                </div>

            </form>

        </div>
    </div>
</div>