<?php

/**
 * Register custom meta fields for issue tracker
 */
function sbwcit_add_custom_box()
{
    add_meta_box(
        'sbwcit_meta_box',
        'Issue Tracker Metadata',
        'sbwcit_meta_box_callback',
        'issue'
    );
}
add_action('add_meta_boxes', 'sbwcit_add_custom_box');

/**
 * Render custom meta box html
 */
function sbwcit_meta_box_callback($post)
{
    $post_id = $post->ID;
    $date = get_post_meta($post_id, 'issue_date', true);
    $ticket = get_post_meta($post_id, 'ticket', true);
    $order_no = get_post_meta($post_id, 'order_no', true);
    $rep_order_no = get_post_meta($post_id, 'rep_order_no', true);
    $reason = get_post_meta($post_id, 'reason', true);
    $ref_amount = get_post_meta($post_id, 'ref_amt', true);
    $status = get_post_meta($post_id, 'status', true); ?>

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

    <script>
        $(document).ready(function() {
            var status = $('#status').attr('current');
            $('#status').val(status);
        });
    </script>

<?php }

/**
 * Save metadata as needed
 */
function sbwcit_save_meta($post_id)
{
    // date
    if (isset($_POST['issue_date'])) :
        update_post_meta($post_id, 'issue_date', $_POST['issue_date']);
    endif;

    // ticket link
    if (isset($_POST['ticket'])) :
        update_post_meta($post_id, 'ticket', $_POST['ticket']);
    endif;

    // order no
    if (isset($_POST['order_no'])) :
        update_post_meta($post_id, 'order_no', $_POST['order_no']);
    endif;

    // replacement order no
    if (isset($_POST['rep_order_no'])) :
        update_post_meta($post_id, 'rep_order_no', $_POST['rep_order_no']);
    endif;

    // reason
    if (isset($_POST['reason'])) :
        update_post_meta($post_id, 'reason', $_POST['reason']);
    endif;

    // refund amount
    if (isset($_POST['ref_amt'])) :
        update_post_meta($post_id, 'ref_amt', $_POST['ref_amt']);
    endif;

    // status
    if (isset($_POST['status'])) :
        update_post_meta($post_id, 'status', $_POST['status']);
    endif;
}

add_action('save_post', 'sbwcit_save_meta');
