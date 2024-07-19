<?php
/*
Plugin Name: SB Secondary Navigation
Plugin URI: https://egneva.com/
Description: Adds a customizable secondary navigation menu to your WordPress site.
Version: 1.0
Author: S Balu
Author URI: https://egneva.com/
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SB_SECONDARY_NAV_PATH', plugin_dir_path(__FILE__));
define('SB_SECONDARY_NAV_URL', plugin_dir_url(__FILE__));
define('SB_SECONDARY_NAV_VERSION', '1.0');

// Include necessary files
require_once SB_SECONDARY_NAV_PATH . 'includes/class-sb-secondary-navigation.php';

// Initialize the plugin
function sb_secondary_navigation_init() {
    $sb_secondary_navigation = new SB_Secondary_Navigation();
    $sb_secondary_navigation->init();
}
add_action('plugins_loaded', 'sb_secondary_navigation_init');
