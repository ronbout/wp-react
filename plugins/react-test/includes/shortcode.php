<?php
// This file enqueues a shortcode.

defined('ABSPATH') or die('Direct script access disallowed.');

add_shortcode('tsd_rt_widget', function ($atts) {
	$default_atts = array();
	$args = shortcode_atts($default_atts, $atts);

	return "<div id='tsd-rt-root'></div>";
});
