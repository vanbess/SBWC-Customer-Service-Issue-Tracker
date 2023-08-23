<?php

// prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// add menu page
add_action('admin_menu', function () {
    add_menu_page(
        'CS Issues',
        'CS Issues',
        'manage_options',
        'cs-issues',
        'cs_issues_redirect',
        'dashicons-warning',
        6
    );
});


function cs_issues_redirect()
{
    wp_redirect('/wp-admin/edit.php?post_type=product_issue');
}

// Register post type
function cptui_register_my_cpts()
{
    /**
     * Post Type: Product Issues.
     */

    $labels = [
        "name"                     => __("Product Issues", "default"),
        "singular_name"            => __("Product Issue", "default"),
        "menu_name"                => __("Product Issues", "default"),
        "all_items"                => __("All Product Issues", "default"),
        "add_new"                  => __("Add new", "default"),
        "add_new_item"             => __("Add new Product Issue", "default"),
        "edit_item"                => __("Edit Product Issue", "default"),
        "new_item"                 => __("New Product Issue", "default"),
        "view_item"                => __("View Product Issue", "default"),
        "view_items"               => __("View Product Issues", "default"),
        "search_items"             => __("Search Product Issues", "default"),
        "not_found"                => __("No Product Issues found", "default"),
        "not_found_in_trash"       => __("No Product Issues found in trash", "default"),
        "parent"                   => __("Parent Product Issue:", "default"),
        "featured_image"           => __("Featured image for this Product Issue", "default"),
        "set_featured_image"       => __("Set featured image for this Product Issue", "default"),
        "remove_featured_image"    => __("Remove featured image for this Product Issue", "default"),
        "use_featured_image"       => __("Use as featured image for this Product Issue", "default"),
        "archives"                 => __("Product Issue archives", "default"),
        "insert_into_item"         => __("Insert into Product Issue", "default"),
        "uploaded_to_this_item"    => __("Upload to this Product Issue", "default"),
        "filter_items_list"        => __("Filter Product Issues list", "default"),
        "items_list_navigation"    => __("Product Issues list navigation", "default"),
        "items_list"               => __("Product Issues list", "default"),
        "attributes"               => __("Product Issues attributes", "default"),
        "name_admin_bar"           => __("Product Issue", "default"),
        "item_published"           => __("Product Issue published", "default"),
        "item_published_privately" => __("Product Issue published privately.", "default"),
        "item_reverted_to_draft"   => __("Product Issue reverted to draft.", "default"),
        "item_scheduled"           => __("Product Issue scheduled", "default"),
        "item_updated"             => __("Product Issue updated.", "default"),
        "parent_item_colon"        => __("Parent Product Issue:", "default"),
    ];

    $args = [
        "label"                 => __("Product Issues", "default"),
        "labels"                => $labels,
        "description"           => "CPT to capture product issue data",
        "public"                => true,
        "publicly_queryable"    => true,
        "show_ui"               => true,
        "show_in_rest"          => false,
        "rest_base"             => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive"           => false,
        "show_in_menu"          => "cs-issues",
        "show_in_nav_menus"     => false,
        "delete_with_user"      => false,
        "exclude_from_search"   => true,
        "capability_type"       => "post",
        "map_meta_cap"          => true,
        "hierarchical"          => false,
        "rewrite"               => ["slug" => "product_issue", "with_front" => false],
        "query_var"             => true,
        "menu_position"         => 5,
        "supports"              => ["title"],
    ];

    register_post_type("product_issue", $args);

    /**
     * Post Type: Shipping Issues.
     */

    $labels = [
        "name"                     => __("Shipping Issues", "default"),
        "singular_name"            => __("Shipping Issue", "default"),
        "menu_name"                => __("Shipping Issues", "default"),
        "all_items"                => __("All Shipping Issues", "default"),
        "add_new"                  => __("Add new", "default"),
        "add_new_item"             => __("Add new Shipping Issue", "default"),
        "edit_item"                => __("Edit Shipping Issue", "default"),
        "new_item"                 => __("New Shipping Issue", "default"),
        "view_item"                => __("View Shipping Issue", "default"),
        "view_items"               => __("View Shipping Issues", "default"),
        "search_items"             => __("Search Shipping Issues", "default"),
        "not_found"                => __("No Shipping Issues found", "default"),
        "not_found_in_trash"       => __("No Shipping Issues found in trash", "default"),
        "parent"                   => __("Parent Shipping Issue:", "default"),
        "featured_image"           => __("Featured image for this Shipping Issue", "default"),
        "set_featured_image"       => __("Set featured image for this Shipping Issue", "default"),
        "remove_featured_image"    => __("Remove featured image for this Shipping Issue", "default"),
        "use_featured_image"       => __("Use as featured image for this Shipping Issue", "default"),
        "archives"                 => __("Shipping Issue archives", "default"),
        "insert_into_item"         => __("Insert into Shipping Issue", "default"),
        "uploaded_to_this_item"    => __("Upload to this Shipping Issue", "default"),
        "filter_items_list"        => __("Filter Shipping Issues list", "default"),
        "items_list_navigation"    => __("Shipping Issues list navigation", "default"),
        "items_list"               => __("Shipping Issues list", "default"),
        "attributes"               => __("Shipping Issues attributes", "default"),
        "name_admin_bar"           => __("Shipping Issue", "default"),
        "item_published"           => __("Shipping Issue published", "default"),
        "item_published_privately" => __("Shipping Issue published privately.", "default"),
        "item_reverted_to_draft"   => __("Shipping Issue reverted to draft.", "default"),
        "item_scheduled"           => __("Shipping Issue scheduled", "default"),
        "item_updated"             => __("Shipping Issue updated.", "default"),
        "parent_item_colon"        => __("Parent Shipping Issue:", "default"),
    ];

    $args = [
        "label"                 => __("Shipping Issues", "default"),
        "labels"                => $labels,
        "description"           => "",
        "public"                => true,
        "publicly_queryable"    => false,
        "show_ui"               => true,
        "show_in_rest"          => false,
        "rest_base"             => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive"           => false,
        "show_in_menu"          => "cs-issues",
        "show_in_nav_menus"     => false,
        "delete_with_user"      => false,
        "exclude_from_search"   => false,
        "capability_type"       => "post",
        "map_meta_cap"          => true,
        "hierarchical"          => false,
        "rewrite"               => ["slug" => "shipping_issue", "with_front" => false],
        "query_var"             => true,
        "supports"              => ["title"],
    ];

    register_post_type("shipping_issue", $args);
}

add_action('init', 'cptui_register_my_cpts');
