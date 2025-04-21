<?php
/**
 * Ezan Block Registration
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register block assets
add_action('init', 'ezan_register_block_assets');
function ezan_register_block_assets() {
    // Register block editor script
    wp_register_script(
        'ezan-block-editor',
        EZAN_PLUGIN_URL . 'assets/js/block.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        filemtime(EZAN_PLUGIN_DIR . 'assets/js/block.js')
    );

    // Register block editor style
    wp_register_style(
        'ezan-block-editor-style',
        EZAN_PLUGIN_URL . 'assets/css/editor.css',
        array('wp-edit-blocks'),
        filemtime(EZAN_PLUGIN_DIR . 'assets/css/editor.css')
    );

    // Register front-end style
    wp_register_style(
        'ezan-block-style',
        EZAN_PLUGIN_URL . 'assets/css/style.css',
        array(),
        filemtime(EZAN_PLUGIN_DIR . 'assets/css/style.css')
    );

    // Register the block
    register_block_type('ezan/prayer-times', array(
        'editor_script' => 'ezan-block-editor',
        'editor_style' => 'ezan-block-editor-style',
        'style' => 'ezan-block-style',
        'render_callback' => 'ezan_render_prayer_times_block'
    ));
}

// Block render callback
function ezan_render_prayer_times_block($attributes) {
    $mosque_id = get_option('ezan_mosque_id');
    
    if (empty($mosque_id)) {
        return '<div class="ezan-error">' . esc_html__('Please configure Mosque ID in Ezan settings.', 'ezan') . '</div>';
    }
    
    // Get custom attributes
    $className = isset($attributes['className']) ? $attributes['className'] : '';
    
    // Start output buffering
    ob_start();
    ?>
    <div class="wp-block-ezan-prayer-times <?php echo esc_attr($className); ?>">
        <div class="ezan-prayer-times" data-mosque-id="<?php echo esc_attr($mosque_id); ?>">
            <div class="ezan-loading">
                <?php esc_html_e('Loading prayer times...', 'ezan'); ?>
            </div>
        </div>
    </div>
    <?php
    
    // Enqueue frontend script
    wp_enqueue_script(
        'ezan-frontend',
        EZAN_PLUGIN_URL . 'assets/js/frontend.js',
        array('jquery'),
        filemtime(EZAN_PLUGIN_DIR . 'assets/js/frontend.js'),
        true
    );
    
    // Get settings for the iframe
    $bg_color = get_option('ezan_bg_color', 'FFFFFF');
    $fg_color = get_option('ezan_fg_color', '000000');
    $other_color = get_option('ezan_other_color', 'EEEEEE');
    $width = get_option('ezan_width', '250px');
    $height = get_option('ezan_height', '260px');
    
    wp_localize_script('ezan-frontend', 'ezanVars', array(
        'mosque_id' => $mosque_id,
        'api_url' => 'https://ezan.io/api/',
        'bg_color' => $bg_color,
        'fg_color' => $fg_color,
        'other_color' => $other_color,
        'width' => $width,
        'height' => $height
    ));
    
    return ob_get_clean();
}
?>
