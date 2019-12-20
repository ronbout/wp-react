<?php

/**
 * @package reactTestPlugin
 * 
 */

/*
	Plugin Name: React reactTest
	Plugin URI: http://localhost/up
	Description: This is a test of embedding react
	Version: 1.0.0
	Author: Ron Boutilier
	License: GPLv2
	Text Domain: react-test-plugin
 */

defined('ABSPATH') or die('Direct script access disallowed.');

define('TSD_RT_PATH', plugin_dir_path(__FILE__));
define('TSD_RT_ASSET_MANIFEST', TSD_RT_PATH . '/build/asset-manifest.json');
define('TSD_RT_INCLUDES', TSD_RT_PATH . '/includes');

require_once(TSD_RT_INCLUDES . '/enqueue.php');
require_once(TSD_RT_INCLUDES . '/shortcode.php');
