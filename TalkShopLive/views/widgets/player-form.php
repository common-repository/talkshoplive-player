<?php
/** @var PlayerWidget $data */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


use TalkShopLive\Core\Widgets\PlayerWidget;
use TalkShopLive\Core\Widgets\SelectOption;

?>
<p>
    <?php echo $data->generateLabelForField('is_visible', 'Is Visible') ?>
    <?php echo $data->generateSelectForField('is_visible', [
        new SelectOption('Yes', 'true', $data->isVisible()),
        new SelectOption('No', 'false', !$data->isVisible()),
    ]); ?>
</p>
<p>
    <?php echo $data->generateLabelForField('url', 'TalkShopLive Url') ?>
    <?php echo $data->generateInputForField('url', $data->getUrl() ); ?>
</p>
<h4>Header Settings</h4>
<hr/>
<p>
    <?php echo $data->generateLabelForField('header', 'Section Header') ?>
    <?php echo $data->generateInputForField('header', $data->getHeader()); ?>
</p>
<p>
    <?php echo $data->generateLabelForField('header_text_style', 'Section Header Text Style') ?>
    <?php echo $data->generateSelectForField('header_text_style', [
        new SelectOption('Normal', PlayerWidget::HEADER_STYLE_NORMAL, $data->getHeaderTextStyle() === PlayerWidget::HEADER_STYLE_NORMAL),
        new SelectOption('H1', PlayerWidget::HEADER_STYLE_H1, $data->getHeaderTextStyle() === PlayerWidget::HEADER_STYLE_H1),
        new SelectOption('H2', PlayerWidget::HEADER_STYLE_H2, $data->getHeaderTextStyle() === PlayerWidget::HEADER_STYLE_H2),
        new SelectOption('H3', PlayerWidget::HEADER_STYLE_H3, $data->getHeaderTextStyle() === PlayerWidget::HEADER_STYLE_H3),
        new SelectOption('H4', PlayerWidget::HEADER_STYLE_H4, $data->getHeaderTextStyle() === PlayerWidget::HEADER_STYLE_H4),
        new SelectOption('H5', PlayerWidget::HEADER_STYLE_H5, $data->getHeaderTextStyle() === PlayerWidget::HEADER_STYLE_H5),

    ]); ?>
</p>
<p>
    <?php echo $data->generateLabelForField('header_align_style', 'Section Header Align') ?>
    <?php echo $data->generateSelectForField('header_align_style', [
        new SelectOption('Center', PlayerWidget::HEADER_STYLE_ALIGN_CENTER, $data->getHeaderAlignStyle() === PlayerWidget::HEADER_STYLE_ALIGN_CENTER),
        new SelectOption('Left', PlayerWidget::HEADER_STYLE_ALIGN_LEFT, $data->getHeaderAlignStyle() === PlayerWidget::HEADER_STYLE_ALIGN_LEFT),
        new SelectOption('Right', PlayerWidget::HEADER_STYLE_ALIGN_RIGHT, $data->getHeaderAlignStyle() === PlayerWidget::HEADER_STYLE_ALIGN_RIGHT),
    ]); ?>
</p>
<h4>Embed Settings</h4>
<hr/>
<p>
    <?php echo $data->generateLabelForField('embed_view', 'Embed View') ?>
    <?php echo $data->generateSelectForField('embed_view', [
        new SelectOption('Products', PlayerWidget::EMBED_VIEW_PRODUCTS, PlayerWidget::EMBED_VIEW_PRODUCTS === $data->getEmbedView()),
        new SelectOption('Chat', PlayerWidget::EMBED_VIEW_CHAT, PlayerWidget::EMBED_VIEW_CHAT === $data->getEmbedView()),
        new SelectOption('Condensed', PlayerWidget::EMBED_VIEW_DEFAULT, PlayerWidget::EMBED_VIEW_DEFAULT === $data->getEmbedView()),

    ]); ?>
</p>
<p>
    <?php echo $data->generateCheckboxForField('can_include_border', $data->canIncludeBorder() ) ?>
    <?php echo $data->generateLabelForField('can_include_border', 'Include border') ?>
</p>
<p>
    <?php echo $data->generateCheckboxForField('can_include_round_border',  $data->canIncludeRoundBorder() ) ?>
    <?php echo $data->generateLabelForField('can_include_round_border', 'Rounded border corners') ?>
</p>
<p>
    <div id="tslColorPickerContainer">
        <?php echo $data->generateLabelForField('border_color', 'Border Color') ?>
        <input  id="<?php echo esc_attr( $data->get_field_id( 'border_color' ) ); ?>"  name="<?php echo $data->get_field_name( 'border_color' ); ?>" type="hidden" value="<?php echo esc_attr( $data->getBorderColor() ?: '#fff' ) ?>" />
        <input class="widefat tsl-color-picker"  type="text" value="<?php echo esc_attr( $data->getBorderColor() ?: '#fff' ) ?>" />
    </div>
</p>
<p>
    <?php echo $data->generateSelectForField('theme_style', [
        new SelectOption('Light', PlayerWidget::THEME_STYLE_LIGHT, PlayerWidget::THEME_STYLE_LIGHT === $data->getThemeStyle()),
        new SelectOption('Dark', PlayerWidget::THEME_STYLE_DARK, PlayerWidget::THEME_STYLE_DARK === $data->getThemeStyle())
    ]); ?>
</p>
<br>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        const tslColorPickerOptions = {
            // you can declare a default color here,
            // or in the data-default-color attribute on the input
            defaultColor: '#fff',
            // a callback to fire whenever the color changes to a valid color
            change: function(event, ui){
              let element = $('#<?php echo esc_js($data->get_field_id('border_color')) ?>');
                element.val($(this).val());
                element.trigger('change');
            },
            // a callback to fire when the input is emptied or an invalid color
            clear: function() {},
            // hide the color picker controls on load
            hide: true,
            // show a group of common colors beneath the square
            // or, supply an array of colors to customize further
            palettes: false
        };

        $('.tsl-color-picker').wpColorPicker(tslColorPickerOptions);
    });

</script>
<style>
    #tslColorPickerContainer label {
        display: block;
    }
</style>