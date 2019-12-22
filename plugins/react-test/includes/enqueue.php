<?php
// This file enqueues scripts and styles

defined('ABSPATH') or die('Direct script access disallowed.');

add_action('init', function () {

	add_filter('script_loader_tag', function ($tag, $handle) {
		if (!preg_match('/^tsd-rt-/', $handle)) {
			return $tag;
		}
		return str_replace(' src', ' async defer src', $tag);
	}, 10, 2);

	add_action('wp_enqueue_scripts', function () {

		//fonts.googleapis.com/icon?family=Material+Icons
		wp_enqueue_style('material-icons', '//fonts.googleapis.com/icon?family=Material+Icons');

		$asset_manifest = json_decode(file_get_contents(TSD_RT_ASSET_MANIFEST), true)['files'];

		if (isset($asset_manifest['main.css'])) {
			wp_enqueue_style('tsd-rt', get_site_url() . $asset_manifest['main.css']);
		}

		wp_enqueue_script('tsd-rt-main', get_site_url() . $asset_manifest['main.js'], array('tsd-rt-runtime'), null, true);

		foreach ($asset_manifest as $key => $value) {

			if (substr($key, 0, 7) === 'runtime' && substr($key, -2, 2) === 'js') {
				wp_enqueue_script('tsd-rt-runtime', get_site_url() . $value, array(), null, true);
			}
			if (preg_match('@static/js/(.*)\.chunk\.js@', $key, $matches)) {
				if ($matches && is_array($matches) && count($matches) === 2) {
					$name = "tsd-rt-" . preg_replace('/[^A-Za-z0-9_]/', '-', $matches[1]);
					wp_enqueue_script($name, get_site_url() . $value, array('tsd-rt-main'), null, true);
				}
			}

			if (preg_match('@static/css/(.*)\.chunk\.css@', $key, $matches)) {
				if ($matches && is_array($matches) && count($matches) == 2) {
					$name = "tsd-rt-" . preg_replace('/[^A-Za-z0-9_]/', '-', $matches[1]);
					wp_enqueue_style($name, get_site_url() . $value, array('tsd-rt'), null);
				}
			}
		}
	});
});
