<?php

// if accessed directly, exit
if (!defined('ABSPATH')) :
    exit;
endif;

// create custom hidden post type "product"
add_action('init', function () {

    // register post type
    register_post_type('product', [
        'labels'              => [
            'name'          => __('Products', 'sbwcit'),
            'singular_name' => __('Product', 'sbwcit'),
            'menu_name'     => __('Products', 'sbwcit'),
            'all_items'     => __('All Products', 'sbwcit'),
            'add_new'       => __('Add New', 'sbwcit'),
            'add_new_item'  => __('Add New Product', 'sbwcit'),
            'edit_item'     => __('Edit Product', 'sbwcit'),
            'new_item'      => __('New Product', 'sbwcit'),
            'view_item'     => __('View Product', 'sbwcit'),
            'search_items'  => __('Search Products', 'sbwcit'),
            'not_found'     => __('No Products Found', 'sbwcit'),
            'not_found_in_trash' => __('No Products Found in Trash', 'sbwcit'),
            'parent_item_colon'  => __('Parent Product', 'sbwcit')
        ],
        'description'         => __('Products', 'sbwcit'),
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-products',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail', 'custom-fields'],
        'has_archive'         => false,
        'rewrite'             => false,
        'query_var'           => true,
        'can_export'          => true
    ]);
});

/**
 * Create wp-cron to import products from csv every 24 hours
 */
add_action('init', function () {

    // if cron not scheduled, schedule it
    if (!wp_next_scheduled('sbwcit_import_products')) :
        wp_schedule_event(time(), 'daily', 'sbwcit_import_products');
    endif;

    // add action to run cron
    add_action('sbwcit_import_products', 'sbwcit_import_products_cron_action');
});

/**
 * Function to import products from csv
 *
 * @return void
 */
function sbwcit_import_products_cron_action()
{

    // get products csv from url
    $csv = file_get_contents('https://nordace.com/google_feed/products-Export-en-USD.csv');

    // map first two columns to array (column 1 is sku, column 2 is title)
    $csv = array_map('str_getcsv', explode("\n", $csv));

    // remove first row (column headers)
    array_shift($csv);

    // delete all product posts and associated meta
    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => 'any'
    ]);

    foreach ($products as $product) :
        wp_delete_post($product, true);
    endforeach;

    // loop through csv and create products if not exists, else update
    foreach ($csv as $product) :

        // if product title empty, skip
        if (empty($product[1])) continue;

        // create product
        $product_id = wp_insert_post([
            'post_title'  => $product[1],
            'post_type'   => 'product',
            'post_status' => 'publish'
        ]);

        // update product meta
        update_post_meta($product_id, 'sku', $product[0]);

    endforeach;

    // add log entry for reference
    sbwcit_add_log_entry('Products imported from CSV at ' . date('Y-m-d H:i:s') . '.');

}

/**
 * Function to add log entry
 */
function sbwcit_add_log_entry($message){
    
        // get log entries
        $log_entries = get_option('sbwcit_log_entries');

        // if log entries too big, remove oldest entry
        if(count($log_entries) > 30) array_shift($log_entries);
    
        // if no log entries, create empty array
        if(!$log_entries) $log_entries = [];
    
        // add new entry to array
        $log_entries[] = $message;
    
        // update option
        update_option('sbwcit_log_entries', $log_entries);
    
}

// Do initial import of product on plugin activation
register_activation_hook(SBWCIT_PATH . 'sbwc-issue-tracker.php', function () {

    // get products csv from url
    $csv = file_get_contents('https://nordace.com/google_feed/products-Export-en-USD.csv');

    // map first two columns to array (column 1 is sku, column 2 is title)
    $csv = array_map('str_getcsv', explode("\n", $csv));

    // remove first row (column headers)
    array_shift($csv);

    // loop through csv and create products if not exists, else update
    foreach ($csv as $product) :

        // if product title empty, skip
        if (empty($product[1])) continue;

        // create product
        $product_id = wp_insert_post([
            'post_title'  => $product[1],
            'post_type'   => 'product',
            'post_status' => 'publish'
        ]);

        // update product meta
        update_post_meta($product_id, 'sku', $product[0]);

    endforeach;
});

