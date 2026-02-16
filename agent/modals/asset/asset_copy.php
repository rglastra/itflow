<?php

require_once '../../../includes/modal_header.php';

$asset_id = intval($_GET['id']);

$sql = mysqli_query($mysqli, "SELECT * FROM assets
    LEFT JOIN asset_interfaces ON interface_asset_id = asset_id AND interface_primary = 1
    WHERE asset_id = $asset_id LIMIT 1"
);

$row = mysqli_fetch_assoc($sql);
$client_id = intval($row['asset_client_id']);
$asset_id = intval($row['asset_id']);
$asset_type = nullable_htmlentities($row['asset_type']);
$asset_name = nullable_htmlentities($row['asset_name']);
$asset_description = nullable_htmlentities($row['asset_description']);
$asset_make = nullable_htmlentities($row['asset_make']);
$asset_model = nullable_htmlentities($row['asset_model']);
$asset_serial = nullable_htmlentities($row['asset_serial']);
$asset_os = nullable_htmlentities($row['asset_os']);
$asset_ip = nullable_htmlentities($row['interface_ip']);
$asset_ipv6 = nullable_htmlentities($row['interface_ipv6']);
$asset_nat_ip = nullable_htmlentities($row['interface_nat_ip']);
$asset_mac = nullable_htmlentities($row['interface_mac']);
$asset_uri = nullable_htmlentities($row['asset_uri']);
$asset_uri_2 = nullable_htmlentities($row['asset_uri_2']);
$asset_status = nullable_htmlentities($row['asset_status']);
$asset_purchase_reference = nullable_htmlentities($row['asset_purchase_reference']);
$asset_purchase_date = nullable_htmlentities($row['asset_purchase_date']);
$asset_warranty_expire = nullable_htmlentities($row['asset_warranty_expire']);
$asset_install_date = nullable_htmlentities($row['asset_install_date']);
$asset_photo = nullable_htmlentities($row['asset_photo']);
$asset_physical_location = nullable_htmlentities($row['asset_physical_location']);
$asset_notes = nullable_htmlentities($row['asset_notes']);
$asset_created_at = nullable_htmlentities($row['asset_created_at']);
$asset_archived_at = nullable_htmlentities($row['asset_archived_at']);
$asset_vendor_id = intval($row['asset_vendor_id']);
$asset_location_id = intval($row['asset_location_id']);
$asset_contact_id = intval($row['asset_contact_id']);
$asset_network_id = intval($row['interface_network_id']);
$device_icon = getAssetIcon($asset_type);

// Generate the HTML form content using output buffering.
ob_start();
?>

