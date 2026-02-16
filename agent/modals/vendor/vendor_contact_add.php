<div class="modal" id="addVendorContactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title"><i class="fa fa-fw fa-user-plus mr-2"></i><?php echo __('New Vendor Contact'); ?></h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="post.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <label><?php echo __('Name'); ?> <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="name" placeholder="Full Name" maxlength="200" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Title'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-id-badge"></i></span>
                            </div>
                            <input type="text" class="form-control" name="title" placeholder="Title" maxlength="200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Department'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-users"></i></span>
                            </div>
                            <input type="text" class="form-control" name="department" placeholder="Department" maxlength="200">
                        </div>
                    </div>

                    <label><?php echo __('Phone'); ?></label>
                    <div class="form-row">
                        <div class="col-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="phone" placeholder="Phone Number" maxlength="200">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" name="extension" placeholder="Extension" maxlength="200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Mobile'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-mobile-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="mobile" placeholder="Mobile Phone Number" maxlength="200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Email'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" maxlength="200">
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <textarea class="form-control" rows="8" name="notes" placeholder="<?php echo __('Enter some notes'); ?>"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="add_vendor_contact" class="btn btn-primary text-bold"><i class="fas fa-check mr-2"></i><?php echo __('Create'); ?></button>
                    <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('Cancel'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>