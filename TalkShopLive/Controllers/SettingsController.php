<?php

namespace TalkShopLive\Controllers;

use TalkShopLive\Core\Data\OptionKeys;
use TalkShopLive\Core\Widgets\PlayerAttributes;
use TalkShopLive\Core\Option;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class SettingsController extends ViewController {

    public function __construct()
    {
        $this->addRoute();
        $this->setDefaultSetting();
        parent::__construct();
    }

    public function setDefaultSetting(): void
    {
        $settings = new PlayerAttributes();
        $settings->setDataType('show');
        $settings->setDataView('default');
        $settings->setDataView('light');
        $settings->setDataStyleBorderRadius('0');
        $settings->setDataStyleBorderWidth('0');

        $option = new Option(OptionKeys::PLAYER_DEFAULTS, $settings->toJson());
        $option->save();
    }

    public function addRoute(): void
    {
        \add_action('rest_api_init', function () {
            register_rest_route('talkshoplive/v1', '/settings',
                array(
                    'methods' => 'POST',
                    'callback' => [$this, 'updateSettings'],
                    'args' => [],
                    'permissions_callback' => array($this, 'getPermissions')
                )
            );

            register_rest_route('talkshoplive/v1', '/settings',
                array(
                    'methods' => 'GET',
                    'callback' => [$this, 'getSettings'],
                    'args' => [],

                    'permissions_callback' => array($this, 'getPermissions')
                )
            );
        });
    }

    public function getPermissions(): bool
    {
        return current_user_can( 'manage_options' );
    }

    public function updateSettings($request): \WP_REST_Response
    {
        try {

            $settings = new PlayerAttributes();

            $settings->setDataView($request['embed_view']);
            $settings->setDataStyleTheme( $request['style_theme']);
            $settings->setDataStyleBorderRadius($request['border_radius']);
            $settings->setDataStyleBorderWidth($request['border_width']);
            $settings->setDataStyleBorderColor($request['border_color']);
            $option = new Option(OptionKeys::PLAYER_DEFAULTS, $settings->toJson());
            $option->update();
            $message = 'Options updated.';
            $status = 200;

        } catch (Exception $e) {
            $message = $e->getMessage();
            $status = 400;
        }

        $response = new \WP_REST_Response(['message' => $message], $status);
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ]);

        return $response;
        
    }

    public function getSettings(): \WP_REST_Response
    {
        $data = [ 'foo' => 'bar' ];

        $response = new \WP_REST_Response($data, 200);

// Set headers.
        $response->set_headers([ 'Cache-Control' => 'must-revalidate, no-cache, no-store' ]);

        return $response;

    }

}