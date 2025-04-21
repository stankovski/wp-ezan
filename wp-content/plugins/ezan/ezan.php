<?php
/**
 * Plugin Name: Ezan
 * Description: Integration with ezan.io for displaying prayer times.
 * Version: 1.0.0
 * Author: WordPress Developer
 * Text Domain: ezan
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EZAN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EZAN_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once EZAN_PLUGIN_DIR . 'includes/settings.php';
require_once EZAN_PLUGIN_DIR . 'includes/block.php';

// Initialize the plugin
function ezan_init() {
    // Load text domain for translations
    load_plugin_textdomain('ezan', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ezan_init');

// Activation hook
register_activation_hook(__FILE__, 'ezan_activate');
function ezan_activate() {
    // Set default settings
    if (!get_option('ezan_mosque_id')) {
        add_option('ezan_mosque_id', '');
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'ezan_deactivate');
function ezan_deactivate() {
    // Clean up if needed
}

// Uninstall hook
register_uninstall_hook(__FILE__, 'ezan_uninstall');
function ezan_uninstall() {
    // Delete plugin settings
    delete_option('ezan_mosque_id');
}
?>
