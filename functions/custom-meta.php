<?php

// include gallery img function
include SBWCIT_PATH . 'functions/gallery.php';

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

?>

    <!-- date -->
    <p class="sbwcit_post_meta_cont">
        <label for="issue_date"><?php function_exists('pll_e') ? pll_e('Issue date:') : _e('Issue date:') ?></label>
        <input type="date" name="issue_date" id="issue_date" value="<?php echo $date ? $date : date('Y-m-d'); ?>">
    </p>

    <!-- ticket -->
    <p class="sbwcit_post_meta_cont">
        <label for="ticket"><?php function_exists('pll_e') ? pll_e('Ticket reference:') : _e('Ticket reference:') ?></label>
        <input type="url" name="ticket" id="ticket" value="<?php echo $ticket ?>" placeholder="<?php function_exists('pll_e') ? pll_e('ticket reference URL') : _e('ticket reference URL') ?>" required>
    </p>

    <!-- original order number -->
    <p class="sbwcit_post_meta_cont">
        <label for="order_no"><?php function_exists('pll_e') ? pll_e('Original order number:') : _e('Original order number:') ?></label>
        <input type="text" name="order_no" id="order_no" value="<?php echo $order_no ?>" required>
    </p>

    <!-- replacement order number -->
    <p class="sbwcit_post_meta_cont">
        <label for="rep_order_no"><?php function_exists('pll_e') ? pll_e('Replacement order number:') : _e('Replacement order number:') ?></label>
        <input type="text" name="rep_order_no" id="rep_order_no" value="<?php echo $rep_order_no ?>">
    </p>

    <!-- product -->
    <p class="sbwcit_post_meta_cont">
        <label for="product"><?php function_exists('pll_e') ? pll_e('Product:') : _e('Product:') ?></label>
        <input type="text" name="product" id="product" value="<?php echo $product ?>" required>
    </p>

    <!-- SKU -->
    <p class="sbwcit_post_meta_cont">
        <label for="sku"><?php function_exists('pll_e') ? pll_e('SKU:') : _e('SKU:') ?></label>
        <input type="text" name="sku" id="sku" value="<?php echo $sku ?>" required>
    </p>

    <!-- comments -->
    <p class="sbwcit_post_meta_cont">
        <label for="comments"><?php function_exists('pll_e') ? pll_e('Additional Comments:') : _e('Additional Comments:') ?></label>
        <textarea name="comments" id="comments" cols="66" rows="10"><?php echo $comments ?></textarea>
    </p>

    <!-- issue type -->
    <p class="sbwcit_post_meta_cont">
        <label for="issue_type"><?php function_exists('pll_e') ? pll_e('Issue Type:') : _e('Issue Type:') ?></label>
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
        <label for="issue_type_other"><?php function_exists('pll_e') ? pll_e('Other Issue:') : _e('Other Issue:') ?></label>
        <textarea name="issue_type_other" id="issue_type_other" cols="66" rows="10" placeholder="<?php function_exists('pll_e') ? pll_e('Please provide more info') : _e('Please provide more info') ?>"><?php echo $issue_type_other ?></textarea>
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

    <!-- nonce -->
    <?php wp_nonce_field('sbwcit_meta_box', 'sbwcit_meta_box_nonce'); ?>

    <hr>

    <!-- gallery images -->
    <p class="sbwcit_post_gall_imgs">
        <?php
        sbwcit_gallery($post_id);
        ?>
    </p>

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
    </style>

    <!-- js -->
    <script>
        $ = jQuery;

        // check issue val
        function checkIssueVal() {

            var issueVal = $('#issue_type').val();

            if (issueVal == 'other_defect') {
                $('#issue_type_other').parent().show();
            } else {
                $('#issue_type_other').parent().hide();
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

        }
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
}
