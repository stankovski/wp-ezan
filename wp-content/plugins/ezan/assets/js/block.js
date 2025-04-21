(function(blocks, element, blockEditor, components, i18n) {
    var el = element.createElement;
    var __ = i18n.__;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    
    // Register block
    blocks.registerBlockType('ezan/prayer-times', {
        title: __('Prayer Times', 'ezan'),
        icon: 'clock', // Using the clock icon from WordPress Dashicons
        category: 'widgets',
        attributes: {
            title: {
                type: 'string',
                default: __('Prayer Times', 'ezan')
            },
            mosqueId: {
                type: 'string',
                default: ''
            }
        },
        
        // Block edit function
        edit: function(props) {
            var title = props.attributes.title;
            // Use the attribute value if set, otherwise use the global setting
            var mosqueId = props.attributes.mosqueId || (window.ezanVars ? ezanVars.mosque_id : '');
            
            // If we just loaded and have a global but no attribute, set it
            if (!props.attributes.mosqueId && window.ezanVars && ezanVars.mosque_id) {
                props.setAttributes({ mosqueId: ezanVars.mosque_id });
            }
            
            // Get colors and dimensions from global ezan vars or use defaults
            var bgColor = (window.ezanVars && ezanVars.bg_color) ? ezanVars.bg_color : 'FFFFFF';
            var fgColor = (window.ezanVars && ezanVars.fg_color) ? ezanVars.fg_color : '000000';
            var otherColor = (window.ezanVars && ezanVars.other_color) ? ezanVars.other_color : 'EEEEEE';
            var width = (window.ezanVars && ezanVars.width) ? ezanVars.width : '250px';
            var height = (window.ezanVars && ezanVars.height) ? ezanVars.height : '260px';
            
            // Create iframe src
            var iframeSrc = mosqueId ? 
                'https://ezan.io/ezantime/?loc=' + mosqueId + 
                '&bc=' + bgColor + 
                '&fc=' + fgColor + 
                '&oc=' + otherColor : '';
            
            return [
                // Block inspector controls
                el(InspectorControls, { key: 'inspector' },
                    el(PanelBody, { title: __('Settings', 'ezan') },
                        el(components.TextControl, {
                            label: __('Title', 'ezan'),
                            value: title,
                            onChange: function(newTitle) {
                                props.setAttributes({ title: newTitle });
                            }
                        }),
                        el(components.TextControl, {
                            label: __('Mosque ID', 'ezan'),
                            value: mosqueId,
                            onChange: function(newMosqueId) {
                                props.setAttributes({ mosqueId: newMosqueId });
                            }
                        })
                    )
                ),
                
                // Block preview
                el('div', { className: props.className },
                    el('div', { className: 'ezan-block-editor' },
                        el('h3', {}, title),
                        mosqueId ? 
                            el('iframe', {
                                src: iframeSrc,
                                width: width,
                                height: height,
                                frameBorder: '0',
                                scrolling: 'no'
                            }) :
                            el('div', { className: 'ezan-prayer-times-preview' },
                                el('p', { className: 'ezan-preview-note' }, 
                                    __('Please enter a Mosque ID in the block settings or configure it in the Ezan settings.', 'ezan')
                                )
                            )
                    )
                )
            ];
        },
        
        // Block save function returns null because we're using a dynamic render_callback
        save: function() {
            return null;
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n
);
