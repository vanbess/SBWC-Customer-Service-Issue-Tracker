<?php

/* Plugin Name: SBWC Order Issue Tracker
Version: 1.0.3
Author: WC Bessinger
Description: Multi order issue tracker for WooCommerce
 */

if (!defined('ABSPATH')) :
    exit();
endif;

define('SBWCIT_PATH', plugin_dir_path(__FILE__));
define('SBWCIT_URL', plugin_dir_url(__FILE__));

function sbwcit_load()
{
    // cpt
    require_once SBWCIT_PATH . 'functions/cpt.php';

    // cpt meta
    require_once SBWCIT_PATH . 'functions/custom-meta.php';

    // css
    add_action('wp_enqueue_scripts', 'sbwcit_scripts');
    function sbwcit_scripts()
    {
        wp_enqueue_style('sbwcit-', SBWCIT_URL . 'assets/admin.css');
    }
}

add_action('plugins_loaded', 'sbwcit_load');
