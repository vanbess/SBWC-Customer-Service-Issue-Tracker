<?php

// prevent direct access
if (!defined('ABSPATH')) :
    exit;
endif;

/**
 * Add custom filters to admin list (product issues)
 */
add_filter('views_edit-product_issue', function ($views) {

    // get product issues and build list of SKUs
    $p_issues = get_posts([
        'post_type'      => 'product_issue',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'order'          => 'ASC',
        'post_status'    => 'publish'
    ]);

    // if skus empty, bail
    if (empty($p_issues)) return;

    // get parent skus
    $parent_skus = [];

    // loop through product issues and get parent skus
    foreach ($p_issues as $issue) {

        // if sku is empty or null, skip
        if (empty(get_post_meta($issue, 'sku', true)) || is_null(get_post_meta($issue, 'sku', true))) continue;

        // if sku not in parent skus, add to array
        if (!in_array(explode('-', get_post_meta($issue, 'sku', true))[0], $parent_skus)) :
            $parent_skus[] = explode('-', get_post_meta($issue, 'sku', true))[0];
        endif;
    }

    foreach ($parent_skus as $parent_sku) :
        $views[$parent_sku] = '<a href="' . admin_url('edit.php?post_type=product_issue&meta_key=sku&meta_value=' . $parent_sku . '">') . $parent_sku . '</a>';
    endforeach;

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

    // get shipping issues and build list of SKUs
    $p_issues = get_posts([
        'post_type'      => 'shipping_issue',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'order'          => 'ASC',
        'post_status'    => 'publish'
    ]);

    // if skus empty, bail
    if (empty($p_issues)) return;

    // get parent skus
    $parent_skus = [];

    // loop through product issues and get parent skus
    foreach ($p_issues as $issue) {

        // if sku is empty or null, skip
        if (empty(get_post_meta($issue, 'sku', true)) || is_null(get_post_meta($issue, 'sku', true))) continue;

        // if sku not in parent skus, add to array
        if (!in_array(explode('-', get_post_meta($issue, 'sku', true))[0], $parent_skus)) :
            $parent_skus[] = explode('-', get_post_meta($issue, 'sku', true))[0];
        endif;
    }

    foreach ($parent_skus as $parent_sku) :
        $views[$parent_sku] = '<a href="' . admin_url('edit.php?post_type=shipping_issue&meta_key=sku&meta_value=' . $parent_sku . '">') . $parent_sku . '</a>';
    endforeach;

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

        // sort by sku LIKE submitted value
        if (isset($_GET['meta_value']) && isset($_GET['meta_key']) && $_GET['meta_key'] === 'sku') {

            $meta_query = array(
                array(
                    'key'     => 'sku',
                    'value'   => sanitize_text_field($_GET['meta_value']),
                    'compare' => 'LIKE',
                ),
            );
    
            $query->set('meta_query', $meta_query);

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
