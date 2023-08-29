<?php

// if accessed directly, exit
if (!defined('ABSPATH')) :
    exit;
endif;

// add ajax action to fetch order date remotely based on order id and WooCommerce API key and secret
add_action('wp_ajax_sbwcit_fetch_order_date', 'sbwcit_fetch_order_date');

// fetch order date
function sbwcit_fetch_order_date()
{

    // get order id
    $order_no = $_POST['order_no'];

    // get api key
    $api_key = $_POST['api_key'];

    // get api secret
    $api_secret = $_POST['api_secret'];

    // live keys (nordace.com)
    $order_no_url = 'https://nordace.com/wp-json/custom/v1/order/' . $order_no;

    // Request 1: get order id

    // init curl
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $order_no_url);

    // set method
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // set api key and secret
    // curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);

    // set returntransfer to true
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // set ssl verify to false
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // set header
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));

    // execute curl
    $result = curl_exec($ch);

    // close curl
    curl_close($ch);

    // decode result
    $result = json_decode($result, true);

    // get order id
    $order_id = $result['order_id'];

    // new curl request to get order data
    $order_url = 'https://nordace.com/wp-json/wc/v3/orders/' . $order_id;

    // Request 2: get order data

    // init curl
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $order_url);

    // set method
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // set api key and secret
    curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);

    // set returntransfer to true
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // set ssl verify to false
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // set header
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));

    // execute curl
    $result = curl_exec($ch);

    // close curl
    curl_close($ch);

    // if no error, decode and send, else send error
    if (!$result) {
        $result = array(
            'error' => 'Error fetching order data'
        );
        wp_send_json_error($result);
    } else {
        $result = json_decode($result, true);
        wp_send_json_success($result);
    }

    exit;
}
