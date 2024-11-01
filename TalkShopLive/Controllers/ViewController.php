<?php

namespace TalkShopLive\Controllers;

use TalkShopLive\Core\Page;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class ViewController {

    /**
     * @var Page
     */
    protected $page;

    protected $viewPath;
    protected $viewAssetsPath;
    protected $_prefix = 'tsl';

    /**
     * @var array can be either a single or multi-dimensional array.
     */
    protected $jsLocalizeData = [];

    /**
     * @var string qualified js variable globally available to scripts added with addJs() which contain
     * the data passed through setJsLocalizeData(array)
     *
     * @example sample usage
     * <code>
     *
     * <?php
     *
     *  $view = new ViewController();
     *  $view->setJsDataObjectName('TslData');
     *  $view->setLocalizeData([ wpAdminUrl => admin_url( 'admin-ajax.php' )])
     *
     * ?>
     * <script type="text/javascript">
     *
     *      alert( TslData.wpAdminUrl );
     *
     * </script>
     *
     * </code>
     */
    protected $jsDataObjectName = 'TalkShopLivePlayer';


    public function __construct(){
        $this->setViewPaths();
        $this->setJsLocalizeData($this->getDefaultJsData());
    }

    protected function getDefaultJsData() : array
    {
        return [
            'siteUrl' => \get_site_url(),
            'api' => [
                'nonce' => wp_create_nonce('tsl_nonce')
            ]
        ];
    }

    public function addJsLocalizeData($data): ViewController
    {
        $this->jsLocalizeData[] = $data;
        return $this;
    }

    protected function setViewPaths(){
        $this->viewPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views';
        $this->viewAssetsPath = plugin_dir_url( __DIR__ )  . 'views' . DIRECTORY_SEPARATOR . 'assets';
    }

    /**
     * @return array
     */
    public function getJsLocalizeData(): array
    {
        return $this->jsLocalizeData;
    }

    /**
     * @param array $jsLocalizeData
     * @return ViewController
     */
    public function setJsLocalizeData(array $jsLocalizeData): ViewController
    {
        $this->jsLocalizeData = $jsLocalizeData;
        return $this;
    }

    /**
     * @return string
     */
    public function getJsDataObjectName(): string
    {
        return $this->jsDataObjectName;
    }

    /**
     * @param string $jsDataObjectName
     * @return ViewController
     */
    public function setJsDataObjectName(string $jsDataObjectName): ViewController
    {
        $this->jsDataObjectName = $jsDataObjectName;
        return $this;
    }


    protected function setPage(Page $page){
        $this->page = $page;

        return $this;
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }



    /**
     * @param Page $page
     * @return ViewController
     */
    public function generateAdminMenu(Page $page): ViewController
    {
        $this->page = $page;

        if (!empty($page->getJsPaths())) {
            $paths = $page->getJsPaths();

            foreach ($paths as $path){
                $this->registerJs($path);
            }
        }

        \add_action('admin_menu',  function () use ($page) {
            $this->generateMenu($page);
        });

        return $this;
    }


    /**
     * @param Page $page
     * @return ViewController
     */
    public function generateAdminSubMenu(Page $page): ViewController
    {
        $this->page = $page;

        if (!empty($page->getJsPaths())) {
            $paths = $page->getJsPaths();

            foreach ($paths as $path){
                $this->registerJs($path);
            }
        }

        \add_action('admin_menu',  function () use ($page) {
            $this->generateSubMenu($page);
        });

        return $this;
    }

    public function generateMenu(Page $page): void
    {
        $callable = $this->getCallable($page);

        \add_menu_page($page->getPageTitle(), $page->getMenuTitle(), $page->getCapability(),$page->getMenuSlug(),$callable, $page->getIconUrl(),$page->getPosition());
    }

    public function generateSubMenu(Page $page): void
    {
        $callable = $this->getCallable($page);

        // TODO version 5 changed
        \add_submenu_page($page->getParentSlug(), $page->getPageTitle(), $page->getMenuTitle(), $page->getCapability(), $page->getMenuSlug(), $callable);
    }

    /**
     * @return callable
     */
    protected function getCallable(Page $page){
        if ($page->getView()) {
            return [
                $page->getView(),
                'render'
            ];
        }

        return null;
    }


    public function registerJs($name, $dependencies = ['jquery'])
    {
        $path = $this->viewAssetsPath;
        $path .= DIRECTORY_SEPARATOR . $name;
        $scriptName = $this->_prefix . '_'. random_int(0, 300);

        \add_action('admin_enqueue_scripts', function () use ($path, $scriptName, $dependencies) {
            wp_enqueue_script($scriptName, $path, $dependencies, '1.0');
            wp_localize_script( $scriptName, $this->getJsDataObjectName(), $this->getJsLocalizeData());
        });
    }

    public function registerCss($name, $dependencies)
    {
        $path = $this->viewAssetsPath;
        wp_register_style($name, $path . DIRECTORY_SEPARATOR . $name, $dependencies);
    }


    public static function getAssetsViewUrl($path): string
    {
        $path = ltrim($path, "\\//");

        $assetsPath = plugin_dir_url( __DIR__ )  . 'views' . DIRECTORY_SEPARATOR . 'assets';

        if (!empty($path)) {
            $assetsPath .= DIRECTORY_SEPARATOR . $path;
        }

        return $assetsPath;
    }

    public static function getAssetsViewPath($path): string
    {
        $path = ltrim($path, "\\//");

        $assetsPath = plugin_dir_path( __DIR__ )  . 'views' . DIRECTORY_SEPARATOR . 'assets';

        if (!empty($path)) {
            $assetsPath .= DIRECTORY_SEPARATOR . $path;
        }

        return $assetsPath;
    }
}