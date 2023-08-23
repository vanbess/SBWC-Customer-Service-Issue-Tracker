<?php

// prevent direct access
if (!defined('ABSPATH')) :
    exit;
endif;

/**
 * Add custom filters to admin list (product issues)
 */
add_filter('views_edit-product_issue', function ($views) {

    // get all products and build list of SKUs
    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids'
    ]);

    $skus = [];

    foreach ($products as $product) {
        $skus[] = get_post_meta($product, '_sku', true);
    }

    $skus = array_unique($skus);

    // if $skus not empty, loop and add filter for each, else retrieve skus from product issues and add filter for each
    if (!empty($skus)) {
        foreach ($skus as $sku) {
            $views[$sku] = '<a href="' . admin_url('edit.php?post_type=product_issue&meta_key=sku&meta_value=' . $sku) . '">' . $sku . '</a>';
        }
    } else {
        $skus = get_posts([
            'post_type'      => 'product_issue',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_key'       => 'sku',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'post_status'    => 'publish'
        ]);

        foreach ($skus as $sku) {

            // get sku count
            $sku_count = count(get_posts([
                'post_type'      => 'product_issue',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_key'       => 'sku',
                'meta_value'     => get_post_meta($sku, 'sku', true),
                'post_status'    => 'publish'
            ]));

            $views[get_post_meta($sku, 'sku', true)] = '<a href="' . admin_url('edit.php?post_type=product_issue&meta_key=sku&meta_value=' . get_post_meta($sku, 'sku', true)) . '">' . get_post_meta($sku, 'sku', true) . ' <span class="count">(' . $sku_count . ')</span></a>';
        }
    }

    // get pending issues count
    $pending_count = count(get_posts([
        'post_type'      => 'product_issue',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => 'status',
        'meta_value'     => 'pending',
        'post_status'    => 'publish'
    ]));

    // get resolved issues count
    $resolved_count = count(get_posts([
        'post_type'      => 'product_issue',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => 'status',
        'meta_value'     => 'resolved',
        'post_status'    => 'publish'
    ]));

    // resolved/unresolved statuses
    $views['unresolved'] = '<a href="' . admin_url('edit.php?post_type=product_issue&meta_key=status&meta_value=pending') . '">' . __('Pending', 'default') . ' <span class="count">(' . $pending_count . ')</span></a>';
    $views['resolved']   = '<a href="' . admin_url('edit.php?post_type=product_issue&meta_key=status&meta_value=resolved') . '">' . __('Resolved', 'default') . ' <span class="count">(' . $resolved_count . ')</span></a>';

    return $views;
});

/**
 * Add custom filters to admin list (shipping issues)
 */
add_filter('views_edit-shipping_issue', function ($views) {

    // get all products and build list of SKUs
    $products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids'
    ]);

    $skus = [];

    foreach ($products as $product) {
        $skus[] = get_post_meta($product, '_sku', true);
    }

    $skus = array_unique($skus);

    // if $skus not empty, loop and add filter for each, else retrieve skus from product issues and add filter for each
    if (!empty($skus)) {
        foreach ($skus as $sku) {
            $views[$sku] = '<a href="' . admin_url('edit.php?post_type=shipping_issue&meta_key=sku&meta_value=' . $sku) . '">' . $sku . '</a>';
        }
    } else {
        $skus = get_posts([
            'post_type'      => 'shipping_issue',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_key'       => 'sku',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'post_status'    => 'publish'
        ]);

        foreach ($skus as $sku) {

            // get sku count
            $sku_count = count(get_posts([
                'post_type'      => 'shipping_issue',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_key'       => 'sku',
                'meta_value'     => get_post_meta($sku, 'sku', true),
                'post_status'    => 'publish'
            ]));

            $views[get_post_meta($sku, 'sku', true)] = '<a href="' . admin_url('edit.php?post_type=shipping_issue&meta_key=sku&meta_value=' . get_post_meta($sku, 'sku', true)) . '">' . get_post_meta($sku, 'sku', true) . ' <span class="count">(' . $sku_count . ')</span></a>';
        }
    }

    // get pending issues count
    $pending_count = count(get_posts([
        'post_type'      => 'shipping_issue',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => 'status',
        'meta_value'     => 'pending',
        'post_status'    => 'publish'
    ]));

    // get resolved issues count
    $resolved_count = count(get_posts([
        'post_type'      => 'shipping_issue',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => 'status',
        'meta_value'     => 'resolved',
        'post_status'    => 'publish'
    ]));

    // resolved/unresolved statuses
    $views['unresolved'] = '<a href="' . admin_url('edit.php?post_type=shipping_issue&meta_key=status&meta_value=pending') . '">' . __('Pending', 'default') . ' <span class="count">(' . $pending_count . ')</span></a>';
    $views['resolved']   = '<a href="' . admin_url('edit.php?post_type=shipping_issue&meta_key=status&meta_value=resolved') . '">' . __('Resolved', 'default') . ' <span class="count">(' . $resolved_count . ')</span></a>';

    return $views;
});

/**
 * Sort by SKU, resolved and unresolved statuses (product and shipping issues)
 */
add_action('pre_get_posts', function ($query) {

    if (!is_admin() || !$query->is_main_query()) return;

    global $pagenow, $typenow;

    if ($pagenow === 'edit.php' && $typenow === 'product_issue' || $typenow === 'shipping_issue') {

        // sort by sku
        if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'sku') {
            $meta_key                        = 'sku';
            $meta_value                      = sanitize_text_field($_GET['meta_value']);
            $query->query_vars['meta_key']   = $meta_key;
            $query->query_vars['meta_value'] = $meta_value;
        }

        // sort by unresolved status
        if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'status' && $_GET['meta_value'] === 'pending') {
            $meta_key                        = 'status';
            $meta_value                      = sanitize_text_field($_GET['meta_value']);
            $query->query_vars['meta_key']   = $meta_key;
            $query->query_vars['meta_value'] = $meta_value;
        }

        // sort by resolved status
        if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'status' && $_GET['meta_value'] === 'resolved') {
            $meta_key                        = 'status';
            $meta_value                      = sanitize_text_field($_GET['meta_value']);
            $query->query_vars['meta_key']   = $meta_key;
            $query->query_vars['meta_value'] = $meta_value;
        }
    }

    return $query;
});

/**
 * JS to add class 'current' to custom filters which are active
 */
add_action('admin_footer', function () {

    global $pagenow, $typenow;

    if ($pagenow === 'edit.php' && $typenow === 'product_issue' || $typenow === 'shipping_issue') { ?>

        <script>
            jQuery(document).ready(function($) {
                <?php if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'sku') : ?>

                    // if sku value == sku value in filter, add class 'current'
                    $('.subsubsub a[href*="meta_key=sku&meta_value=<?php echo $_GET['meta_value']; ?>"]').addClass('current');

                <?php endif; ?>

                <?php if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'status' && $_GET['meta_value'] === 'pending') : ?>
                    $('.subsubsub a[href*="meta_key=status&meta_value=pending"]').addClass('current');
                <?php endif; ?>

                <?php if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'status' && $_GET['meta_value'] === 'resolved') : ?>
                    $('.subsubsub a[href*="meta_key=status&meta_value=resolved"]').addClass('current');
                <?php endif; ?>
            });
        </script>

<?php }
});
