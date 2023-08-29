<?php

// include gallery img function
include SBWCIT_PATH . 'functions/cpt-functions/gallery.php';

// order fetch function
include SBWCIT_PATH . 'functions/cpt-functions/fetch_order_date_ajax.php';

// register pll strings
if (function_exists('pll_register_string')) :
    pll_register_string('sbwcit_1', 'Issue date');
    pll_register_string('sbwcit_2', 'Ticket reference');
    pll_register_string('sbwcit_3', 'Original order number');
    pll_register_string('sbwcit_4', 'Replacement order number');
    pll_register_string('sbwcit_5', 'Replacement reason');
    pll_register_string('sbwcit_6', 'Refunded amount');
    pll_register_string('sbwcit_7', 'Issue status');
    pll_register_string('sbwcit_8', 'Pending');
    pll_register_string('sbwcit_9', 'Resolved');
    pll_register_string('sbwcit_10', 'Issue images:');
    pll_register_string('sbwcit_11', 'Add');
    pll_register_string('sbwcit_12', 'Remove');
    pll_register_string('sbwcit_13', 'Select gallery image:');
endif;

/**
 * Register custom meta fields for issue tracker
 */
function sbwcit_add_custom_box()
{
    add_meta_box(
        'sbwcit_meta_box',
        'Issue Tracker Metadata',
        'sbwcit_meta_box_callback',
        ['product_issue', 'shipping_issue']
    );
}
add_action('add_meta_boxes', 'sbwcit_add_custom_box');

/**
 * Render custom meta box html
 */
