<?php

namespace TalkShopLive\Core;

use TalkShopLive\Core\Data\IView;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class View implements IView {

    protected $viewPath;
    protected $viewAssetsPath;
    protected $_prefix = 'tsl';
    protected $viewName = '';

    public function __construct($viewName)
    {
        $this->viewName = $viewName;
        $this->viewPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views';
        $this->viewAssetsPath = plugin_dir_url(__DIR__) . 'views' . DIRECTORY_SEPARATOR . 'assets';
    }

    public function render($data = null, $canRequireOnce = false): void
    {
        $view = str_replace(['.php', '.'], ['', DIRECTORY_SEPARATOR], $this->viewName);

        if (file_exists($this->viewPath . DIRECTORY_SEPARATOR . $view . '.php')) {
            $filePath = $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';

            if ($canRequireOnce){
                require_once $filePath;
            } else {
                require $filePath;
            }
        }
    }

}