<?php

namespace TalkShopLive\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
class Option {

    protected const PREFIX = 'talk_shop_live::';
    protected $name = '';
    protected $value;

    public static function getPrefix(): string
    {
        return self::PREFIX;
    }

    public function __construct($name, $value)
    {
        if ($value === true) {
            $value = 'true';
        }

        if ($value === false){
            $value = 'false';
        }

        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Option
     */
    protected function setName(string $name): Option
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Option
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public static function getRealName($name): string
    {
        return self::getPrefix() . $name;
    }

    public function save($autoload = 'yes'): void
    {
        self::addOption($this, $autoload);
    }

    public function update($autoload = null): void
    {
        self::updateOption($this, $autoload);
    }


    public static function addOption(Option $setting, $autoload = 'yes'): void
    {
        \add_option( self::getRealName($setting->getName()), $setting->getValue(), '', $autoload );
    }

    public static function updateOption(Option $setting, $autoload = null): void
    {
        \update_option( self::getRealName($setting->getName()), $setting->getValue(), $autoload );
    }

    public static function deleteOption(Option $setting): void
    {
        \delete_option( self::getRealName($setting->getName()));
    }

    public static function get($name, $default = null)
    {
        $value = \get_option( self::getRealName($name), $default);

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return $value;
    }


}