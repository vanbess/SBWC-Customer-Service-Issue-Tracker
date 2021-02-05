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
    $post_id = $post->ID;
    $date = get_post_meta($post_id, 'issue_date', true);
    $ticket = get_post_meta($post_id, 'ticket', true);
    $order_no = get_post_meta($post_id, 'order_no', true);
    $rep_order_no = get_post_meta($post_id, 'rep_order_no', true);
    $reason = get_post_meta($post_id, 'reason', true);
    $ref_amount = get_post_meta($post_id, 'ref_amt', true);
    $status = get_post_meta($post_id, 'status', true);

    // enqueue js here so that it only runs here, nowhere else
    wp_enqueue_script('sbwcit-', SBWCIT_URL . 'assets/admin.js');

?>

    <!-- date -->
    <div class="sbwcit_post_meta_cont">
        <label for="issue_date"><?php pll_e('Issue date') ?></label>
        <input type="date" name="issue_date" id="issue_date" value="<?php echo $date; ?>">
    </div>

    <!-- ticket -->
    <div class="sbwcit_post_meta_cont">
        <label for="ticket"><?php pll_e('Ticket reference') ?></label>
        <input type="url" name="ticket" id="ticket" value="<?php echo $ticket ?>" required>
    </div>

    <!-- original order number -->
    <div class="sbwcit_post_meta_cont">
        <label for="order_no"><?php pll_e('Original order number') ?></label>
        <input type="text" name="order_no" id="order_no" value="<?php echo $order_no ?>" required>
    </div>

    <!-- replacement order number -->
    <div class="sbwcit_post_meta_cont">
        <label for="rep_order_no"><?php pll_e('Replacement order number') ?></label>
        <input type="text" name="rep_order_no" id="rep_order_no" value="<?php echo $rep_order_no ?>">
    </div>

    <!-- replacement reason -->
    <div class="sbwcit_post_meta_cont">
        <label for="reason"><?php pll_e('Replacement reason') ?></label>
        <input type="text" name="reason" id="reason" value="<?php echo $reason ?>" required>
    </div>

    <!-- refund amount -->
    <div class="sbwcit_post_meta_cont">
        <label for="ref_amt"><?php pll_e('Refunded amount') ?></label>
        <input type="text" name="ref_amt" id="ref_amt" value="<?php echo $ref_amount ?>" required>
    </div>

    <!-- status -->
    <div class="sbwcit_post_meta_cont">
        <label for="status"><?php pll_e('Issue status') ?></label>
        <select name="status" id="status" current="<?php echo $status; ?>">
            <option value="pending"><?php pll_e('Pending') ?></option>
            <option value="resolved"><?php pll_e('Resolved') ?></option>
        </select>
    </div>

    <!-- gallery images -->
    <div class="sbwcit_post_gall_imgs">
        <?php
        sbwcit_gallery($post_id);
        ?>
    </div>

<?php }

// save post data via ajax (used specifically because saving files are too involved otherwise)
add_action('wp_ajax_nopriv_sbwcit_save_issue_data', 'sbwcit_save_issue_data');
add_action('wp_ajax_sbwcit_save_issue_data', 'sbwcit_save_issue_data');

function sbwcit_save_issue_data()
{

    // required for file uploads
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    // upload overrides
    $overrides = [
        'test_form' => false,
        'test_size' => true,
        'test_upload' => true
    ];

    // update post
    $updated = wp_update_post([
        'ID' => $_POST['post_ID'],
        'post_author' => $_POST['post_author'],
        'post_title' => $_POST['post_title'],
        'post_status' => 'publish',
        'post_type' => $_POST['post_type'],
        'meta_input' => [
            'issue_date' => $_POST['issue_date'],
            'ticket' => $_POST['ticket'],
            'order_no' => $_POST['order_no'],
            'rep_order_no' => $_POST['rep_order_no'],
            'reason' => $_POST['reason'],
            'ref_amt' => $_POST['ref_amt'],
            'status' => $_POST['status']
        ]
    ]);

    // upload files if present
    if (!empty($_FILES)) :

        $file_count = count($_FILES);

        for ($i = 0; $i < $file_count; $i++) {
            $file_urls[$i] = wp_handle_sideload($_FILES['sbwcit_gall_img_' . $i], $overrides)['url'];
        }

    endif;

    // add img urls to post if urls present
    if (!empty($file_urls)) :
        update_post_meta($_POST['post_ID'], 'issue_gallery', maybe_serialize($file_urls));
    endif;

    // if updated successfully, return success message
    if ($updated) :
        print 'success';
    endif;

    wp_die();
}
