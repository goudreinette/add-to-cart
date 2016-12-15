<?php namespace AddToCart;

/*
Plugin Name: Add To Cart
Plugin URI: https://github.com/reinvdwoerd/add-to-cart
Description:
Version: 1.0
Author: reinvdwoerd
Author URI: reinvdwoerd.herokuapp.com
License: -
Text Domain: add-to-cart
*/

/**
 * Directory
 */
$root = plugin_dir_url(__FILE__);
$path = plugin_dir_path(__FILE__);


/**
 * Autoload
 */
require __DIR__ . '/vendor/autoload.php';


/**
 * View
 */
use Utils\View;

$view = new View($root);
new Admin($view);
new SingleProduct($view);


/**
 * Run on init
 */
add_action('init', function () use ($view) {
});

add_action('admin_init', function () use ($view) {
});

/**
 * Translations
 */
add_action('plugins_loaded', function () {
    load_plugin_textdomain('add-to-cart', false, dirname(plugin_basename(__FILE__)));
});
