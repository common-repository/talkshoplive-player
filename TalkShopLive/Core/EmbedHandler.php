<?php

namespace TalkShopLive\Core;


use TalkShopLive\Core\Data\IView;
use TalkShopLive\Core\Widgets\PlayerAttributes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class EmbedHandler {

    public const FILTER_TAG = 'embed_handler_talkshoplive';
    /**
     * @var View
     */
    protected $view;

    protected $pattern = '(?P<url>https://(www)?\.?talkshop\.live/(?P<type>.*?)/(?P<handle_id>[^\?]+))(?P<query>\??(?P<settings>.*))';
    protected $playerAttributes;

    public function __construct(IView  $view)
    {
        $this->view = $view;
        $this->playerAttributes = new PlayerAttributes();
    }

    /**
     * Call if $this->pattern evaluates to true.
     *
     * @param array $matches The RegEx matches from the provided regex when calling
     *                        wp_embed_register_handler().
     * @param array $attr    Embed attributes. can be passed with the embed shortcode
     *                       @example [embed width="560" height="315"]https://talkshop.live/watch/someshow/[/embed]
     * @param string $url     The original URL that was matched by the regex.
     * @param mixed $rawAttr The original unmodified attributes.
     * @return mixed|void
     *
     * @example https://talkshop.live/watch/Tw_61UnDXhFV
     */
    public function handler(array $matches, array $attr, string $url, $rawAttr )
    {
        $embed = $this->getContent($matches);

        /**
         * @param string $embed   embed output.
         * @param array  $attr    An array of embed attributes.
         * @param string $url     The original URL that was matched by the regex.
         * @param array  $rawattr The original unmodified attributes.
         */
        return apply_filters( self::FILTER_TAG, $embed, $attr, $url, $rawAttr );
    }

    /**
     * content filtered by wp_kses
     *
     * @param $matches
     * @return string
     */
    public function getContent($matches): string
    {
        ob_start();
        $this->view->render($matches);

        $allowedTags = [
            'script' => [
                'src' => [],
                'async' => [],
                'crossorigin' => []
            ],
            'div' => [
                'class' => []
            ],
        ];

        foreach ($this->playerAttributes->toArray() as $attrName => $val) {
            $allowedTags['div'][$attrName] = [];
        }

        return \wp_kses(ob_get_clean(), $allowedTags);
    }

    public function matchUrl($url){
        preg_match("#{$this->getPattern()}#i", $url, $matches);

        return $matches;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function registerEmbed($priority = 20): void
    {
        wp_embed_register_handler(
            'talkshoplive',
            "#{$this->getPattern()}#i",
            [$this, 'handler'],
            $priority
        );
    }
}