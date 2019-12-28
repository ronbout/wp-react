<?php

/**
 * Custom functions for the 3sixd site
 * 'td_' will be prefix for names.
 *
 * Ron Boutilier
 * 12/15/2018
 */
defined('ABSPATH') or die('Direct script access disallowed.');

require_once get_stylesheet_directory().'/inc/react-setup.php';

// Set up candid query vars
function tsd_register_query_vars($vars)
{
    $vars[] = 'candid';

    return $vars;
}
add_filter('query_vars', 'tsd_register_query_vars');

// Add rewrite tags and rules for candid.
// function tsd_rewrite_tag_rule()
// {
//     add_rewrite_tag('%candid%', '([^&]+)');
//     add_rewrite_rule('^candid/([^/]*)/?', 'index.php?pagename=bio&candid=$matches[1]', 'top');
// }
// add_action('init', 'tsd_rewrite_tag_rule', 10, 0);

//* Enqueue Jobs Page Stylesheets and Scripts
add_action('wp_enqueue_scripts', 'td_load_resources');

function td_load_resources()
{
    // for Candidate resumes and job alerts dashboards, turn off caching by
    // adding time to the js url as the version number
    // $js_ver = (is_page('candidate-dashboard') || is_page('job-alerts') || is_page('job-dashboard')) ? time() : false;
    // wp_register_script('jl-js', get_stylesheet_directory_uri().'/js/custom.js', ['jquery'], $js_ver, true);
    // //wp_register_script( 'jl-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery', 'wp-resume-manager-resume-submission' ), $js_ver, true);
    // wp_enqueue_script('jl-js');
}

// // change default login page, which some Job plugins use to our sign in page
// if (! is_admin() ) {
// add_filter( 'login_url', 'td_login_page', 10, 2 );
// function td_login_page( $login_url, $redirect ) {
// return home_url( '/sign-in/?redirect_to=' . $redirect );
// }
// }

// must turn off admin access to candidate, customer and subscriber users
function td_no_admin_access()
{
    global $current_user;
    $redirect = home_url('/');
    $user = wp_get_current_user();
    $role = $user->roles[0];
    if (('CANDIDATE' === strtoupper($role) || 'SUBSCRIBER' === strtoupper($role) || 'CUSTOMER' === strtoupper($role)) &&
(!wp_doing_ajax())) {
        exit(wp_redirect($redirect));
    }
}
add_action('admin_init', 'td_no_admin_access', 1);

// hide the admin bar if the user is any of the above
if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $role = $user->roles[0];
    if ('CANDIDATE' === strtoupper($role) || 'SUBSCRIBER' === strtoupper($role) || 'CUSTOMER' === strtoupper($role)) {
        show_admin_bar(false);
    }
}

// // must allow Post A Job access to only Admins and Employers
// function td_no_post_job_access() {
// global $current_user;
// if (is_page('post-a-job') && is_user_logged_in()) {
// $redirect = home_url( '/' );
// $user = wp_get_current_user();
// $role = $user->roles[0];
// if ( strtoupper($role) !== 'ADMINISTRATOR' && strtoupper($role) !== 'EMPLOYER') {
// exit( wp_redirect( $redirect ) );
// }
// }
// }
// add_action( 'template_redirect', 'td_no_post_job_access', 1 );

// have to add 'action' to public query vars so I can look it
// up in the next step. plugin added query w/o registering with wp
function add_query_vars_filter($vars)
{
    $vars[] = 'action';

    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

// add class to the body so that
add_filter('body_class', 'add_body_classes');
function add_body_classes($classes)
{
    global $post;

    // if (is_page(array('resumes', 'jobs'))) {
    // $classes[] = 'jobs-resumes-page';
    // }
    // if (is_page('submit-resume') || (is_page('candidate-dashboard') && get_query_var('action') === 'edit') ) {
    // $classes[] = 'resume-form-page';
    // }
    // for better identification, just put page name in body class
    $classes[] = $post->post_name;

    return $classes;
}
