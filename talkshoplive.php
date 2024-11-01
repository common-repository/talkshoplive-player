<?php
/**
 * Plugin Name:       TalkShopLive Player
 * Plugin URI:        https://embed.talkshop.live/#wordpress
 * Description:       The plugin allows you to embed your live shows and checkout on WordPress platform to extend your selling potential and drive partnership sales. Potential buyers never leave the WordPress site hosting the embed. Checkout is completely contained within the embed allowing you and/or your partners to reduce bounce.
 * Version:           1.0.3
 * Requires at least: 4.7
 * Requires PHP:      7.1
 * Author:            TalkShopLive
 * Author URI:        https://talkshop.live
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       talkshoplive-player
 * Domain Path:       /public/lang
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


$version = (float)PHP_VERSION;
$requiredVersion = 7.1;
if($version < $requiredVersion) {
    add_action('admin_notices', static function() use ($requiredVersion, $version) {
        $html = '<div id="message" class="error notice is-dismissible">';
        $html .= '<p>';
        $html .= __("Talk Shop Live Player requires at least php version: {$requiredVersion}. Detected version: {$version} <b>The plugin has been disabled.</b>", 'talkshoplive-player');
        $html .= '</p>';
        $html .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
        $html .= '</div><!-- /.updated -->';
        echo wp_kses_post(wpautop($html));
        deactivate_plugins( __FILE__, true);
    });
}

require(__DIR__ . DIRECTORY_SEPARATOR . 'autoload.php');

add_action('plugins_loaded', function(){

    $app = new \TalkShopLive\Core\App();
    $app->run();
});