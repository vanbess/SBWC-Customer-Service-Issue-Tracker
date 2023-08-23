<?php

// prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Make columns sortable
function product_issue_sortable_columns($columns)
{
    $columns['issue_date'] = 'issue_date';
    $columns['status']     = 'status';
    $columns['severity']   = ['severity', true];

    return $columns;
}

add_filter('manage_edit-product_issue_sortable_columns', 'product_issue_sortable_columns');
add_filter('manage_edit-shipping_issue_sortable_columns', 'product_issue_sortable_columns');

/**
 * Sort by severity
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) return;

    $orderby = $query->get('orderby');

    if ('severity' == $orderby) {
        $query->set('meta_key', 'severity');
        $query->set('orderby', 'meta_value');

        // Reverse the order direction if already set
        $order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'desc' : 'asc';
        $query->set('order', $order);

        add_filter('posts_orderby', function ($orderby) use ($query) {
            global $wpdb;
            $order = $query->get('order');
            return "FIELD({$wpdb->prefix}postmeta.meta_value, 'low', 'medium', 'high') " . $order;
        });
    }
});

/**
 * Sort by status
 */
add_action('pre_get_posts', function ($query) {

    if (!is_admin() || !$query->is_main_query()) return;

    $orderby = $query->get('orderby');

    if ('status' == $orderby) {
        $query->set('meta_key', 'status');
        $query->set('orderby', 'meta_value');

        // Reverse the order direction if already set
        $order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'desc' : 'asc';
        $query->set('order', $order);

        add_filter('posts_orderby', function ($orderby) use ($query) {
            global $wpdb;
            $order = $query->get('order');
            return "FIELD({$wpdb->prefix}postmeta.meta_value, 'pending', 'resolved') " . $order;
        });
    }
});
