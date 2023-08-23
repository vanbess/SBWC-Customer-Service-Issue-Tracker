<?php

// prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Customize post columns
function cs_issues_custom_cols($columns)
{

    unset($columns['author']);
    unset($columns['date']);

    $columns['issue_date']   = __('Issue date', 'default');
    $columns['issue_type']   = __('Issue type', 'default');
    $columns['manufacturer'] = __('Manufacturer', 'default');
    $columns['ticket']       = __('Ticket URL', 'default');
    $columns['severity']     = __('Severity', 'default');
    $columns['order_no']     = __('Order No', 'default');
    $columns['order_date']   = __('Order Date', 'default');
    $columns['rep_order_no'] = __('Repl. Order No', 'default');
    $columns['ref_amt']      = __('Ref. Amount', 'default');
    $columns['status']       = __('Status', 'default');

    return $columns;
}
add_filter('manage_product_issue_posts_columns', 'cs_issues_custom_cols');
add_filter('manage_shipping_issue_posts_columns', 'cs_issues_custom_cols');

// Set custom column values
function product_issue_custom_column_values($column, $post_id)
{

    switch ($column) {

        case 'issue_date':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'issue_type':
            echo ucwords(str_replace('_', ' ', get_post_meta($post_id, $column, true)));
            break;

        case 'manufacturer':
            echo get_post_meta($post_id, $column, true) ? '<b>' . get_post_meta($post_id, $column, true) . '</b><br>' : '-';
            echo get_post_meta($post_id, 'manufacture_date', true) ? __('<b>MFG Date:</b> ', 'default') . get_post_meta($post_id, 'manufacture_date', true) . '<br>' : '-';
            break;

        case 'ticket':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'severity': ?>

            <style>
                span.sbwcir_high {
                    background: #d63638;
                    display: block;
                    text-align: center;
                    padding: 5px;
                    border-radius: 3px;
                    color: white;
                    font-weight: 600;
                    width: 100px;
                }

                span.sbwcir_medium {
                    background: #dba617;
                    display: block;
                    text-align: center;
                    padding: 5px;
                    border-radius: 3px;
                    color: white;
                    font-weight: 600;
                    width: 100px;
                }

                span.sbwcir_low {
                    background: #72aee6;
                    display: block;
                    text-align: center;
                    padding: 5px;
                    border-radius: 3px;
                    color: white;
                    font-weight: 600;
                    width: 100px;
                }
            </style>

            <?php
            // set different background colors for different severity levels
            switch (get_post_meta($post_id, $column, true)):
                case 'high': ?>
                    <span class="sbwcir_high"><?php _e('High', 'default'); ?></span>
                <?php
                    break;

                case 'medium': ?>
                    <span class="sbwcir_medium"><?php _e('Medium', 'default'); ?></span>
                <?php
                    break;

                case 'low': ?>
                    <span class="sbwcir_low"><?php _e('Low', 'default'); ?></span>
            <?php
                    break;
                case '':
                    echo '-';
                    break;
            endswitch;

            break;

        case 'order_no':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'order_date':
            echo get_post_meta($post_id, $column, true) ? get_post_meta($post_id, $column, true) : '-';
            break;

        case 'rep_order_no':
            echo get_post_meta($post_id, $column, true) ? get_post_meta($post_id, $column, true) : '-';
            break;

        case 'ref_amt':
            echo get_post_meta($post_id, $column, true) ? get_post_meta($post_id, $column, true) : '-';
            break;

        case 'status': ?>

            <style>
                span.sbwcir_unresolved {
                    background: indianred;
                    color: white;
                    display: block;
                    text-align: center;
                    font-weight: 600;
                    padding: 5px;
                    border-radius: 3px;
                    width: 100px;
                }

                span.sbwcir_resolved {
                    background: seagreen;
                    color: white;
                    display: block;
                    text-align: center;
                    font-weight: 600;
                    padding: 5px;
                    border-radius: 3px;
                    width: 100px;
                }
            </style>

            <?php switch (get_post_meta($post_id, $column, true)):
                case 'resolved': ?>
                    <span class="sbwcir_resolved">
                        <?php echo ucfirst(get_post_meta($post_id, $column, true)); ?>
                    </span>
                <?php break;

                default: ?>
                    <span class="sbwcir_unresolved">
                        <?php echo ucfirst(get_post_meta($post_id, $column, true)); ?>
                    </span>
<?php break;
            endswitch;
    }
}
add_action('manage_product_issue_posts_custom_column', 'product_issue_custom_column_values', 10, 2);
add_action('manage_shipping_issue_posts_custom_column', 'product_issue_custom_column_values', 10, 2);
