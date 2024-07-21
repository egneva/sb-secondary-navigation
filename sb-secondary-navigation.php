<?php
/*
Plugin Name: SB Secondary Navigation
Plugin URI: https://sibenotes.com/
Description: Adds a customizable secondary navigation menu to your WordPress site.
Version: 1.0.1
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
define('SB_SECONDARY_NAV_VERSION', '1.0.1');

// Include necessary files
require_once SB_SECONDARY_NAV_PATH . 'includes/class-sb-secondary-navigation.php';

// Initialize the plugin
function sb_secondary_navigation_init() {
    $sb_secondary_navigation = new SB_Secondary_Navigation();
    $sb_secondary_navigation->init();
}
add_action('plugins_loaded', 'sb_secondary_navigation_init');

// Add settings link on plugin page
function sb_secondary_navigation_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=sb-secondary-navigation">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sb_secondary_navigation_settings_link');


//enque styles, fix z index issue btw main and secondary 
function sb_secondary_nav_enqueue_styles() {
    wp_add_inline_style('sb-secondary-navigation', '
        /* Ensure main menu is above secondary menu */
        .main-navigation,
        .main-menu-container,
        #primary-menu,
        .menu-main-menu-container {
            position: relative;
            z-index: 1000;
        }

        /* Dropdown menus should have a higher z-index */
        .main-navigation ul ul,
        .main-menu-container ul ul,
        #primary-menu ul ul,
        .menu-main-menu-container ul ul {
            z-index: 1001;
        }

        /* Secondary menu should have a lower z-index */
        .secondary-menu-container,
        #secondary-menu,
        .menu-secondary-menu-container {
            position: relative;
            z-index: 999;
        }
    ');
}
add_action('wp_enqueue_scripts', 'sb_secondary_nav_enqueue_styles', 20);

// Make sure you have a function to enqueue your main plugin stylesheet
function sb_secondary_nav_enqueue_main_style() {
    wp_enqueue_style('sb-secondary-navigation', plugin_dir_url(__FILE__) . 'css/secondary-navigation.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'sb_secondary_nav_enqueue_main_style');
