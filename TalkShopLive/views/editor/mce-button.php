<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


    /** @var \TalkShopLive\Core\View $this */
?>
<?php if (!empty($data)) : ?>
    <script type="text/javascript">
        const talkShopLiveEditorButton = <?php echo \json_encode($data); ?>;
    </script>
<?php endif; ?>

<style>
    i.mce-i-talkshoplive-editor-ico:before {
        /*content: "\f126";*/
    }
    .mce-btn i.mce-i-talkshoplive-editor-ico {
        background-image: url("<?php echo esc_url( \TalkShopLive\Controllers\ViewController::getAssetsViewUrl('images/tsl-icon.svg') ) ?>");
        background-size: contain;
        background-repeat: no-repeat;
    }
</style>
