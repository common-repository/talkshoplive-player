<?php

namespace TalkShopLive\Core\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class PlayerAttributes implements \JsonSerializable
{
    public const DATA_VIEW_DEFAULT = 'default';
    public const DATA_VIEW_CHAT = 'chat';
    public const DATA_VIEW_PRODUCTS = 'products';

    public const DATA_TYPE_SHOW = 'show';
    public const DATA_TYPE_CHANNEL = 'channel';

    public const DATA_THEME_STYLE_LIGHT = 'light';
    public const DATA_THEME_STYLE_DARK = 'dark';

    protected $data_type = '';
    protected $data_modus = '';
    protected $data_view = '';
    protected $data_event_id = '';
    protected $data_dnt = '';
    protected $data_manual_init = '';
    protected $data_style_border_width = '0';
    protected $data_style_border_radius = '0';
    protected $data_style_theme = '';
    protected $data_style_border_color = '';

    /**
     * Helper method for easy creation
     * @param $jsonData
     */
    public function fromJson($jsonData): void
    {
        if (!is_string($jsonData)) {
            throw new \RuntimeException('Invalid data - must be string');
        }

        $data = \json_decode($jsonData);

        $this->toObject($data);

    }

    protected function toObject($data): void
    {
        if (!empty($data)) {
            foreach ($data as $property => $value) {
                $property = str_replace('-', '_', $property);
                if (property_exists($this, $property)) {
                    $this->{$property} = preg_replace('/[^ \w-]/', '', $value);
                }
            }
        }
    }

    public function fromArray(array $data): void
    {
        $this->toObject($data);
    }

    public function fromQueryString($queryString): void
    {
        parse_str($queryString, $parameters);

        $this->toObject($parameters);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson(){
        return \json_encode($this->toArray());
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'data-type' => $this->getDataType(),
            'data-modus' => $this->getDataModus(),
            'data-view' => $this->getDataView(),
            'data-eventid' => $this->getDataEventId(),
            'data-dnt' => $this->getDataDnt(),
            'data-manual-init' => $this->getDataManualInit(),
            'data-style-border-width' => $this->getDataStyleBorderWidth(),
            'data-style-border-radius' => $this->getDataStyleBorderRadius(),
            'data-style-theme' => $this->getDataStyleTheme(),
            'data-style-border-color' => $this->getDataStyleBorderColor(),
        ];
    }

    public function toQueryString(): string
    {

        $attributes = [];

        foreach ($this->toArray() as $attributeName => $attributeValue) {
            if (!empty($attributeValue)) {
                $attributes[$attributeName] = $attributeValue;
            }
        }

        return \http_build_query($attributes);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $attributes = ' ';

        $data = $this->toArray();

        foreach ($data as $attribute => $attributeValue) {
            if (!$this->isEmpty($attributeValue)) {
                $attributes .= $this->attrToString($attribute, $attributeValue);
            }
        }

        return trim($attributes);
    }

    protected function isEmpty($string): bool
    {
        if($string === '0') {
            return false;
        }
        return !(bool)$string;
    }

    protected function attrToString($attrName, $value): string
    {
        $value = esc_html(esc_attr($value));
        return "{$attrName}=\"{$value}\" ";
    }

    protected function escapeAttributes($data): string
    {
        return esc_attr($data);
    }

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->escapeAttributes($this->data_type);
    }

    /**
     * @param string $data_type
     * @return PlayerAttributes
     */
    public function setDataType(string $data_type): PlayerAttributes
    {
        $this->data_type = $data_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataModus(): string
    {
        return $this->escapeAttributes($this->data_modus);
    }

    /**
     * @param string $data_modus
     * @return PlayerAttributes
     */
    public function setDataModus(string $data_modus): PlayerAttributes
    {
        $this->data_modus = $data_modus;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataView(): string
    {
        return $this->escapeAttributes($this->data_view);
    }

    /**
     * @param string $data_view
     * @return PlayerAttributes
     */
    public function setDataView(string $data_view): PlayerAttributes
    {
        $this->data_view = filter_var($data_view, FILTER_SANITIZE_STRING);
        return $this;
    }

    /**
     * @return string
     */
    public function getDataEventId(): string
    {
        return $this->escapeAttributes($this->data_event_id);
    }

    /**
     * @param string $data_event_id
     * @return PlayerAttributes
     */
    public function setDataEventId(string $data_event_id): PlayerAttributes
    {
        $this->data_event_id = $data_event_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataDnt(): string
    {
        return $this->escapeAttributes($this->data_dnt);
    }

    /**
     * @param string $data_dnt
     * @return PlayerAttributes
     */
    public function setDataDnt(string $data_dnt): PlayerAttributes
    {
        $this->data_dnt = $data_dnt;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataManualInit(): string
    {
        return $this->escapeAttributes($this->data_manual_init);
    }

    /**
     * @param string $data_manual_init
     * @return PlayerAttributes
     */
    public function setDataManualInit(string $data_manual_init): PlayerAttributes
    {
        $this->data_manual_init = $data_manual_init;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataStyleBorderWidth(): string
    {
        return $this->escapeAttributes($this->data_style_border_width);
    }

    /**
     * @param string $data_style_border_width
     * @return PlayerAttributes
     */
    public function setDataStyleBorderWidth(string $data_style_border_width): PlayerAttributes
    {
        $canHaveBorder = filter_var($data_style_border_width, FILTER_VALIDATE_BOOLEAN);

        if ($canHaveBorder) {
            $this->data_style_border_width = '1';
        } else {
            $this->data_style_border_width = '0';
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getDataStyleBorderRadius(): string
    {
        return $this->escapeAttributes($this->data_style_border_radius);
    }

    /**
     * @param string $data_style_border_radius
     * @return PlayerAttributes
     */
    public function setDataStyleBorderRadius(string $data_style_border_radius): PlayerAttributes
    {
        $this->data_style_border_radius = filter_var($data_style_border_radius, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }

    /**
     * @return string
     */
    public function getDataStyleTheme(): string
    {
        return $this->escapeAttributes($this->data_style_theme);
    }

    /**
     * @param string $data_style_theme
     * @return PlayerAttributes
     */
    public function setDataStyleTheme(string $data_style_theme): PlayerAttributes
    {
        $this->data_style_theme = filter_var($data_style_theme, FILTER_SANITIZE_STRING);
        return $this;
    }

    /**
     * @return string
     */
    public function getDataStyleBorderColor(): string
    {
        if ($this->data_style_border_color) {
            return '#' . $this->escapeAttributes($this->data_style_border_color);
        }
        return $this->escapeAttributes($this->data_style_border_color);
    }

    /**
     * @param string $data_style_border_color
     * @return PlayerAttributes
     */
    public function setDataStyleBorderColor(string $data_style_border_color): PlayerAttributes
    {
        $this->data_style_border_color = filter_var($data_style_border_color, FILTER_SANITIZE_STRING);
        return $this;
    }
}