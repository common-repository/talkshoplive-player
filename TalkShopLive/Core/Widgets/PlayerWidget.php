<?php

namespace TalkShopLive\Core\Widgets;

use TalkShopLive\Controllers\ViewController;
use TalkShopLive\Core\Data\IView;
use TalkShopLive\Core\Data\OptionKeys;
use TalkShopLive\Core\EmbedHandler;
use TalkShopLive\Core\Option;
use TalkShopLive\Core\View;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class PlayerWidget extends \WP_Widget
{

    #region CONSTANTS

    public const WIDGET_ID = 'talk_shop_live_player_widget';

    public const EMBED_VIEW_DEFAULT = 1;
    public const EMBED_VIEW_CHAT = 2;
    public const EMBED_VIEW_PRODUCTS = 3;

    public const THEME_STYLE_LIGHT = 1;
    public const THEME_STYLE_DARK = 2;

    public const HEADER_STYLE_NORMAL = 100;
    public const HEADER_STYLE_H1 = 1;
    public const HEADER_STYLE_H2 = 2;
    public const HEADER_STYLE_H3 = 3;
    public const HEADER_STYLE_H4 = 4;
    public const HEADER_STYLE_H5 = 5;

    public const HEADER_STYLE_ALIGN_CENTER = 1;
    public const HEADER_STYLE_ALIGN_LEFT = 2;
    public const HEADER_STYLE_ALIGN_RIGHT = 3;

    #endregion

    #region FIELDS

    /**
     * @var IView
     */
    protected $view;
    protected $isVisible = true;
    protected $embed_view = self::EMBED_VIEW_DEFAULT;
    protected $can_include_border = true;
    protected $can_include_round_border = false;
    protected $theme_style = self::THEME_STYLE_LIGHT;
    protected $header_text_style = self::HEADER_STYLE_NORMAL;
    protected $header_align_style = self::HEADER_STYLE_ALIGN_LEFT;
    protected $border_color = '';
    protected $url = '';
    protected $header = '';
    protected $embedHandler;

    #endregion

    #region ACCESSORS

    /**
     * @return bool
     */
    public function isVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * @return int|null
     */
    public function getEmbedView() : ?int
    {
        return $this->embed_view;
    }

    public function canIncludeBorder(): ?bool
    {
        return $this->can_include_border;
    }

    public function canIncludeRoundBorder(): ?bool
    {
        return $this->can_include_round_border;
    }

    public function getThemeStyle(): ?int
    {
        return $this->theme_style;
    }

    public function getHeaderTextStyle(): ?int
    {
        return $this->header_text_style;
    }

    public function getHeaderAlignStyle(): ?int
    {
        return $this->header_align_style;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getHeader(): ?string
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return PlayerWidget
     */
    protected function setHeader(string $header): PlayerWidget
    {
        $this->header = $header;
        return $this;
    }

    public function getBorderColor(): string
    {
        return $this->border_color;
    }

    #endregion

    public function __construct()
    {
        add_action( 'customize_preview_init', [$this, 'addCustomizerCss'] );
        $this->view = new View('widgets.player-form');
        $this->embedHandler = new EmbedHandler(new View('embed'));
        $widgetOptions = [
            'description' =>
            'Displays TalkShopLive shows and or channels',
            'customize_selective_refresh' => true,
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
            ];

        parent::__construct(self::WIDGET_ID, 'TalkShopLive Player', $widgetOptions);
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
        $this->addCustomizerCss();
    }

    public function addCustomizerCss(): void
    {
        wp_enqueue_style(self::WIDGET_ID . '_customizer_css', ViewController::getAssetsViewUrl('css/widget.css') );
    }


    /**
     * @param bool $isVisible
     * @return PlayerWidget
     */
    public function setIsVisible(bool $isVisible): PlayerWidget
    {
        $this->isVisible = $isVisible;
        return $this;
    }


    public function form($instance)
    {
        $this->setInstanceValues($instance);
        $this->view->render($this);
    }

    protected function setInstanceValues($instance): void
    {
        $defaultPlayerSettings = new PlayerAttributes();
        $defaultPlayerSettings->fromJson(Option::get(OptionKeys::PLAYER_DEFAULTS));

        if (empty($instance)) {
            $this->can_include_border = $defaultPlayerSettings->getDataStyleBorderWidth();
            $this->can_include_round_border = $defaultPlayerSettings->getDataStyleBorderRadius();
            $this->embed_view = self::getDataViewId($defaultPlayerSettings->getDataView());
            $this->theme_style = self::getThemeStyleId($defaultPlayerSettings->getDataStyleTheme());
        } else {
            $this->can_include_border = isset($instance['can_include_border']) ? filter_var($instance['can_include_border'], FILTER_VALIDATE_BOOLEAN) : false;
            $this->can_include_round_border = isset($instance['can_include_round_border']) ? filter_var($instance['can_include_round_border'], FILTER_VALIDATE_BOOLEAN) : false;
            $this->url = isset($instance['url']) ? filter_var($instance['url']) : '';
            $this->header = isset($instance['header']) ? filter_var($instance['header']) : $this->header;
            $this->border_color = isset($instance['border_color']) ? filter_var($instance['border_color']) : $this->border_color;
            $this->embed_view = isset($instance['embed_view']) ? filter_var($instance['embed_view'], FILTER_VALIDATE_INT) : $this->embed_view;
            $this->theme_style = isset($instance['theme_style']) ? filter_var($instance['theme_style'], FILTER_VALIDATE_INT) : $this->theme_style;
        }

        $this->isVisible = isset($instance['is_visible']) ? filter_var($instance['is_visible'], FILTER_VALIDATE_BOOLEAN) : $this->isVisible();
        $this->header_text_style = isset($instance['header_text_style']) ? filter_var($instance['header_text_style']) : $this->getHeaderTextStyle();
        $this->header_align_style = isset($instance['header_align_style']) ? filter_var($instance['header_align_style']) : $this->getHeaderAlignStyle();
    }

    public static function getDataViewId(string $dataView)
    {
        switch ($dataView) {
            case PlayerAttributes::DATA_VIEW_DEFAULT :
                return self::EMBED_VIEW_DEFAULT;
            case PlayerAttributes::DATA_VIEW_CHAT :
                return self::EMBED_VIEW_CHAT;
            case PlayerAttributes::DATA_VIEW_PRODUCTS :
                return self::EMBED_VIEW_PRODUCTS;
        }
    }

    public static function getDataViewFromId(int $id)
    {
        switch ($id) {
            case  self::EMBED_VIEW_DEFAULT:
                return PlayerAttributes::DATA_VIEW_DEFAULT;
            case  self::EMBED_VIEW_CHAT:
                return PlayerAttributes::DATA_VIEW_CHAT;
            case  self::EMBED_VIEW_PRODUCTS:
                return PlayerAttributes::DATA_VIEW_PRODUCTS;
        }
    }

    public static function getThemeStyleId(string $dataThemeStyle){
        switch ($dataThemeStyle) {
            case PlayerAttributes::DATA_THEME_STYLE_LIGHT :
                return self::THEME_STYLE_LIGHT;
            case PlayerAttributes::DATA_THEME_STYLE_DARK :
                return self::THEME_STYLE_DARK;
        }
    }

    public static function getThemeStyleFromId(int $id)
    {
        switch ($id) {
            case  self::THEME_STYLE_LIGHT:
                return PlayerAttributes::DATA_THEME_STYLE_LIGHT;
            case  self::THEME_STYLE_DARK:
                return PlayerAttributes::DATA_THEME_STYLE_DARK;
        }
    }

    public function update($new_instance, $old_instance): array
    {
        $instance = $old_instance;
        $isVisible = sanitize_text_field($new_instance['is_visible']);
        $this->isVisible = filter_var( $isVisible,FILTER_VALIDATE_BOOLEAN);
        $instance['is_visible'] = $this->isVisible;
        $this->url = sanitize_text_field($new_instance['url']);
        $this->header = sanitize_text_field($new_instance['header']);
        $this->embed_view = (int)sanitize_text_field($new_instance['embed_view']);
        $this->theme_style = (int)sanitize_text_field($new_instance['theme_style']);
        $this->can_include_border = filter_var($new_instance['can_include_border'], FILTER_VALIDATE_BOOLEAN);
        $this->can_include_round_border = sanitize_text_field($new_instance['can_include_round_border']);
        $this->header_text_style = sanitize_text_field($new_instance['header_text_style']);
        $this->header_align_style = sanitize_text_field($new_instance['header_align_style']);
        $this->border_color = sanitize_text_field($new_instance['border_color']);

        if (!empty($this->url)) {
            $match = $this->embedHandler->matchUrl($this->url);

            if (!empty($match['settings'])) {
                $htmlAttributes = new PlayerAttributes();
                $htmlAttributes->fromQueryString($match['settings']);

                $this->embed_view = self::getDataViewId($htmlAttributes->getDataView());
                $this->border_color = $htmlAttributes->getDataStyleBorderWidth();
                $this->can_include_round_border = $htmlAttributes->getDataStyleBorderRadius();
                $this->theme_style = self::getThemeStyleId($htmlAttributes->getDataStyleTheme());
            }

            $this->url = $match['url'];
        }


        $instance['url'] = $this->url;
        $instance['header'] = $this->header;
        $instance['embed_view'] = $this->embed_view;
        $instance['can_include_border'] = $this->can_include_border;
        $instance['can_include_round_border'] = $this->can_include_round_border;
        $instance['border_color'] = $this->border_color;
        $instance['theme_style'] = $this->theme_style;
        $instance['header_text_style'] = $this->getHeaderTextStyle();
        $instance['header_align_style'] = $this->getHeaderAlignStyle();


        return $instance;
    }

    protected function filterTitle($title): string
    {
        $class = 'tsl-widget-title-left';

        switch ($this->getHeaderAlignStyle()) {
            case self::HEADER_STYLE_ALIGN_RIGHT;
                $class = 'tsl-widget-title-right';
                break;
            case self::HEADER_STYLE_ALIGN_CENTER;
                $class = 'tsl-widget-title-center';
                break;
        }

        $filteredTitle = sprintf('<h2 class="widget-title %s">%s</h2>', $class, $title);

        switch ($this->getHeaderTextStyle()) {
            case self::HEADER_STYLE_H1:
                $filteredTitle = sprintf('<h1 class="widget-title %s">%s</h1>', $class, $title);
                break;
            case self::HEADER_STYLE_H3:
                $filteredTitle = sprintf('<h3 class="widget-title %s">%s</h3>', $class, $title);
                break;
            case self::HEADER_STYLE_H4:
                $filteredTitle = sprintf('<h4 class="widget-title %s">%s</h4>', $class, $title);
                break;
            case self::HEADER_STYLE_H5:
                $filteredTitle = sprintf('<h5 class="widget-title %s">%s</h5>', $class, $title);
                break;
        }

        return $filteredTitle;
    }


    /**
     * @inheritDoc
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) : void
    {
        $this->setInstanceValues($instance);
        // before and after widget arguments are defined by themes
        echo \wp_kses_post($args['before_widget']);

        if (filter_var( $instance['is_visible'],FILTER_VALIDATE_BOOLEAN)) {
            $title = apply_filters( 'widget_title', $instance['header'] );

            if ( ! empty( $title ) ) {
                echo \wp_kses_post($this->filterTitle($title));
            }

            $url =  preg_replace('/\s.*/', '', $instance['url']);

            if (!empty($url)) {

                $htmlAttributes = new PlayerAttributes();

                $htmlAttributes->setDataView(self::getDataViewFromId($this->getEmbedView()));
                $htmlAttributes->setDataStyleBorderWidth($this->canIncludeBorder() ? '1' : '0');
                $htmlAttributes->setDataStyleBorderRadius($this->canIncludeRoundBorder() ? '1' : '0');
                $htmlAttributes->setDataStyleTheme(self::getThemeStyleFromId($this->getThemeStyle()));
                $htmlAttributes->setDataStyleBorderColor($this->canIncludeBorder() ? $this->getBorderColor() : '');

                $url .= "?{$htmlAttributes->toQueryString()}";
            }

            $match = $this->embedHandler->matchUrl($url);
            echo $this->embedHandler->getContent($match);
        }

        echo \wp_kses_post($args['after_widget']);
    }

    /**
     * Constructs html label with id attributes for use in WP_Widget::form() fields.
     *
     * @param $fieldName
     * @param $value
     * @return string
     */
    public function generateLabelForField($fieldName, $value): string
    {
        return  sprintf('<label for="%s">%s</label>',
            $this->get_field_id($fieldName),
            esc_attr( $value )
        );
    }

    /**
     * Constructs html input[text] with id and name attributes for use in WP_Widget::form() fields.
     *
     * @param $fieldName
     * @param $value
     * @return string
     */
    public function generateInputForField($fieldName, $value) : string
    {
        return sprintf('<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
            $this->get_field_id($fieldName),
            $this->get_field_name($fieldName),
            esc_attr($value)
        );
    }

    /**
     * Constructs html select with id and name attributes for use in WP_Widget::form() fields.
     *
     * @param $fieldName
     * @param SelectOption[] $options
     * @return string
     */
    public function generateSelectForField($fieldName, array $options): string
    {
        $optionsData = '';

        foreach ($options as $option) {
            $optionsData .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $option->getValue() ), selected( $option->isSelected(), true, false ), esc_html( $option->getText() ));
        }

        return sprintf('<select class="widefat" id="%s" name="%s">%s</select>',
            $this->get_field_id($fieldName),
            $this->get_field_name($fieldName),
            $optionsData
        );

    }

    /**
     * Constructs html input[checkbox] with id and name attributes for use in WP_Widget::form() fields.
     *
     * @param $fieldName
     * @param bool $isChecked whether is checked
     * @return string
     */
    public function generateCheckboxForField($fieldName, $isChecked = false): string
    {

       return sprintf('<input class="widefat" type="checkbox" id="%s" name="%s"  %s />',
            $this->get_field_id($fieldName),
            $this->get_field_name($fieldName),
            esc_attr(  $isChecked ? 'checked' : '' )
        );
    }

}