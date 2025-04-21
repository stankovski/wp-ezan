<?php
/**
 * Plugin Name: Ezan
 * Description: A WordPress plugin to display prayer times.
 * Version: 1.0
 * Author: Your Name
 */

// Activation hook
register_activation_hook(__FILE__, 'ezan_activate');
function ezan_activate() {
    // Set default settings
    if (!get_option('ezan_mosque_id')) {
        add_option('ezan_mosque_id', '');
    }
    
    // Set default display settings
    if (!get_option('ezan_bg_color')) {
        add_option('ezan_bg_color', 'FFFFFF');
    }
    
    if (!get_option('ezan_fg_color')) {
        add_option('ezan_fg_color', '000000');
    }
    
    if (!get_option('ezan_other_color')) {
        add_option('ezan_other_color', 'EEEEEE');
    }
    
    if (!get_option('ezan_width')) {
        add_option('ezan_width', '250px');
    }
    
    if (!get_option('ezan_height')) {
        add_option('ezan_height', '260px');
    }
}

// Shortcode to display prayer times
add_shortcode('ezan-prayer-times', 'ezan_prayer_times_shortcode');
function ezan_prayer_times_shortcode($atts) {
    $atts = shortcode_atts(array(
        'mosque_id' => get_option('ezan_mosque_id', ''),
        'bg_color' => get_option('ezan_bg_color', 'FFFFFF'),
        'fg_color' => get_option('ezan_fg_color', '000000'),
        'other_color' => get_option('ezan_other_color', 'EEEEEE'),
        'width' => get_option('ezan_width', '250px'),
        'height' => get_option('ezan_height', '260px'),
    ), $atts);
    
    ob_start();
    ?>
    <div class="ezan-prayer-times" data-mosque-id="<?php echo esc_attr($atts['mosque_id']); ?>" style="width: <?php echo esc_attr($atts['width']); ?>; height: <?php echo esc_attr($atts['height']); ?>;">
        <p class="ezan-error">Loading...</p>
    </div>
    <script>
    (function($) {
        'use strict';
        
        $(document).ready(function() {
            $('.ezan-prayer-times').each(function() {
                var container = $(this);
                var mosqueId = container.data('mosque-id');
                
                if (!mosqueId) {
                    container.html('<p class="ezan-error">Mosque ID not configured</p>');
                    return;
                }
                
                // Create iframe from ezan.io
                var iframe = '<iframe loading="lazy" src="https://ezan.io/ezantime/?loc=' + mosqueId + '&amp;bc=<?php echo esc_js(get_option('ezan_bg_color', 'FFFFFF')); ?>&amp;fc=<?php echo esc_js(get_option('ezan_fg_color', '000000')); ?>&amp;oc=<?php echo esc_js(get_option('ezan_other_color', 'EEEEEE')); ?>" width="<?php echo esc_js(get_option('ezan_width', '250px')); ?>" height="<?php echo esc_js(get_option('ezan_height', '260px')); ?>" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>';
                
                container.html(iframe);
            });
        });
        
    })(jQuery);
    </script>
    <?php
    return ob_get_clean();
}

// Uninstall hook
register_uninstall_hook(__FILE__, 'ezan_uninstall');
function ezan_uninstall() {
    // Delete plugin settings
    delete_option('ezan_mosque_id');
    delete_option('ezan_bg_color');
    delete_option('ezan_fg_color');
    delete_option('ezan_other_color');
    delete_option('ezan_width');
    delete_option('ezan_height');
}
?>