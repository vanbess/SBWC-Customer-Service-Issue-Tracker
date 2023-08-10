<?php

// add menu page
add_action('admin_menu', function(){
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
		"name"                     => __("Product Issues", "woocommerce"),
		"singular_name"            => __("Product Issue", "woocommerce"),
		"menu_name"                => __("Product Issues", "woocommerce"),
		"all_items"                => __("All Product Issues", "woocommerce"),
		"add_new"                  => __("Add new", "woocommerce"),
		"add_new_item"             => __("Add new Product Issue", "woocommerce"),
		"edit_item"                => __("Edit Product Issue", "woocommerce"),
		"new_item"                 => __("New Product Issue", "woocommerce"),
		"view_item"                => __("View Product Issue", "woocommerce"),
		"view_items"               => __("View Product Issues", "woocommerce"),
		"search_items"             => __("Search Product Issues", "woocommerce"),
		"not_found"                => __("No Product Issues found", "woocommerce"),
		"not_found_in_trash"       => __("No Product Issues found in trash", "woocommerce"),
		"parent"                   => __("Parent Product Issue:", "woocommerce"),
		"featured_image"           => __("Featured image for this Product Issue", "woocommerce"),
		"set_featured_image"       => __("Set featured image for this Product Issue", "woocommerce"),
		"remove_featured_image"    => __("Remove featured image for this Product Issue", "woocommerce"),
		"use_featured_image"       => __("Use as featured image for this Product Issue", "woocommerce"),
		"archives"                 => __("Product Issue archives", "woocommerce"),
		"insert_into_item"         => __("Insert into Product Issue", "woocommerce"),
		"uploaded_to_this_item"    => __("Upload to this Product Issue", "woocommerce"),
		"filter_items_list"        => __("Filter Product Issues list", "woocommerce"),
		"items_list_navigation"    => __("Product Issues list navigation", "woocommerce"),
		"items_list"               => __("Product Issues list", "woocommerce"),
		"attributes"               => __("Product Issues attributes", "woocommerce"),
		"name_admin_bar"           => __("Product Issue", "woocommerce"),
		"item_published"           => __("Product Issue published", "woocommerce"),
		"item_published_privately" => __("Product Issue published privately.", "woocommerce"),
		"item_reverted_to_draft"   => __("Product Issue reverted to draft.", "woocommerce"),
		"item_scheduled"           => __("Product Issue scheduled", "woocommerce"),
		"item_updated"             => __("Product Issue updated.", "woocommerce"),
		"parent_item_colon"        => __("Parent Product Issue:", "woocommerce"),
	];

	$args = [
		"label"                 => __("Product Issues", "woocommerce"),
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
		"name"                     => __("Shipping Issues", "woocommerce"),
		"singular_name"            => __("Shipping Issue", "woocommerce"),
		"menu_name"                => __("Shipping Issues", "woocommerce"),
		"all_items"                => __("All Shipping Issues", "woocommerce"),
		"add_new"                  => __("Add new", "woocommerce"),
		"add_new_item"             => __("Add new Shipping Issue", "woocommerce"),
		"edit_item"                => __("Edit Shipping Issue", "woocommerce"),
		"new_item"                 => __("New Shipping Issue", "woocommerce"),
		"view_item"                => __("View Shipping Issue", "woocommerce"),
		"view_items"               => __("View Shipping Issues", "woocommerce"),
		"search_items"             => __("Search Shipping Issues", "woocommerce"),
		"not_found"                => __("No Shipping Issues found", "woocommerce"),
		"not_found_in_trash"       => __("No Shipping Issues found in trash", "woocommerce"),
		"parent"                   => __("Parent Shipping Issue:", "woocommerce"),
		"featured_image"           => __("Featured image for this Shipping Issue", "woocommerce"),
		"set_featured_image"       => __("Set featured image for this Shipping Issue", "woocommerce"),
		"remove_featured_image"    => __("Remove featured image for this Shipping Issue", "woocommerce"),
		"use_featured_image"       => __("Use as featured image for this Shipping Issue", "woocommerce"),
		"archives"                 => __("Shipping Issue archives", "woocommerce"),
		"insert_into_item"         => __("Insert into Shipping Issue", "woocommerce"),
		"uploaded_to_this_item"    => __("Upload to this Shipping Issue", "woocommerce"),
		"filter_items_list"        => __("Filter Shipping Issues list", "woocommerce"),
		"items_list_navigation"    => __("Shipping Issues list navigation", "woocommerce"),
		"items_list"               => __("Shipping Issues list", "woocommerce"),
		"attributes"               => __("Shipping Issues attributes", "woocommerce"),
		"name_admin_bar"           => __("Shipping Issue", "woocommerce"),
		"item_published"           => __("Shipping Issue published", "woocommerce"),
		"item_published_privately" => __("Shipping Issue published privately.", "woocommerce"),
		"item_reverted_to_draft"   => __("Shipping Issue reverted to draft.", "woocommerce"),
		"item_scheduled"           => __("Shipping Issue scheduled", "woocommerce"),
		"item_updated"             => __("Shipping Issue updated.", "woocommerce"),
		"parent_item_colon"        => __("Parent Shipping Issue:", "woocommerce"),
	];

	$args = [
		"label"                 => __("Shipping Issues", "woocommerce"),
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



// Customize post columns
function my_custom_columns_list($columns)
{

	unset($columns['author']);
	unset($columns['date']);

	$columns['issue_date']   = 'Issue date';
	$columns['ticket']       = 'Ticket URL';
	$columns['order_no']     = 'Order No';
	$columns['rep_order_no'] = 'Repl. Order No';
	$columns['ref_amt']      = 'Ref. Amount';
	$columns['status']       = 'Status';

	return $columns;
}
add_filter('manage_product_issue_posts_columns', 'my_custom_columns_list');
add_filter('manage_shipping_issue_posts_columns', 'my_custom_columns_list');

// Set custom column values
function product_issue_custom_column_values($column, $post_id)
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
add_action('manage_product_issue_posts_custom_column', 'product_issue_custom_column_values', 10, 2);
add_action('manage_shipping_issue_posts_custom_column', 'product_issue_custom_column_values', 10, 2);
