<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


try {
    spl_autoload_register(static function ($class_name) {

        // TODO let's actually make sure that it starts with TSL
        if (false === strpos($class_name, 'TalkShopLive')) {
            return;
        }

        $iterator = new DirectoryIterator(__DIR__);
        $path = $iterator->getPath() . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';


        if (file_exists($path)) {
            require_once $path;
            return;
        }

        throw new \RuntimeException("Failed to load $class_name", 001);
    });
} catch (Exception $e) {
    $message = $e->getMessage();
    add_action('admin_notices', static function() use ($message) {
        $html = '<div id="message" class="error notice is-dismissible">';
        $html .= '<p>';
        $html .= __("Failed to load classes - error: {$message}", 'talkshoplive-player');
        $html .= '</p>';
        $html .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
        $html .= '</div><!-- /.updated -->';
        echo wp_kses_post(wpautop($html));
        deactivate_plugins( __FILE__, true);
    });
}