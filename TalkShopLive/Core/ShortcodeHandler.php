<?php

namespace TalkShopLive\Core;

use TalkShopLive\Core\Widgets\PlayerAttributes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class ShortcodeHandler {

    /**
     * @var EmbedHandler
     */
    protected $embedHandler;

    public const CODE_TAG = 'talkshoplive';

    /**
     * @return EmbedHandler
     */
    public function getEmbedHandler(): EmbedHandler
    {
        return $this->embedHandler;
    }

    /**
     * @param EmbedHandler $embedHandler
     * @return ShortcodeHandler
     */
    protected function setEmbedHandler(EmbedHandler $embedHandler): ShortcodeHandler
    {
        $this->embedHandler = $embedHandler;
        return $this;
    }

    public function __construct(){
        $this->setEmbedHandler(new EmbedHandler(new View('embed')));
    }


    public function register($attributes = [], $content = ''): void
    {
        add_shortcode(self::CODE_TAG, function ($attributes, $content) {
            return $this->generate($attributes, $content);
        });
    }


    public function generate($attributes, $content = ''): string
    {
        $embedContent = '';

        if (!empty($attributes['url'])) {

            $url = preg_replace('/\?.*/', '', $attributes['url']);

            $playerAttributes = new PlayerAttributes();
            $playerAttributes->fromArray($attributes);

            $url .= "?{$playerAttributes->toQueryString()}";

            $match = $this->getEmbedHandler()->matchUrl($url);

            $embedContent = $this->embedHandler->getContent($match);
        } else {
            $embedContent = '<p style="color:#ff0000" aria-roledescription="talkshoplive-error">Required parameter "url" is missing</p>';
        }

        return $embedContent;
    }

}