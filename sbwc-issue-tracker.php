<?php

/* Plugin Name: SBWC Order Issue Tracker
Version: 1.0.3b
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

    // cpt meta
    require_once SBWCIT_PATH . 'functions/custom-meta.php';
});
