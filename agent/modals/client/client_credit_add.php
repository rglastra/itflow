<div class="modal" id="addCreditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-dark">
            <div class="modal-header bg-dark">
                <h5 class="modal-title"><i class="fa fa-fw fa-wallet mr-2"></i><?php echo __('Adding Credit'); ?> (<strong><?php echo __('Credit Balance'); ?>:</strong> <?php echo numfmt_format_currency($currency_format, $credit_balance, $client_currency_code); ?>)</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="post.php" method="post" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <label><?php echo __('Expire'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar-day"></i></span>
                            </div>
                            <input type="date" class="form-control" name="expire" max="2999-12-31">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Type'); ?><strong class="text-danger ml-2">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-th-list"></i></span>
                            </div>
                            <select class="form-control select2" name="type" required>
                                <option value="0"><?php echo __('- select credit type -'); ?></option>
                                <option value="manual"><?php echo __('Manual'); ?></option>
                                <option value="prepaid"><?php echo __('Prepaid'); ?></option>
                                <option value="promotion"><?php echo __('Promotion'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Amount'); ?><strong class="text-danger ml-2">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php echo $currency_format->getSymbol(NumberFormatter::CURRENCY_SYMBOL); ?></span>
                            </div>
                            <input type="text" class="form-control" inputmode="decimal" pattern="[0-9]*\.?[0-9]{0,2}" name="amount" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Note'); ?><strong class="text-danger ml-2">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-file-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="note" placeholder="<?php echo __('enter a note'); ?>" maxlength="250">
                        </div>
                    </div>

                    <?php if (isset($_GET['client_id'])) { ?>
                        <input type="hidden" name="client" value="<?php echo $client_id; ?>">
                    <?php } else { ?>

                        <div class="form-group">
                            <label><?php echo __('Client'); ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                                </div>
                                <select class="form-control select2" name="client" required>
                                    <option value="0"><?php echo __('- Client'); ?> (<?php echo __('optional'); ?>)</option>
                                    <?php

                                    $sql = mysqli_query($mysqli, "SELECT client_id, client_name FROM clients ORDER BY client_name ASC");
                                    while ($row = mysqli_fetch_assoc($sql)) {
                                        $client_id = intval($row['client_id']);
                                        $client_name = nullable_htmlentities($row['client_name']);
                                        ?>
                                        <option value="<?php echo $client_id; ?>"><?php echo $client_name; ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    <?php } ?>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_credit" class="btn btn-primary text-bold"><i class="fa fa-fw fa-check mr-2"></i><?php echo __('Add'); ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
