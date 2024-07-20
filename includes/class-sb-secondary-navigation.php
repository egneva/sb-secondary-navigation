<?php
class SB_Secondary_Navigation {
    private $options;

    public function init() {
        $this->options = get_option('sb_secondary_nav_options', $this->get_default_options());
        
        add_action('after_setup_theme', array($this, 'register_secondary_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('generate_after_header', array($this, 'display_secondary_menu'));
        
        // Add admin menu and settings
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add settings link in plugin list
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
    }

    public function get_default_options() {
        return array(
            'bg_color' => '#f8f8f8',
            'text_color' => '#333333',
            'border_color' => '#dddddd',
            'hover_bg_color' => '#e8e8e8',
            'hover_text_color' => '#000000',
            'submenu_bg_color' => '#ffffff',
            'font_size' => '16px',
            'font_family' => 'Arial, sans-serif'
        );
    }

    public function register_secondary_menu() {
        register_nav_menu('secondary-menu', __('Secondary Menu', 'sb-secondary-navigation'));
    }

    public function enqueue_styles() {
        wp_enqueue_style('sb-secondary-navigation', SB_SECONDARY_NAV_URL . 'assets/css/sb-secondary-navigation.css', array(), SB_SECONDARY_NAV_VERSION);
        $this->add_custom_css();
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

    public function add_admin_menu() {
        add_options_page(
            'SB Secondary Navigation Settings',
            'SB Secondary Nav',
            'manage_options',
            'sb-secondary-navigation',
            array($this, 'display_settings_page')
        );
    }

    public function register_settings() {
        register_setting('sb_secondary_nav_options', 'sb_secondary_nav_options', array($this, 'sanitize_options'));

        add_settings_section(
            'sb_secondary_nav_main_section',
            'Appearance Settings',
            null,
            'sb-secondary-navigation'
        );

        $fields = array(
            'bg_color' => 'Background Color',
            'text_color' => 'Text Color',
            'border_color' => 'Border Color',
            'hover_bg_color' => 'Hover Background Color',
            'hover_text_color' => 'Hover Text Color',
            'submenu_bg_color' => 'Submenu Background Color',
            'font_size' => 'Font Size',
            'font_family' => 'Font Family'
        );

        foreach ($fields as $field => $label) {
            add_settings_field(
                'sb_secondary_nav_' . $field,
                $label,
                array($this, 'render_field'),
                'sb-secondary-navigation',
                'sb_secondary_nav_main_section',
                array('field' => $field)
            );
        }
    }

    public function sanitize_options($input) {
        $new_input = array();
        $fields = array('bg_color', 'text_color', 'border_color', 'hover_bg_color', 'hover_text_color', 'submenu_bg_color', 'font_size', 'font_family');

        foreach ($fields as $field) {
            if (isset($input[$field])) {
                if (in_array($field, array('bg_color', 'text_color', 'border_color', 'hover_bg_color', 'hover_text_color', 'submenu_bg_color'))) {
                    $new_input[$field] = sanitize_hex_color($input[$field]);
                } else {
                    $new_input[$field] = sanitize_text_field($input[$field]);
                }
            }
        }

        return $new_input;
    }

    public function render_field($args) {
        $field = $args['field'];
        $value = isset($this->options[$field]) ? esc_attr($this->options[$field]) : '';
        if (strpos($field, 'color') !== false) {
            echo "<input type='color' id='sb_secondary_nav_$field' name='sb_secondary_nav_options[$field]' value='$value' />";
        } else {
            echo "<input type='text' id='sb_secondary_nav_$field' name='sb_secondary_nav_options[$field]' value='$value' />";
        }
    }

    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h1>SB Secondary Navigation Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('sb_secondary_nav_options');
                do_settings_sections('sb-secondary-navigation');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function add_custom_css() {
        $custom_css = "
            .secondary-navigation { background-color: {$this->options['bg_color']}; }
            .secondary-menu a { color: {$this->options['text_color']}; border-color: {$this->options['border_color']}; font-size: {$this->options['font_size']}; font-family: {$this->options['font_family']}; }
            .secondary-menu > li > a:hover,
            .secondary-menu > li:focus-within > a {
                background-color: {$this->options['hover_bg_color']};
                color: {$this->options['hover_text_color']};
            }
            .secondary-menu ul { background-color: {$this->options['submenu_bg_color']}; }
        ";
        wp_add_inline_style('sb-secondary-navigation', $custom_css);
    }

    public function add_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=sb-secondary-navigation">' . __('Settings') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}
