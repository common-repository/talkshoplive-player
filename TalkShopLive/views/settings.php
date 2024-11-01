<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


use TalkShopLive\Core\Data\OptionKeys;
use TalkShopLive\Core\Option;
use TalkShopLive\Core\Widgets\PlayerAttributes;

$settings = new PlayerAttributes();
$settings->fromJson(Option::get(OptionKeys::PLAYER_DEFAULTS));
?>
<div class="wrap">
    <h1>TalkShopLive Player Options</h1>
    <div id="notification-banner">
        
    </div>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row">Embed View</th>
            <td>
                <select id="embed_view" name="embed_view">
                    <option <?php echo esc_attr( $settings->getDataView() === 'products' ? 'selected' : '') ?> value="products">Products</option>
                    <option <?php echo esc_attr( $settings->getDataView() === 'chat' ? 'selected' : '' ) ?> value="chat">Chat</option>
                    <option <?php echo esc_attr( $settings->getDataView() === 'default' ? 'selected' : '') ?> value="default">Condensed</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">Include Border</th>
            <td>
                <input <?php echo esc_attr( $settings->getDataStyleBorderWidth() ? 'checked' : '' ) ?> type="checkbox" id="border_width" name="border_width" />
            </td>
        </tr>
        <tr>
            <th scope="row">Rounded Border Corners</th>
            <td>
                <input <?php echo esc_attr( $settings->getDataStyleBorderRadius() ? 'checked' : '' ) ?> type="checkbox" id="border_radius" name="border_radius" />
            </td>
        </tr>
        <tr>
            <th scope="row">Border Color</th>
            <td>
                <input class="widefat color-picker" id="border_color"  name="border_color" type="text" value="<?php echo esc_attr( $settings->getDataStyleBorderColor() ?: '#ffffff' ) ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">Embed Theme Style</th>
            <td>
                <select id="style_theme" name="style_theme">
                    <option <?php echo esc_attr( $settings->getDataStyleTheme() === 'light' ? 'selected' : '' ) ?> value="light">Light</option>
                    <option <?php echo esc_attr( $settings->getDataStyleTheme() === 'dark' ? 'selected' : '' ) ?> value="dark">Dark</option>
                </select>
            </td>
        </tr>
    </table>
    <p class="submit" style="display:flex;"><button type="button" onclick="submitValues()" name="submit" id="submit" class="button button-primary" value="Save Changes">Save Changes</button><span class="spinner"></span></p>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        const tslColorPickerOptions = {
            // you can declare a default color here,
            // or in the data-default-color attribute on the input
            defaultColor: '#ffffff',
            // a callback to fire whenever the color changes to a valid color
            change: function(event, ui){},
            // a callback to fire when the input is emptied or an invalid color
            clear: function() {},
            // hide the color picker controls on load
            hide: true,
            // show a group of common colors beneath the square
            // or, supply an array of colors to customize further
            palettes: true
        };


        let tslColorPicker = $('.color-picker');
        let parent = tslColorPicker.parent();
        tslColorPicker.wpColorPicker(tslColorPickerOptions);
    });

    const notificationBanner = document.getElementById('notification-banner');
    
    function clearNotification() {
        notificationBanner.innerHTML= ''
    }

    function submitValues() {

        const embed_view = document.getElementById('embed_view').value;
        const border_width = document.getElementById('border_width').checked;
        const border_radius = document.getElementById('border_radius').checked;
        const border_color = document.getElementById('border_color').value;
        const style_theme = document.getElementById('style_theme').value;
       
        const spinner = jQuery('.spinner');
        spinner.addClass('is-active')
        jQuery.post('<?php echo esc_url(get_rest_url() . "talkshoplive/v1/settings"); ?>', {
                embed_view,
                border_radius,
                border_width,
                border_color,
                style_theme
            },
            function() {
                
                    notificationBanner.innerHTML = `
                <div class="notice notice-success settings-error is-dismissible">
                <p>
                <strong>Options saved.</strong></p>
                <button type="button" onClick="clearNotification()" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
             `            
                                
            }).
            fail(function() {
                notificationBanner.innerHTML = `
                <div class="notice notice-error settings-error is-dismissible">
                <p>
                <strong>There was an error saving options.</strong></p>
                <button type="button" onClick="clearNotification()" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
                `
            })
            .always(function() {
                spinner.removeClass('is-active')
            })
    }
</script>