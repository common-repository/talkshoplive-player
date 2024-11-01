<?php

namespace TalkShopLive\Core;

use TalkShopLive\Controllers\SettingsController;
use TalkShopLive\Core\Editor\MceButton;
use TalkShopLive\Core\Widgets\PlayerWidget;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class App
{

    protected $coreJsSource = 'https://embed.talkshop.live/embed.js';

    /**
     * @var ShortcodeHandler
     */
    protected $shortcodeHandler;
    /**
     * @var MceButton
     */
    protected $buttonHandler;

    /**
     * @var
     */
    protected $gutenbergHandler;

    public function __construct()
    {
        $this->shortcodeHandler = new ShortcodeHandler();
        $this->buttonHandler = new MceButton();
        $this->gutenbergHandler = new GutenbergHandler();
    }

    public function getCoreJsSource(): string
    {
        return $this->coreJsSource;
    }

    public function registerWidgets(): void
    {
        add_action('widgets_init', function () {
            register_widget(PlayerWidget::class);
        });
    }

    public function registerShortcodes(): void
    {
        add_action('init', [$this->shortcodeHandler, 'register']);
    }

    public function registerEditorButtons(): void
    {
        add_action('init', [$this->buttonHandler, 'register']);
    }

    public function registerGutenbergBlocks(): void
    {
        add_action('init', [$this->gutenbergHandler, 'register']);
    }


    public function run(): void
    {

        $embedController = new EmbedHandler(new View('embed'));
        $embedController->registerEmbed();


        $menuSlug = 'tsl-settings';
        $menuTitle = 'TalkShopLive Player';

        $page = new Page();
        $page->setPageTitle($menuTitle);
        $page->setMenuTitle($menuTitle);
        $page->setMenuSlug($menuSlug);
        $page->setIconUrl('dashicons-controls-play');
        $page->setView(new View('settings'));
        $page->addJs('js/admin.js');

        $settingsPage = new SettingsController();
        $settingsPage->generateAdminMenu($page);

        $this->registerFooterScripts();
        $this->registerWidgets();
        $this->registerShortcodes();
        $this->registerEditorButtons();
        $this->registerGutenbergBlocks();
    }

    public function registerFooterScripts(): void
    {
        \add_action('admin_footer', function () {
            echo \wp_kses($this->getScriptTag(), [ 'script' => [
                'src' => [],
                'async' => [],
                'crossorigin' => []
            ]]);
        });
    }

    public function getScriptTag(): string
    {
        return sprintf('<script async crossorigin="anonymous" src="%s"></script>',
            esc_url($this->getCoreJsSource())
        );
    }

}