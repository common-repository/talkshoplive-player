<?php

namespace TalkShopLive\Core\Editor;

use TalkShopLive\Controllers\ViewController;
use TalkShopLive\Core\View;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class MceButton
{
    public const SHORTCODE_BUTTON_NAME = 'talkshoplive_player_button';
    /**
     * @var View
     */
    protected $jsView;

    public function __construct(){
        $this->jsView = new View('editor/mce-button.php');
    }

    protected function canBeAdded(): bool
    {
        return current_user_can('manage_options');
    }

    public function register(): void
    {

        if (!$this->canBeAdded()) {
            return;
        }

        add_filter('mce_external_plugins', [$this, 'addButtons']);
        add_filter('mce_buttons', [$this, 'registerButtons']);
        add_action ( 'after_wp_tiny_mce', [$this, 'addJsVariables'] );
        wp_enqueue_style('symple_shortcodes-tc', ViewController::getAssetsViewUrl('css/widget.css'));
    }

    public function addButtons($pluginData)
    {
        $pluginData[self::SHORTCODE_BUTTON_NAME] = ViewController::getAssetsViewUrl('js/tinymce_buttons.js');
        return $pluginData;
    }

    public function registerButtons(array $buttonsData): array
    {
        $buttonsData[] = self::SHORTCODE_BUTTON_NAME;
        return $buttonsData;
    }

   public function addJsVariables(): void
   {
       $data = [
           'button_title' => 'TalkShopLive Player',
           'url' => 'TalkShopLive Url',
           'embed_view' => 'Embed View',
           'embed_view_options' => [
               ['text' => 'Products', 'value' => 'products'],
               ['text' => 'Chat', 'value' => 'chat'],
               ['text' => 'Condensed', 'value' => 'default'],
           ],
           'embed_theme_style' => 'Embed Theme Style',
           'embed_theme_style_options' => [
               ['text' => 'Light', 'value' => 'light'],
               ['text' => 'Dark', 'value' => 'dark'],
           ],
           'button_id' => self::SHORTCODE_BUTTON_NAME
       ];

       $this->jsView->render($data);
   }
}