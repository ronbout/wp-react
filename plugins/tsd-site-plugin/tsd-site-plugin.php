<?php

/*
    Plugin Name: 3sixd Site Plugin
    Plugin URI: http://localhost/up
    Description: Various functionalities for 3sixd.com
    Version: 1.0.0
    Author: Ron Boutilier
    License: GPLv2
    Text Domain: tsd-site-plugin
 */

defined('ABSPATH') or die('Direct script access disallowed.');

define('TSD_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TSD_PLUGIN_INCLUDES', TSD_PLUGIN_PATH.'/includes');

// dirty quick show message for non-admin page
if (!function_exists('show_message') && !is_admin()) {
    function show_message($msg)
    {
        // add_filter('registration_errors', function ($errors, $sanitized_user_login, $user_email) use ($msg) {
        //     $errors->add('Candidate Creation Error', __('<strong>ERROR</strong>: '.$msg, 'tsd'));
        // });

        var_dump($msg);
        die();
    }
}

require_once TSD_PLUGIN_INCLUDES.'/fetch_post.php';
require_once TSD_PLUGIN_INCLUDES.'/user-fields.php';
// require_once TSD_PLUGIN_INCLUDES.'/enqueue.php';
// require_once TSD_PLUGIN_INCLUDES.'/shortcode.php';

UserFields::get_instance();
