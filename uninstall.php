<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
require(__DIR__ . DIRECTORY_SEPARATOR . 'autoload.php');

use TalkShopLive\Core\Option;
use TalkShopLive\Core\Data\OptionKeys;

$optionName = Option::getRealName(OptionKeys::PLAYER_DEFAULTS);
 
delete_option($optionName);