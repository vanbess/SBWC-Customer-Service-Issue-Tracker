<?php

// Register post type
function cptui_register_my_cpts_issue()
{

    /**
     * Post Type: Order Issues.
     */

    $labels = [
        "name" => __("CS Issues", "custom-post-type-ui"),
        "singular_name" => __("CS Issue", "custom-post-type-ui"),
    ];

    $args = [
        "label" => __("CS Issues", "custom-post-type-ui"),
        "labels" => $labels,
        "description" => "Custom post type to capture data for CS issues",
        "public" => true,
        "publicly_queryable" => false,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => false,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "menu_icon" => 'dashicons-warning',
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => ["slug" => "issue", "with_front" => true],
        "query_var" => true,
        "supports" => ["title", "thumbnail"],
    ];

    register_post_type("issue", $args);
}

add_action('init', 'cptui_register_my_cpts_issue');

// Customize post columns
function my_custom_columns_list($columns)
{

    unset($columns['author']);
    unset($columns['date']);

    $columns['issue_date']     = 'Issue date';
    $columns['ticket']     = 'Ticket URL';
    $columns['order_no']     = 'Order No';
    $columns['rep_order_no']     = 'Repl. Order No';
    $columns['ref_amt']     = 'Ref. Amount';
    $columns['status']     = 'Status';

    return $columns;
}
add_filter('manage_issue_posts_columns', 'my_custom_columns_list');

// Set custom column values
function issue_custom_column_values($column, $post_id)
{

    switch ($column) {

            // in this example, a Product has custom fields called 'product_number' and 'product_name'
        case 'issue_date':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'ticket':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'order_no':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'rep_order_no':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'ref_amt':
            echo get_post_meta($post_id, $column, true);
            break;

        case 'status':

            if (get_post_meta($post_id, $column, true) == 'resolved') : ?>
                <span class="sbwcir_resolved">
                    <?php echo ucfirst(get_post_meta($post_id, $column, true)); ?>
                </span>
            <?php else : ?>
                <span class="sbwcir_unresolved">
                    <?php echo ucfirst(get_post_meta($post_id, $column, true)); ?>
                </span>
            <?php endif;

            break;
    }
}
add_action('manage_issue_posts_custom_column', 'issue_custom_column_values', 10, 2);
