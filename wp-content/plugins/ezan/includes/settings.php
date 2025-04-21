<?php
/**
 * Ezan Settings Page
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu
add_action('admin_menu', 'ezan_add_admin_menu');
function ezan_add_admin_menu() {
    add_options_page(
        esc_html__('Ezan Settings', 'ezan'),
        esc_html__('Ezan', 'ezan'),
        'manage_options',
        'ezan-settings',
        'ezan_settings_page'
    );
}

// Register settings
add_action('admin_init', 'ezan_register_settings');
function ezan_register_settings() {
    // Main settings
    register_setting('ezan_settings_group', 'ezan_mosque_id', 'sanitize_text_field');
    
    // Display settings
    register_setting('ezan_settings_group', 'ezan_bg_color', 'ezan_sanitize_color');
    register_setting('ezan_settings_group', 'ezan_fg_color', 'ezan_sanitize_color');
    register_setting('ezan_settings_group', 'ezan_other_color', 'ezan_sanitize_color');
    register_setting('ezan_settings_group', 'ezan_width', 'ezan_sanitize_dimension');
    register_setting('ezan_settings_group', 'ezan_height', 'ezan_sanitize_dimension');
    
    // Main section
    add_settings_section(
        'ezan_main_section',
        esc_html__('Ezan Integration Settings', 'ezan'),
        'ezan_main_section_callback',
        'ezan-settings'
    );
    
    // Display section
    add_settings_section(
        'ezan_display_section',
        esc_html__('Display Settings', 'ezan'),
        'ezan_display_section_callback',
        'ezan-settings'
    );
    
    // Mosque ID field
    add_settings_field(
        'ezan_mosque_id',
        esc_html__('Mosque ID', 'ezan'),
        'ezan_mosque_id_callback',
        'ezan-settings',
        'ezan_main_section'
    );
    
    // Color fields
    add_settings_field(
        'ezan_bg_color',
        esc_html__('Background Color', 'ezan'),
        'ezan_bg_color_callback',
        'ezan-settings',
        'ezan_display_section'
    );
    
    add_settings_field(
        'ezan_fg_color',
        esc_html__('Foreground Color', 'ezan'),
        'ezan_fg_color_callback',
        'ezan-settings',
        'ezan_display_section'
    );
    
    add_settings_field(
        'ezan_other_color',
        esc_html__('Other Color', 'ezan'),
        'ezan_other_color_callback',
        'ezan-settings',
        'ezan_display_section'
    );
    
    // Dimension fields
    add_settings_field(
        'ezan_width',
        esc_html__('Width', 'ezan'),
        'ezan_width_callback',
        'ezan-settings',
        'ezan_display_section'
    );
    
    add_settings_field(
        'ezan_height',
        esc_html__('Height', 'ezan'),
        'ezan_height_callback',
        'ezan-settings',
        'ezan_display_section'
    );
}

// Section callbacks
function ezan_main_section_callback() {
    echo '<p>' . esc_html__('Configure your integration with ezan.io', 'ezan') . '</p>';
}

function ezan_display_section_callback() {
    echo '<p>' . esc_html__('Customize how the prayer times widget looks', 'ezan') . '</p>';
}

// Field callbacks
function ezan_mosque_id_callback() {
    $mosque_id = get_option('ezan_mosque_id', '');
    echo '<input type="text" name="ezan_mosque_id" value="' . esc_attr($mosque_id) . '" class="regular-text" />';
    echo '<p class="description">' . esc_html__('Enter your Mosque ID from ezan.io', 'ezan') . '</p>';
}

function ezan_bg_color_callback() {
    $bg_color = get_option('ezan_bg_color', 'FFFFFF');
    echo '<input type="text" name="ezan_bg_color" value="#' . esc_attr($bg_color) . '" class="ezan-color-field" />';
    echo '<p class="description">' . esc_html__('Background color in HEX format', 'ezan') . '</p>';
}

function ezan_fg_color_callback() {
    $fg_color = get_option('ezan_fg_color', '000000');
    echo '<input type="text" name="ezan_fg_color" value="#' . esc_attr($fg_color) . '" class="ezan-color-field" />';
    echo '<p class="description">' . esc_html__('Foreground color in HEX format', 'ezan') . '</p>';
}

function ezan_other_color_callback() {
    $other_color = get_option('ezan_other_color', 'EEEEEE');
    echo '<input type="text" name="ezan_other_color" value="#' . esc_attr($other_color) . '" class="ezan-color-field" />';
    echo '<p class="description">' . esc_html__('Other color in HEX format', 'ezan') . '</p>';
}

function ezan_width_callback() {
    $width = get_option('ezan_width', '250px');
    echo '<input type="text" name="ezan_width" value="' . esc_attr($width) . '" class="regular-text" />';
    echo '<p class="description">' . esc_html__('Width of the prayer times widget (e.g., 250px or 100%)', 'ezan') . '</p>';
}

function ezan_height_callback() {
    $height = get_option('ezan_height', '260px');
    echo '<input type="text" name="ezan_height" value="' . esc_attr($height) . '" class="regular-text" />';
    echo '<p class="description">' . esc_html__('Height of the prayer times widget (e.g., 260px)', 'ezan') . '</p>';
}

// Sanitization functions
function ezan_sanitize_color($color) {
    $color = sanitize_text_field($color);
    // Remove hash if it exists
    $color = ltrim($color, '#');
    // Only allow hex characters
    $color = preg_replace('/[^A-Fa-f0-9]/', '', $color);
    
    // Make sure we have a valid hex color
    if (!empty($color)) {
        // If only 3 characters provided, expand to 6
        if (strlen($color) === 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }
        
        // If not 6 characters, default to black or white
        if (strlen($color) !== 6) {
            $color = 'FFFFFF';
        }
    } else {
        $color = 'FFFFFF';
    }
    
    return $color;
}

function ezan_sanitize_dimension($dimension) {
    $dimension = sanitize_text_field($dimension);
    // Allow numbers followed by px, em, rem, % or no unit
    if (preg_match('/^(\d+)(px|em|rem|%)?$/', $dimension)) {
        return $dimension;
    }
    // Default to px if not valid
    return '250px';
}

// Settings page HTML
function ezan_settings_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Add color picker support
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_add_inline_script('wp-color-picker', '
        jQuery(document).ready(function($) {
            $(".ezan-color-field").wpColorPicker({
                change: function(event, ui) {
                    // Update the input field with the hex value (without #)
                    $(this).val(ui.color.toString().replace("#", ""));
                }
            });
        });
    ');
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('ezan_settings_group');
            do_settings_sections('ezan-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
?>
