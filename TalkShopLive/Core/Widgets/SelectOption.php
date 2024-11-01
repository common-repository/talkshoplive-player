<?php


namespace TalkShopLive\Core\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class SelectOption {

    private $value;
    private $isSelected;
    private $text;

    /**
     * @param string $value
     * @param bool $isSelected
     * @param string $text
     */
    public function __construct(string $text, string $value, bool $isSelected)
    {
        $this->value = $value;
        $this->isSelected = $isSelected;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return SelectOption
     */
    public function setValue(string $value): SelectOption
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->isSelected;
    }

    /**
     * @param bool $isSelected
     * @return SelectOption
     */
    public function setIsSelected(bool $isSelected): SelectOption
    {
        $this->isSelected = $isSelected;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return SelectOption
     */
    public function setText(string $text): SelectOption
    {
        $this->text = $text;
        return $this;
    }

}