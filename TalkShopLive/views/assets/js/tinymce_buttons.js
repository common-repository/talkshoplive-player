(function () {
    tinymce.PluginManager.add(talkShopLiveEditorButton.button_id, function (editor, url) {
        editor.addButton(talkShopLiveEditorButton.button_id, {
            // text: talkShopLiveEditorButton.button_title,
            icon: 'talkshoplive-editor-ico',
            tooltip: 'Insert TalkShopLive Player Shortcode',
            onclick: function () {
                editor.windowManager.open({
                    title: talkShopLiveEditorButton.button_title,
                    body: [
                        {
                            type: 'textbox',
                            name: 'tsl_url',
                            label: talkShopLiveEditorButton.url,
                            value: '',
                            classes: 'tls-url',
                        },
                        {
                            type: 'listbox',
                            name: 'tsl_embed_view',
                            label: talkShopLiveEditorButton.embed_view,
                            values: talkShopLiveEditorButton.embed_view_options,
                            value: 'products' // Sets the default
                        },
                        {
                            type: 'listbox',
                            name: 'tsl_embed_theme_style',
                            label: talkShopLiveEditorButton.embed_theme_style,
                            values: talkShopLiveEditorButton.embed_theme_style_options,
                            value: 'light', // Sets the default
                        },
                        {
                            type: 'checkbox',
                            name: 'tsl_can_include_border',
                            label: 'Include Border',
                            // checked: true
                        },
                        {
                            type: 'checkbox',
                            name: 'tsl_is_round_border',
                            label: 'Rounded Border Corners',
                        },
                        {
                            type: 'textbox',
                            name: 'tsl_border_color',
                            label: 'Border Color (hex)',
                            value: '#fff',
                        }
                          
                    ],
                    onsubmit: function (e) {
                        if (!e.data.tsl_url) {
                            tinymce.activeEditor.windowManager.alert('TalkShopLive Url field is empty. Please provide TalkShopLive url.');
                            return false
                        }
                        const hexPattern = new RegExp('^#([a-fA-F0-9]){3}$|[a-fA-F0-9]{6}$');
                        if (!hexPattern.test(e.data.tsl_border_color)) {
                            tinymce.activeEditor.windowManager.alert('Border color is invalid. It should be a hex color for example #ccc. Please provide a valid border color.');
                            return false;
                        }

                        const includeBorder = e.data.tsl_can_include_border ? '1' : '0'
                        const roundedBorder = e.data.tsl_is_round_border ? '1' : '0'

                        editor.insertContent('[talkshoplive url="' + e.data.tsl_url +'" data-view="' + e.data.tsl_embed_view + '" data-style-theme="' + e.data.tsl_embed_theme_style + '" data-style-border-width="' + includeBorder + '" data-style-border-radius="' + roundedBorder + '" data-style-border-color="' + e.data.tsl_border_color + '" /]');
                    }
                });
            },
        });
    });
})();