<div class="modal-header bg-dark">
    <h5 class="modal-title"><i class='fa fa-fw fa-<?php echo $device_icon; ?> mr-2'></i><?php echo __('Copying asset:'); ?> <strong><?php echo $asset_name; ?></strong></h5>
    <button type="button" class="close text-light" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<form action="post.php" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">

    <div class="modal-body">

        <ul class="nav nav-pills nav-justified mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#pillsDetailsCopy<?php echo $asset_id; ?>"><?php echo __('Details'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pillsNetworkCopy<?php echo $asset_id; ?>"><?php echo __('Network'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pillsAssignmentCopy<?php echo $asset_id; ?>"><?php echo __('Assignment'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pillsPurchaseCopy<?php echo $asset_id; ?>"><?php echo __('Purchase'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pillsLoginCopy<?php echo $asset_id; ?>"><?php echo __('Login'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pillsNotesCopy<?php echo $asset_id; ?>"><?php echo __('Notes'); ?></a>
            </li>
        </ul>

        <hr>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="pillsDetailsCopy<?php echo $asset_id; ?>">

                <div class="form-group">
                    <label><?php echo __('Name'); ?> <strong class="text-danger">*</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" placeholder="<?php echo __('name the asset'); ?>" value="<?php echo $asset_name; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Description'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-angle-right"></i></span>
                        </div>
                        <input type="text" class="form-control" name="description" placeholder="<?php echo __('description of the asset'); ?>" value="<?php echo $asset_description; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Type'); ?> <strong class="text-danger">*</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-tags"></i></span>
                        </div>
                        <select class="form-control select2" name="type" required>
                            <?php foreach($asset_types_array as $asset_type_select => $asset_icon_select) { ?>
                                <option <?php if ($asset_type_select == $asset_type) { echo "selected"; } ?>><?php echo $asset_type_select; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <?php //Do not display Make Model or Serial if Virtual is selected
                if ($asset_type !== 'virtual') { ?>
                    <div class="form-group">
                        <label><?php echo __('Make'); ?> </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                            </div>
                            <input type="text" class="form-control" name="make" placeholder="<?php echo __('Manufacturer'); ?>" value="<?php echo $asset_make; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Model'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                            </div>
                            <input type="text" class="form-control" name="model" placeholder="<?php echo __('Model Number'); ?>" value="<?php echo $asset_model; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Serial Number'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-barcode"></i></span>
                            </div>
                            <input type="text" class="form-control" name="serial" placeholder="<?php echo __('serial number'); ?>">
                        </div>
                    </div>
                <?php } ?>

                <?php if ($asset_type !== 'Phone' && $asset_type !== 'Mobile Phone' && $asset_type !== 'Tablet' && $asset_type !== 'Access Point' && $asset_type !== 'Printer' && $asset_type !== 'Camera' && $asset_type !== 'TV' && $asset_type !== 'Other') { ?>
                    <div class="form-group">
                        <label><?php echo __('Operating System'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-fw fa-windows"></i></span>
                            </div>
                            <input type="text" class="form-control" name="os" placeholder="<?php echo __('ex Windows 10 Pro'); ?>" value="<?php echo $asset_os; ?>">
                        </div>
                    </div>
                <?php } ?>

            </div>

            <div class="tab-pane fade" id="pillsNetworkCopy<?php echo $asset_id; ?>">

                <div class="form-group">
                    <label><?php echo __('Network'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-network-wired"></i></span>
                        </div>
                        <select class="form-control select2" name="network">
                            <option value=""><?php echo __('- Select Network -'); ?></option>
                            <?php

                            $sql_networks = mysqli_query($mysqli, "SELECT * FROM networks WHERE network_archived_at IS NULL AND network_client_id = $client_id ORDER BY network_name ASC");
                            while ($row = mysqli_fetch_assoc($sql_networks)) {
                                $network_id_select = intval($row['network_id']);
                                $network_name_select = nullable_htmlentities($row['network_name']);
                                $network_select = nullable_htmlentities($row['network']);

                                ?>
                                <option <?php if ($asset_network_id == $network_id_select) { echo "selected"; } ?> value="<?php echo $network_id_select; ?>"><?php echo $network_name_select; ?> - <?php echo $network_select; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('IP Address or DHCP'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-ethernet"></i></span>
                        </div>
                        <input type="text" class="form-control" name="ip" placeholder="192.168.10.250" data-inputmask="'alias': 'ip'" data-mask>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="checkbox" name="dhcp" value="1" <?php if($asset_ip == 'DHCP'){ echo "checked"; } ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('NAT IP'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-random"></i></span>
                        </div>
                        <input type="text" class="form-control" name="nat_ip" placeholder="10.52.4.55" data-inputmask="'alias': 'ip'" data-mask>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('IPv6 Address'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-ethernet"></i></span>
                        </div>
                        <input type="text" class="form-control" name="ipv6" value="<?php echo $asset_ipv6; ?>" placeholder="<?php echo __('ex. 2001:0db8:0000:0000:0000:ff00:0042:8329'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('MAC Address'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-ethernet"></i></span>
                        </div>
                        <input type="text" class="form-control" name="mac" placeholder="<?php echo __('MAC Address'); ?>" data-inputmask="'alias': 'mac'" data-mask>
                    </div>
                </div>

                <div class="form-group">
                    <label>URI</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-globe"></i></span>
                        </div>
                        <input type="text" class="form-control" name="uri" placeholder="<?php echo __('URI http:// ftp:// ssh: etc'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>URI 2</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-globe"></i></span>
                        </div>
                        <input type="text" class="form-control" name="uri_2" placeholder="<?php echo __('URI http:// ftp:// ssh: etc'); ?>">
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="pillsAssignmentCopy<?php echo $asset_id; ?>">

                <div class="form-group">
                    <label><?php echo __('Physical Location'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="physical_location" placeholder="<?php echo __('Physical location eg. Floor 2, Closet B'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Location'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                        </div>
                        <select class="form-control select2" name="location">
                            <option value=""><?php echo __('- Select Location -'); ?></option>
                            <?php

                            $sql_locations = mysqli_query($mysqli, "SELECT * FROM locations WHERE location_archived_at IS NULL AND location_client_id = $client_id ORDER BY location_name ASC");
                            while ($row = mysqli_fetch_assoc($sql_locations)) {
                                $location_id_select = intval($row['location_id']);
                                $location_name_select = nullable_htmlentities($row['location_name']);
                                ?>
                                <option <?php if ($asset_location_id == $location_id_select) { echo "selected"; } ?> value="<?php echo $location_id_select; ?>"><?php echo $location_name_select; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Assign To'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                        </div>
                        <select class="form-control select2" name="contact">
                            <option value=""><?php echo __('- Select Contact -'); ?></option>
                            <?php

                            $sql_contacts = mysqli_query($mysqli, "SELECT * FROM contacts WHERE contact_archived_at IS NULL AND contact_client_id = $client_id ORDER BY contact_name ASC");
                            while ($row = mysqli_fetch_assoc($sql_contacts)) {
                                $contact_id_select = intval($row['contact_id']);
                                $contact_name_select = nullable_htmlentities($row['contact_name']);
                                ?>
                                <option value="<?php echo $contact_id_select; ?>"><?php echo $contact_name_select; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Status'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-info"></i></span>
                        </div>
                        <select class="form-control select2" name="status">
                            <?php foreach($asset_status_array as $asset_status_select) { ?>
                                <option <?php if ($asset_status_select == $asset_status) { echo "selected"; } ?>><?php echo $asset_status_select; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="pillsPurchaseCopy<?php echo $asset_id; ?>">

                <div class="form-group">
                    <label><?php echo __('Vendor'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
                        </div>
                        <select class="form-control select2" name="vendor">
                            <option value=""><?php echo __('- Select Vendor -'); ?></option>
                            <?php

                            $sql_vendors = mysqli_query($mysqli, "SELECT * FROM vendors WHERE vendor_archived_at IS NULL AND vendor_client_id = $client_id ORDER BY vendor_name ASC");
                            while ($row = mysqli_fetch_assoc($sql_vendors)) {
                                $vendor_id_select = intval($row['vendor_id']);
                                $vendor_name_select = nullable_htmlentities($row['vendor_name']);
                                ?>
                                <option <?php if ($asset_vendor_id == $vendor_id_select) { echo "selected"; } ?> value="<?php echo $vendor_id_select; ?>"><?php echo $vendor_name_select; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Install Date'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-calendar-check"></i></span>
                        </div>
                        <input type="date" class="form-control" name="install_date" max="2999-12-31" value="<?php echo $asset_install_date; ?>">
                    </div>
                </div>

                <?php if ($asset_type !== 'Virtual Machine') { ?>
                    <div class="form-group">
                        <label><?php echo __('Purchase Reference'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-shopping-cart"></i></span>
                            </div>
                            <input type="text" class="form-control" name="purchase_reference" placeholder="<?php echo __('eg. Invoice, PO Number'); ?>" value="<?php echo $asset_purchase_reference; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Purchase Date'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-shopping-cart"></i></span>
                            </div>
                            <input type="date" class="form-control" name="purchase_date" max="2999-12-31" value="<?php echo $asset_purchase_date; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php echo __('Warranty Expire'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar-times"></i></span>
                            </div>
                            <input type="date" class="form-control" name="warranty_expire" max="2999-12-31" value="<?php echo $asset_warranty_expire; ?>">
                        </div>
                    </div>
                <?php } ?>

            </div>

            <div class="tab-pane fade" id="pillsLoginCopy<?php echo $asset_id; ?>">

                <div class="form-group">
                    <label><?php echo __('Username'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa  fa-fw fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="username" placeholder="<?php echo __('username'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo __('Password'); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                        </div>
                        <input type="text" class="form-control" name="password" placeholder="<?php echo __('password'); ?>" autocomplete="off">
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="pillsNotesCopy<?php echo $asset_id; ?>">

                <div class="form-group">
                    <label><?php echo __('Upload Photo'); ?></label>
                    <input type="file" class="form-control-file" name="file">
                </div>

                <div class="form-group">
                    <textarea class="form-control" rows="8" placeholder="<?php echo __('Enter some notes'); ?>" name="notes"><?php echo $asset_notes; ?></textarea>
                </div>

            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" name="add_asset" class="btn btn-primary text-bold"><i class="fa fa-check mr-2"></i><?php echo __('Copy'); ?></button>
        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times mr-2"></i><?php echo __('cancel'); ?></button>
    </div>
</form>

<?php
require_once '../../../includes/modal_footer.php';
