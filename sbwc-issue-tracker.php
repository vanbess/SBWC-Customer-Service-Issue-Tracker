<?php

/* Plugin Name: SBWC Order Issue Tracker
Version: 1.0.5
Author: WC Bessinger
Description: Multi order issue tracker for WooCommerce
 */

if (!defined('ABSPATH')) :
    exit();
endif;

define('SBWCIT_PATH', plugin_dir_path(__FILE__));
define('SBWCIT_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', function () {

    // cpt
    require_once SBWCIT_PATH . 'functions/cpt.php';

    // import prods
    require_once SBWCIT_PATH . 'functions/import_prods.php';

    // select2
    function enqueue_select2_admin()
    {
        // Enqueue Select2 script
        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);

        // Enqueue Select2 styles
        wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13');
    }
    add_action('admin_enqueue_scripts', 'enqueue_select2_admin');
});
