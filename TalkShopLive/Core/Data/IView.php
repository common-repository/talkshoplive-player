<?php

namespace TalkShopLive\Core\Data;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

interface IView {
    public function render($data = null, $canRequireOnce = false);
}