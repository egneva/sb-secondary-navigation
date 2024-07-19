<?php
class SB_Secondary_Navigation {
    public function init() {
        add_action('after_setup_theme', array($this, 'register_secondary_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('generate_after_header', array($this, 'display_secondary_menu'));
    }

    public function register_secondary_menu() {
        register_nav_menu('secondary-menu', __('Secondary Menu', 'sb-secondary-navigation'));
    }

    public function enqueue_styles() {
        wp_enqueue_style('sb-secondary-navigation', SB_SECONDARY_NAV_URL . 'assets/css/sb-secondary-navigation.css', array(), SB_SECONDARY_NAV_VERSION);
    }

    public function enqueue_scripts() {
        wp_enqueue_script('sb-secondary-navigation', SB_SECONDARY_NAV_URL . 'assets/js/sb-secondary-navigation.js', array('jquery'), SB_SECONDARY_NAV_VERSION, true);
    }

    public function display_secondary_menu() {
        if (has_nav_menu('secondary-menu')) {
            echo '<nav id="secondary-navigation" class="secondary-navigation" role="navigation">';
            wp_nav_menu(array(
                'theme_location' => 'secondary-menu',
                'container' => false,
                'menu_class' => 'secondary-menu',
                'fallback_cb' => false,
            ));
            echo '</nav>';
        }
    }
}