function sbwcit_meta_box_callback($post)
{
    // get post meta
    $post_id          = $post->ID;
    $date             = get_post_meta($post_id, 'issue_date', true);
    $ticket           = get_post_meta($post_id, 'ticket', true);
    $order_no         = get_post_meta($post_id, 'order_no', true);
    $rep_order_no     = get_post_meta($post_id, 'rep_order_no', true);
    $reason           = get_post_meta($post_id, 'reason', true);
    $ref_amount       = get_post_meta($post_id, 'ref_amt', true);
    $status           = get_post_meta($post_id, 'status', true);
    $product          = get_post_meta($post_id, 'product', true);
    $sku              = get_post_meta($post_id, 'sku', true);
    $comments         = get_post_meta($post_id, 'comments', true);
    $issue_type       = get_post_meta($post_id, 'issue_type', true);
    $issue_type_other = get_post_meta($post_id, 'issue_type_other', true);
    $severity         = get_post_meta($post_id, 'severity', true);
    $manufacturer     = get_post_meta($post_id, 'manufacturer', true);
    $manufacture_date = get_post_meta($post_id, 'manufacture_date', true);
    $order_date       = get_post_meta($post_id, 'order_date', true);
    $order_data       = get_post_meta($post_id, 'order_data', true);


?>

    <!-- date -->
    <p class="sbwcit_post_meta_cont">
        <label for="issue_date"><?php function_exists('pll_e') ? pll_e('Issue date:') : _e('Issue date:') ?></label>
        <input type="date" name="issue_date" id="issue_date" value="<?php echo $date ? $date : date('Y-m-d'); ?>">
    </p>

    <!-- ticket -->
    <p class="sbwcit_post_meta_cont">
        <label for="ticket"><?php function_exists('pll_e') ? pll_e('Ticket reference:*') : _e('Ticket reference:*') ?></label>
        <input type="url" name="ticket" id="ticket" value="<?php echo $ticket ?>" placeholder="<?php function_exists('pll_e') ? pll_e('ticket reference URL') : _e('ticket reference URL') ?>" required>
    </p>

    <!-- ticket severity (low, medium or high) -->
    <p class="sbwcit_post_meta_cont">
        <label for="severity"><?php function_exists('pll_e') ? pll_e('Ticket severity:*') : _e('Ticket severity:*') ?></label>
        <select name="severity" id="severity" required>
            <option value=""><?php function_exists('pll_e') ? pll_e('Please Select...') : _e('Please Select...') ?></option>
            <option <?php echo $severity == 'low' ? 'selected' : ''; ?> value="low"><?php function_exists('pll_e') ? pll_e('Low') : _e('Low') ?></option>
            <option <?php echo $severity == 'medium' ? 'selected' : ''; ?> value="medium"><?php function_exists('pll_e') ? pll_e('Medium') : _e('Medium') ?></option>
            <option <?php echo $severity == 'high' ? 'selected' : ''; ?> value="high"><?php function_exists('pll_e') ? pll_e('High') : _e('High') ?></option>
        </select>
    </p>

    <!-- original order number -->
    <p class="sbwcit_post_meta_cont">
        <label for="order_no"><?php function_exists('pll_e') ? pll_e('Original order number:*') : _e('Original order number:*') ?></label>
        <input type="text" name="order_no" id="order_no" value="<?php echo $order_no ?>" required onblur="fetch_order_date()" data-key="ck_6c5cd21e42f272ab827c33e9252b9af3a1ed7a5b" data-secret="cs_9ed0400dc2b161a3f7076ed2c1d2454eac0c13cb">
    </p>

    <!-- function fetch_order_date -->
    <script>
        function fetch_order_date() {

            $ = jQuery;

            // get order id
            var order_no = $('#order_no').val();

            // get api key
            var api_key = $('#order_no').data('key');

            // get api secret
            var api_secret = $('#order_no').data('secret');

            // if order id empty, return
            if (!order_no) return;

            // if api key empty, return
            if (!api_key) return;

            // if api secret empty, return
            if (!api_secret) return;

            // ajax request
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'sbwcit_fetch_order_date',
                    order_no: order_no,
                    api_key: api_key,
                    api_secret: api_secret,
                    issue_id: <?php echo $post_id; ?>,
                },
                success: function(response) {

                    // if success
                    if (response.success) {

                        // // log
                        // console.log(response);

                        // order data
                        let order_data = response.data;

                        // extract order date
                        let order_date = order_data.date_created;

                        // convert to compatible date format for order date input and set value
                        $('#order_date').val(order_date.substring(0, 10));

                        // base64 encode order data and add to hidden input
                        $('#order_data').val(JSON.stringify(order_data));

                        // if error
                    } else {
                        // log
                        console.log(response);
                    }


                }
            });
        }
    </script>

    <!-- order date -->
    <p class="sbwcit_post_meta_cont">
        <label for="order_date"><?php function_exists('pll_e') ? pll_e('Order date:') : _e('Order date:') ?></label>
        <input type="date" name="order_date" id="order_date" value="<?php echo $order_date ?>" readonly placeholder="<?php function_exists('pll_e') ? pll_e('enter original order number') : _e('enter original order number') ?>">
    </p>

    <!-- replacement order number -->
    <p class="sbwcit_post_meta_cont">
        <label for="rep_order_no"><?php function_exists('pll_e') ? pll_e('Replacement order number:') : _e('Replacement order number:') ?></label>
        <input type="text" name="rep_order_no" id="rep_order_no" value="<?php echo $rep_order_no ?>">
    </p>

    <!-- product -->
    <p class="sbwcit_post_meta_cont">
        <label for="product"><?php function_exists('pll_e') ? pll_e('Product:*') : _e('Product:*') ?></label>

        <?php
        // if products post type exists, build select2 dropdown, else display text input for product title
        if (post_type_exists('product')) :

            // get products
            $products = get_posts([
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_status'    => 'any'
            ]);

            // init products array
            $products_arr = [];

            // loop through products
            foreach ($products as $product) :

                // get product title
                $title = get_the_title($product);

                // get product sku
                $psku = get_post_meta($product, 'sku', true);

                // add to array
                $products_arr[$psku] = $title;

            endforeach;

            // sort array
            asort($products_arr); ?>

            <select name="product" id="product" required>

                <option value=""><?php echo (function_exists('pll_e') ? pll_e('Please Select...') : _e('Please Select...')); ?></option>

                <?php foreach ($products_arr as $psku => $title) : ?>
                    <option <?php echo ($product == $title ? 'selected' : '') ?> value="<?php echo $title ?>" data-sku="<?php echo $psku; ?>"><?php echo $title ?></option>
                <?php endforeach; ?>

            </select>

            <!-- select2 -->
            <script>
                $ = jQuery;

                $(document).ready(function() {

                    // init select2
                    $('#product').select2({
                        placeholder: "<?php echo (function_exists('pll_e') ? pll_e('Please Select...') : _e('Please Select...')); ?>",
                        allowClear: true,
                    });

                    // loop through product select options
                    $('#product option').each(function() {

                        // get sku
                        var sku = $(this).data('sku');

                        // if sku matches
                        if (sku == '<?php echo $sku; ?>') {

                            // select option
                            $(this).attr('selected', true);

                            // trigger change
                            $('#product').trigger('change');
                        }
                    });

                    // set #sku value on #product change
                    $('#product').on('change', function() {

                        // get sku
                        var sku = $(this).find(':selected').data('sku');

                        // set sku
                        $('#sku').val(sku);
                    });

                });
            </script>
        <?php else : // default 
        ?>

            <input type="text" name="product" id="product" value="<?php echo $product ?>" required>

        <?php endif; ?>
    </p>

    <!-- SKU -->
    <p class="sbwcit_post_meta_cont">
        <label for="sku"><?php function_exists('pll_e') ? pll_e('SKU:*') : _e('SKU:*') ?></label>

        <?php if (post_type_exists('product')) : ?>

            <input type="text" name="sku" id="sku" value="<?php echo $sku; ?>" required readonly placeholder="<?php function_exists('pll_e') ? pll_e('Select product...') : _e('Select product...') ?>">

        <?php else : ?>

            <input type="text" name="sku" id="sku" value="<?php echo $sku; ?>" required>

        <?php endif; ?>
    </p>

    <!-- comments -->
    <p class="sbwcit_post_meta_cont">
        <label for="comments"><?php function_exists('pll_e') ? pll_e('Additional Comments:') : _e('Additional Comments:') ?></label>
        <textarea name="comments" id="comments" cols="66" rows="10"><?php echo $comments ?></textarea>
    </p>

    <!-- issue type -->
    <p class="sbwcit_post_meta_cont">
        <label for="issue_type"><?php function_exists('pll_e') ? pll_e('Issue Type:*') : _e('Issue Type:*') ?></label>
        <select name="issue_type" id="issue_type" onchange="checkIssueVal()" required>
            <option value=""><?php function_exists('pll_e') ? pll_e('Please Select...') : _e('Please Select...') ?></option>
            <option <?php echo $issue_type == 'damaged_receipt' ? 'selected' : ''; ?> value="damaged_receipt"><?php function_exists('pll_e') ? pll_e('Damaged Upon Receipt') : _e('Damaged Upon Receipt') ?></option>
            <option <?php echo $issue_type == 'defect_exterior' ? 'selected' : ''; ?> value="defect_exterior"><?php function_exists('pll_e') ? pll_e('Material Defect (Exterior)') : _e('Material Defect (Exterior)') ?></option>
            <option <?php echo $issue_type == 'defect_interior' ? 'selected' : ''; ?> value="defect_interior"><?php function_exists('pll_e') ? pll_e('Material Defect (Interior)') : _e('Material Defect (Interior)') ?></option>
            <option <?php echo $issue_type == 'zipper_defect' ? 'selected' : ''; ?> value="zipper_defect"><?php function_exists('pll_e') ? pll_e('Zipper Defect') : _e('Zipper Defect') ?></option>
            <option <?php echo $issue_type == 'strap_defect' ? 'selected' : ''; ?> value="strap_defect"><?php function_exists('pll_e') ? pll_e('Strap Defect') : _e('Strap Defect') ?></option>
            <option <?php echo $issue_type == 'handle_defect' ? 'selected' : ''; ?> value="handle_defect"><?php function_exists('pll_e') ? pll_e('Handle Structure Defect') : _e('Handle Structure Defect') ?></option>
            <option <?php echo $issue_type == 'manufacturing_defect' ? 'selected' : ''; ?> value="manufacturing_defect"><?php function_exists('pll_e') ? pll_e('Manufacturing Defect (Upside down logo, asymmetrical sewing, etc.') : _e('Manufacturing Defect (Upside down logo, asymmetrical sewing, etc.') ?></option>
            <option <?php echo $issue_type == 'charging_defect' ? 'selected' : ''; ?> value="charging_defect"><?php function_exists('pll_e') ? pll_e('Charging Unit Faulty') : _e('Charging Unit Faulty') ?></option>
            <option <?php echo $issue_type == 'other_defect' ? 'selected' : ''; ?> value="other_defect"><?php function_exists('pll_e') ? pll_e('Other') : _e('Other') ?></option>
        </select>
    </p>

    <!-- issue type other textarea -->
    <p class="sbwcit_post_meta_cont" style="display: none;">
        <label for="issue_type_other"><?php function_exists('pll_e') ? pll_e('Other Issue:*') : _e('Other Issue:*') ?></label>
        <textarea name="issue_type_other" id="issue_type_other" cols="66" rows="10" placeholder="<?php function_exists('pll_e') ? pll_e('Please provide more info') : _e('Please provide more info') ?>"><?php echo $issue_type_other ?></textarea>
    </p>

    <!-- manufacturer -->
    <p class="sbwcit_post_meta_cont">
        <label for="manufacturer"><?php function_exists('pll_e') ? pll_e('Manufacturer:') : _e('Manufacturer:') ?></label>
        <input type="text" name="manufacturer" id="manufacturer" value="<?php echo $manufacturer ?>">
    </p>

    <!-- manufacture date -->
    <p class="sbwcit_post_meta_cont">
        <label for="manufacture_date"><?php function_exists('pll_e') ? pll_e('Manufacture Date:') : _e('Manufacture Date:') ?></label>
        <input type="date" name="manufacture_date" id="manufacture_date" value="<?php echo $manufacture_date ?>">
    </p>

    <!-- replacement reason -->
    <p class="sbwcit_post_meta_cont">
        <label for="reason"><?php function_exists('pll_e') ? pll_e('Replacement reason:') : _e('Replacement reason:') ?></label>
        <input type="text" name="reason" id="reason" value="<?php echo $reason ?>">
    </p>

    <!-- refund amount -->
    <p class="sbwcit_post_meta_cont">
        <label for="ref_amt"><?php function_exists('pll_e') ? pll_e('Refunded amount:') : _e('Refunded amount:') ?></label>
        <input type="text" name="ref_amt" id="ref_amt" value="<?php echo $ref_amount ?>">
    </p>

    <!-- status -->
    <p class="sbwcit_post_meta_cont">
        <label for="status"><?php function_exists('pll_e') ? pll_e('Issue status:') : _e('Issue status:') ?></label>
        <select name="status" id="status" current="<?php echo $status; ?>">
            <option value="pending"><?php function_exists('pll_e') ? pll_e('Pending') : _e('Pending') ?></option>
            <option value="resolved"><?php function_exists('pll_e') ? pll_e('Resolved') : _e('Resolved') ?></option>
        </select>
    </p>

    <!-- order data hidden input -->
    <input type="hidden" name="order_data" id="order_data" value="<?php echo esc_html($order_data); ?>">

    <!-- nonce -->
    <?php wp_nonce_field('sbwcit_meta_box', 'sbwcit_meta_box_nonce'); ?>

    <hr>

    <!-- gallery images -->
    <p class="sbwcit_post_gall_imgs">
        <?php
        sbwcit_gallery($post_id);
        ?>
    </p>


    <!-- order info modal overlay -->
    <div id="sbwcit-order-info-modal-overlay" style="display: none;"></div>

    <!-- order info modal actual -->
    <div id="sbwcit-order-info-modal" style="display: none;">

        <!-- heading -->
        <h4><?php _e('Order Info', 'default'); ?></h4>

        <!-- dismiss/close -->
        <span id="sbwcit-order-info-modal-close">&times;</span>

        <!-- modal cont -->
        <div id="sbwcit-order-info-modal-cont">

            <?php

            // decode order info
            $order_data = json_decode($order_data);

            // get order id
            $order_id = $order_data->id;

            // get billing info
            $billing = $order_data->billing;

            // get line item info
            $line_items = $order_data->line_items;

            // get order date
            $order_date = $order_data->date_created;

            // get order status
            $order_status = $order_data->status;

            // get order currency
            $order_currency = $order_data->currency;

            // get order total
            $order_total = $order_data->total;

            // get order discount total
            $order_discount_total = $order_data->discount_total;

            // render order info table with headings to the left and data to the right
            ?>

            <table id="sbwcit-order-data-table" class="wp-list-table widefat fixed striped table-view-list">
                <!-- order id -->
                <tr>
                    <th><?php _e('Original Order ID:', 'default'); ?></th>
                    <td><?php echo $order_id; ?></td>
                </tr>

                <!-- order date -->
                <tr>
                    <th><?php _e('Order Date:', 'default'); ?></th>
                    <td><?php echo $order_date; ?></td>
                </tr>

                <!-- order status -->
                <tr>
                    <th><?php _e('Status:', 'default'); ?></th>
                    <td><?php echo ucwords($order_status); ?></td>
                </tr>

                <!-- order total -->
                <tr>
                    <th><?php _e('Total:', 'default'); ?></th>
                    <td><?php echo $order_currency . $order_total; ?></td>
                </tr>

                <!-- discount -->
                <tr>
                    <th><?php _e('Discount:', 'default'); ?></th>
                    <td><?php echo $order_currency . $order_discount_total; ?></td>
                </tr>

                <!-- line items -->
                <tr>
                    <th><?php _e('Line Items:', 'default'); ?></th>
                    <td>
                        <table id="sbwcit-line-items-info">
                            <?php foreach ($line_items as $item) : ?>
                                <tr>
                                    <th style="font-weight: 600;"><?php _e('Product:', 'default'); ?></th>
                                    <td><?php echo $item->name; ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: 600;"><?php _e('SKU:', 'default'); ?></th>
                                    <td><?php echo $item->sku; ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: 600;"><?php _e('QTY:', 'default'); ?></th>
                                    <td><?php echo $item->quantity; ?></td>
                                </tr>

                            <?php endforeach; ?>
                        </table>
                    </td>
                </tr>

                <!-- shipping info -->
                <tr>
                    <th><?php _e('Shipping/Customer Info:', 'default'); ?></th>
                    <td>
                        <table id="sbwcit-shipping-info">

                            <!-- first name -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('First name:', 'default'); ?></th>
                                <td><?php echo $billing->first_name; ?></td>
                            </tr>

                            <!-- last name -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Last name:', 'default'); ?></th>
                                <td><?php echo $billing->last_name; ?></td>
                            </tr>

                            <!-- address 1 -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Address line 1:', 'default'); ?></th>
                                <td><?php echo $billing->address_1; ?></td>
                            </tr>

                            <!-- address 2 -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Address line 2:', 'default'); ?></th>
                                <td><?php echo $billing->address_2 ? $billing->address_2 : '-'; ?></td>
                            </tr>

                            <!-- city -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('City:', 'default'); ?></th>
                                <td><?php echo $billing->city; ?></td>
                            </tr>

                            <!-- state -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('State:', 'default'); ?></th>
                                <td><?php echo $billing->state ? $billing->state : '-'; ?></td>
                            </tr>

                            <!-- postcode -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Post code:', 'default'); ?></th>
                                <td><?php echo $billing->postcode; ?></td>
                            </tr>

                            <!-- country -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Country:', 'default'); ?></th>
                                <td><?php echo $billing->country; ?></td>
                            </tr>

                            <!-- phone -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Phone:', 'default'); ?></th>
                                <td>
                                    <!-- phone link -->
                                    <a href="tel:<?php echo $billing->phone; ?>"><?php echo $billing->phone; ?></a>
                                </td>
                            </tr>

                            <!-- email -->
                            <tr>
                                <th style="font-weight: 600;"><?php _e('Email:', 'default'); ?></th>
                                <td>
                                    <!-- mailto link -->
                                    <a href="mailto:<?php echo $billing->email; ?>"><?php echo $billing->email; ?></a>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>



        </div>



    </div>

    <!-- css -->
    <style>
        .sbwcit_post_meta_cont>label,
        .sbwcit-img-input>label {
            min-width: 220px;
            display: inline-block;
            font-weight: 600;
            font-style: italic;
            vertical-align: top;
        }

        p.sbwcit_post_meta_cont>input,
        p.sbwcit_post_meta_cont>select {
            min-width: 450px;
        }

        .sbwcit-btns {
            display: inline-block;
            vertical-align: middle;
            margin-right: 30px;
        }

        .sbwcit-img-input {
            display: inline-block;
            vertical-align: middle;
        }

        .sbwcit-add-gall-img.col-20 {
            width: 23%;
            display: inline-block;
            padding: 10px;
            vertical-align: middle;
        }

        .sbwcit-add-gall-img.col-20 img {
            border: 2px solid #ddd;
        }

        div#sbwcit-order-info-modal-overlay {
            background: #00000094;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
        }

        div#sbwcit-order-info-modal {
            position: absolute;
            top: -19vh;
            left: 18vw;
            background: white;
            width: 50vw;
            overflow-x: hidden;
            border-radius: 5px;
            padding: 20px;
        }

        span#sbwcit-order-info-modal-close {
            background: red;
            color: white;
            width: 25px;
            height: 25px;
            display: block;
            text-align: center;
            line-height: 1.8;
            border-radius: 50%;
            position: absolute;
            right: 5px;
            top: 5px;
            cursor: pointer;
        }

        div#sbwcit-order-info-modal>h4 {
            margin-top: 0;
            font-size: x-large;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }

        #sbwcit-order-data-table>tbody>tr>th {
            width: 30%;
            font-weight: 500;
        }

        #sbwcit-order-data-table>tbody>tr>td {
            width: 70%;
        }

        #sbwcit-order-data-table>tbody>tr:nth-child(6)>th,
        #sbwcit-order-data-table>tbody>tr:nth-child(7)>th {
            vertical-align: top;
            padding-top: 19px;
        }

        table#sbwcit-line-items-info th,
        table#sbwcit-shipping-info th {
            padding-left: 0;
        }
    </style>

    <!-- js -->
    <script>
        $ = jQuery;

        // check issue val
        function checkIssueVal() {

            var issueVal = $('#issue_type').val();

            if (issueVal == 'other_defect') {

                $('#issue_type_other').parent().show();

                // add required attr
                $('#issue_type_other').attr('required', true);
            } else {

                $('#issue_type_other').parent().hide();

                // remove required attr
                $('#issue_type_other').removeAttr('required');
            }
        }

        window.onload = function() {

            // if issue type is other, show textarea
            checkIssueVal();

            // add image on click
            $(document).on('click', '.sbwcit-add-img', function(e) {
                e.preventDefault();
                var to_add = $(document).find('.sbwcit-add-gall-img').html();
                $('#sbwcit-gallery-cont').append(to_add);
            });

            // rem image on click
            $(document).on('click', '.sbwcit-rem-img', function(e) {
                e.preventDefault();
                $(this).parents('.sbwcit-add-rem-img').remove();
            });

            // add file upload support to form #post
            $('#post').attr('enctype', 'multipart/form-data');

            // get order data
            var order_data = JSON.parse($('#order_data').val());

            // if order data exists
            if (order_data) {
                $('#sbwcit_meta_box > div.postbox-header > h2').append('<button class="button button-primary" onclick="showOrderInfo(event)">' + '<?php _e('View Order Info') ?>' + '</button>');
            }

        }

        // show order modal
        function showOrderInfo(event) {

            // stop default
            event.preventDefault();
            event.stopPropagation();

            // show modal and modal overlay
            $('#sbwcit-order-info-modal').show();
            $('#sbwcit-order-info-modal-overlay').show();

        }

        // modal close on click
        $(document).on('click', '#sbwcit-order-info-modal-close', function() {

            // hide modal and modal overlay
            $('#sbwcit-order-info-modal').hide();
            $('#sbwcit-order-info-modal-overlay').hide();

        });

        // set selected product
        $('#product').on('select2:select', function() {

            // get sku
            var sku = $(this).find(':selected').data('sku');

            // if sku data attribute matches
        });
    </script>

