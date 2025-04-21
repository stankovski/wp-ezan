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
            
            // Get colors and dimensions from settings
            var bgColor = ezanVars.bg_color || 'FFFFFF';
            var fgColor = ezanVars.fg_color || '000000';
            var otherColor = ezanVars.other_color || 'EEEEEE';
            var width = ezanVars.width || '250px';
            var height = ezanVars.height || '260px';
            
            // Create iframe from ezan.io
            var iframe = '<iframe loading="lazy" src="https://ezan.io/ezantime/?loc=' + mosqueId + 
                         '&amp;bc=' + bgColor + 
                         '&amp;fc=' + fgColor + 
                         '&amp;oc=' + otherColor + 
                         '" width="' + width + '" height="' + height + 
                         '" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>';
            
            container.html(iframe);
        });
    });
    
})(jQuery);
