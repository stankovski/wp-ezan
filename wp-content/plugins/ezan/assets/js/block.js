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
            }
        },
        
        // Block edit function
        edit: function(props) {
            var title = props.attributes.title;
            
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
                        })
                    )
                ),
                
                // Block preview
                el('div', { className: props.className },
                    el('div', { className: 'ezan-block-editor' },
                        el('div', { className: 'ezan-icon' },
                            el('span', { className: 'dashicons dashicons-clock' })
                        ),
                        el('h3', {}, title),
                        el('div', { className: 'ezan-prayer-times-preview' },
                            el('div', { className: 'ezan-prayer-time' },
                                el('span', { className: 'ezan-prayer-name' }, __('Fajr:', 'ezan')),
                                el('span', { className: 'ezan-prayer-time-value' }, '05:30')
                            ),
                            el('div', { className: 'ezan-prayer-time' },
                                el('span', { className: 'ezan-prayer-name' }, __('Dhuhr:', 'ezan')),
                                el('span', { className: 'ezan-prayer-time-value' }, '12:45')
                            ),
                            el('div', { className: 'ezan-prayer-time' },
                                el('span', { className: 'ezan-prayer-name' }, __('Asr:', 'ezan')),
                                el('span', { className: 'ezan-prayer-time-value' }, '16:15')
                            ),
                            el('div', { className: 'ezan-prayer-time' },
                                el('span', { className: 'ezan-prayer-name' }, __('Maghrib:', 'ezan')),
                                el('span', { className: 'ezan-prayer-time-value' }, '19:30')
                            ),
                            el('div', { className: 'ezan-prayer-time' },
                                el('span', { className: 'ezan-prayer-name' }, __('Isha:', 'ezan')),
                                el('span', { className: 'ezan-prayer-time-value' }, '21:00')
                            )
                        ),
                        el('p', { className: 'ezan-preview-note' }, 
                            __('This is a preview. Actual prayer times will be loaded from ezan.io when the page is viewed.', 'ezan')
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