<?php }

// hook save post meta
add_action('save_post', 'sbwcit_save_post_meta');

// save custom meta fields
function sbwcit_save_post_meta($post_id)
{

    // bail if not product_issue post type
    if (get_post_type($post_id) != 'product_issue') {
        return;
    }

    // check if nonce is set
    if (!isset($_POST['sbwcit_meta_box_nonce'])) {
        return;
    }

    // verify nonce
    if (!wp_verify_nonce($_POST['sbwcit_meta_box_nonce'], 'sbwcit_meta_box')) {
        return;
    }

    // check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // check if user has permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // save date
    if (isset($_POST['issue_date'])) {
        update_post_meta($post_id, 'issue_date', sanitize_text_field($_POST['issue_date']));
    }

    // save ticket
    if (isset($_POST['ticket'])) {
        update_post_meta($post_id, 'ticket', sanitize_text_field($_POST['ticket']));
    }

    // save severity
    if (isset($_POST['severity'])) {
        update_post_meta($post_id, 'severity', sanitize_text_field($_POST['severity']));
    }

    // save order date
    if (isset($_POST['order_date'])) {
        update_post_meta($post_id, 'order_date', sanitize_text_field($_POST['order_date']));
    }

    // save manufacturer
    if (isset($_POST['manufacturer'])) {
        update_post_meta($post_id, 'manufacturer', sanitize_text_field($_POST['manufacturer']));
    }

    // save manufacture date
    if (isset($_POST['manufacture_date'])) {
        update_post_meta($post_id, 'manufacture_date', sanitize_text_field($_POST['manufacture_date']));
    }

    // save order number
    if (isset($_POST['order_no'])) {
        update_post_meta($post_id, 'order_no', sanitize_text_field($_POST['order_no']));
    }

    // save replacement order number
    if (isset($_POST['rep_order_no'])) {
        update_post_meta($post_id, 'rep_order_no', sanitize_text_field($_POST['rep_order_no']));
    }

    // save product name
    if (isset($_POST['product'])) {
        update_post_meta($post_id, 'product', sanitize_text_field($_POST['product']));
    }

    // save product sku
    if (isset($_POST['sku'])) {
        update_post_meta($post_id, 'sku', sanitize_text_field($_POST['sku']));
    }

    // save additional comments
    if (isset($_POST['comments'])) {
        update_post_meta($post_id, 'comments', sanitize_textarea_field($_POST['comments']));
    }

    // save issue type
    if (isset($_POST['issue_type'])) {
        update_post_meta($post_id, 'issue_type', sanitize_text_field($_POST['issue_type']));
    }

    // save issue type other
    if (isset($_POST['issue_type_other'])) {
        update_post_meta($post_id, 'issue_type_other', sanitize_textarea_field($_POST['issue_type_other']));
    }

    // save replacement reason
    if (isset($_POST['reason'])) {
        update_post_meta($post_id, 'reason', sanitize_text_field($_POST['reason']));
    }

    // save refund amount
    if (isset($_POST['ref_amt'])) {
        update_post_meta($post_id, 'ref_amt', sanitize_text_field($_POST['ref_amt']));
    }

    // save status
    if (isset($_POST['status'])) {
        update_post_meta($post_id, 'status', sanitize_text_field($_POST['status']));
    }

    // save gallery images
    if (isset($_FILES['sbwcit_gall_imgs'])) {

        // init images array
        $images = [];

        // loop through images
        foreach ($_FILES['sbwcit_gall_imgs']['name'] as $key => $value) {

            // if files exist
            if ($_FILES['sbwcit_gall_imgs']['size'][$key]) {

                // init file array
                $file = [
                    'name'     => $_FILES['sbwcit_gall_imgs']['name'][$key],
                    'type'     => $_FILES['sbwcit_gall_imgs']['type'][$key],
                    'tmp_name' => $_FILES['sbwcit_gall_imgs']['tmp_name'][$key],
                    'error'    => $_FILES['sbwcit_gall_imgs']['error'][$key],
                    'size'     => $_FILES['sbwcit_gall_imgs']['size'][$key]
                ];

                // upload file
                $upload = wp_handle_upload($file, ['test_form' => false]);

                // if upload successful
                if (!isset($upload['error']) && isset($upload['file'])) {
                    $images[] = $upload['url'];

                    // if upload failed
                } else {
                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                }
            }
        }

        // update post meta
        update_post_meta($post_id, 'issue_gallery', $images);
    }

    // save order data
    if (isset($_POST['order_data'])) {
        update_post_meta($post_id, 'order_data', sanitize_text_field($_POST['order_data']));
    }
}
