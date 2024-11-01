<?php

namespace TalkShopLive\Core;

use TalkShopLive\Controllers\ViewController;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class GutenbergHandler {

    /**
     * @var EmbedHandler
     */
    protected $embedHandler;

    public function __construct(){
        $this->embedHandler = new EmbedHandler(new View('embed'));
        $this->registerEndpoints();
    }

    public function register()
    {
        if (!function_exists('register_block_type')) {
            // Gutenberg is not active.
            return;
        }
        $handle = 'tsl-gutenberg-block-main';

        add_action( 'block_categories', function ($categories) {
            $slugs = wp_list_pluck($categories, 'slug');

            if (!in_array('talkshoplive', $slugs, true)){
                $categories[] = [
                    'slug' => 'talkshoplive',
                    'title' => 'TalkShopLive',
                    'icon' => ''
                ];
            }

            return $categories;
        } );

        global $wp_version;
        $currentVersion = (float)$wp_version;
        $source = ViewController::getAssetsViewUrl('js/blocks/build/tsl.player.block.build.js');

        if($currentVersion >= 5.8){
            $source = ViewController::getAssetsViewUrl('js/blocks/build/tsl.player.block.latest.build.js');
        }

        $dependencies = [
            'wp-element',
            'wp-blocks',
            'wp-editor',
            'wp-components',
            'wp-dom-ready',
            'wp-edit-post',
        ];
        wp_register_script(
            $handle,
            $source,
            $dependencies,
            filemtime(ViewController::getAssetsViewPath('js/blocks/build/tsl.player.block.build.js'))

        );

        register_block_type('talkshoplive/player', [
            'editor_script' => $handle,
            'render_callback' => function( $attribs, $content ) {
                return $content;

            }
        ]);
    }

    public function registerEndpoints(){
        \add_action('rest_api_init', function () {
            register_rest_route('talkshoplive/v1', '/embed',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'getEmbed'],

                    'permissions_callback' => array($this, 'getPermissions')
                )
            );
        });
    }

    public function getEmbed(\WP_REST_Request $request): \WP_REST_Response
    {
        $requestData = json_decode($request->get_body());

        $embedData = '';
        if (!empty($requestData->url)) {
            $match = $this->embedHandler->matchUrl($requestData->url);

            $embedData = $this->embedHandler->getContent($match);
        }

        $response = new \WP_REST_Response(['data' => $embedData], 200);

        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store' ]);

        return $response;
    }

    public function getPermissions(){

    }

}