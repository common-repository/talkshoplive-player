<?php

namespace TalkShopLive\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Page
{
    protected $page_title = '';
    protected $menu_title = '';
    protected $capability = 'manage_options';
    protected $menu_slug = '';
    protected $function = null;
    protected $icon_url = '';
    protected $position = null;
    protected $parent_slug = '';
    protected $jsPaths = [];

    protected $cssPaths = [];

    /**
     * @var View
     */
    protected $view;

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->page_title;
    }

    /**
     * @param string $page_title
     * @return Page
     */
    public function setPageTitle(string $page_title): Page
    {
        $this->page_title = $page_title;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuTitle(): string
    {
        return $this->menu_title;
    }

    /**
     * @param string $menu_title
     * @return Page
     */
    public function setMenuTitle(string $menu_title): Page
    {
        $this->menu_title = $menu_title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCapability(): string
    {
        return $this->capability;
    }

    /**
     * @param string $capability
     * @return Page
     */
    public function setCapability(string $capability): Page
    {
        $this->capability = $capability;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuSlug(): string
    {
        return $this->menu_slug;
    }

    /**
     * @param string $menu_slug
     * @return Page
     */
    public function setMenuSlug(string $menu_slug): Page
    {
        $this->menu_slug = $menu_slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param $function
     * @return Page
     */
    public function setFunction($function): Page
    {
        $this->function = $function;
        return $this;
    }

    /**
     * @return string
     */
    public function getIconUrl(): string
    {
        return $this->icon_url;
    }

    /**
     * @param string $icon_url
     * @return Page
     */
    public function setIconUrl(string $icon_url): Page
    {
        $this->icon_url = $icon_url;
        return $this;
    }

    /**
     * @return null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param null $position
     * @return Page
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentSlug(): string
    {
        return $this->parent_slug;
    }

    /**
     * @param string $parent_slug
     * @return Page
     */
    public function setParentSlug(string $parent_slug): Page
    {
        $this->parent_slug = $parent_slug;
        return $this;
    }

    /**
     * @param string $pathName Enqueue js script giving by the pathName which is relative to TalkShopLive/views
     */
    public function addJs(string $pathName): Page
    {
        $this->jsPaths[] = $pathName;

        return $this;
    }


    /**
     * @param string $pathName Enqueue js script giving by the pathName which is relative to TalkShopLive/views
     */
    public function addCss(string $pathName): Page
    {
        $this->cssPaths[] = $pathName;

        return $this;
    }

    /**
     * @return ?View
     */
    public function getView(): ?View
    {
        return $this->view;
    }

    /**
     * @param View $view
     * @return Page
     */
    public function setView(View $view): Page
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return array
     */
    public function getJsPaths(): array
    {
        return $this->jsPaths;
    }

    /**
     * @return array
     */
    public function getCssPaths(): array
    {
        return $this->cssPaths;
    }




}