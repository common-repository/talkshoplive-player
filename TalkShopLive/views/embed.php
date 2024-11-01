<?php
/**
 * @see \TalkShopLive\Core\EmbedHandler::getContent()
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


use TalkShopLive\Core\Data\OptionKeys;
use TalkShopLive\Core\Option;
use TalkShopLive\Core\Widgets\PlayerAttributes;

$handleId = '';
$type = '';
$eventId = '';

$settings = new PlayerAttributes();
$playerDefaultSettings = (string)Option::get(OptionKeys::PLAYER_DEFAULTS);
$settings->fromJson($playerDefaultSettings);

if (!empty($data)) {
    $handleId =  array_key_exists('handle_id', $data) ? $data['handle_id'] : '';
    $type =  array_key_exists('type', $data) ? $data['type'] : '';

    if (!empty($handleId)) {
        $pieces = explode('/', $handleId);
        $handleId = $pieces[0];
        $eventId =  $pieces[1]  ?? '';
        $eventId = esc_html(esc_attr($eventId));
    }

    if (strtolower($type) === 'channels') {
        $type = 'channel';
    } else {
        $type = 'show';
    }

    if (!empty($data['settings'])) {
        $settings = new PlayerAttributes();
        $settings->fromQueryString($data['settings']);
    }

}
?>
<?php if (!empty($handleId)) : ?>
<script async crossorigin="anonymous" src="https://embed.talkshop.live/embed.js"></script>
<div 
    class="tsl-container" 
    data-type="<?php echo esc_attr($type); ?>"
    data-modus="<?php echo esc_attr($handleId); ?>"
    <?php echo \wp_kses( $settings, []) ?>
    <?php echo \wp_kses( (!empty($eventId) ? "data-eventid={$eventId}" : ''), []); ?>
>
</div>
<?php endif; ?>