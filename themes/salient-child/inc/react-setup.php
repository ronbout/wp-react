<?php

// code for loading react resources on react-page
add_action('init', function () {
    add_filter('script_loader_tag', function ($tag, $handle) {
        if (!preg_match('/^tsd-rt-/', $handle)) {
            return $tag;
        }

        return str_replace(' src', ' async defer src', $tag);
    }, 10, 2);

    add_action('wp_enqueue_scripts', function () {
        global $post;
        $page_name = $post->post_name;

        if ('page-react.php' === basename(get_page_template())) {
            //fonts.googleapis.com/icon?family=Material+Icons
            wp_enqueue_style('material-icons', '//fonts.googleapis.com/icon?family=Material+Icons');

            $manifest_file = get_stylesheet_directory().'/react/'.$page_name.'/asset-manifest.json';
            if (file_exists($manifest_file)) {
                $asset_manifest = json_decode(file_get_contents($manifest_file), true)['files'];

                if (isset($asset_manifest['main.css'])) {
                    wp_enqueue_style('tsd-rt', get_site_url().$asset_manifest['main.css']);
                }

                wp_enqueue_script('tsd-rt-main', get_site_url().$asset_manifest['main.js'], ['tsd-rt-runtime'], null, true);

                foreach ($asset_manifest as $key => $value) {
                    if ('runtime' === substr($key, 0, 7) && 'js' === substr($key, -2, 2)) {
                        wp_enqueue_script('tsd-rt-runtime', get_site_url().$value, [], null, true);
                    }
                    if (preg_match('@static/js/(.*)\.chunk\.js@', $key, $matches)) {
                        if ($matches && is_array($matches) && 2 === count($matches)) {
                            $name = 'tsd-rt-'.preg_replace('/[^A-Za-z0-9_]/', '-', $matches[1]);
                            wp_enqueue_script($name, get_site_url().$value, ['tsd-rt-main'], null, true);
                        }
                    }

                    if (preg_match('@static/css/(.*)\.chunk\.css@', $key, $matches)) {
                        if ($matches && is_array($matches) && 2 == count($matches)) {
                            $name = 'tsd-rt-'.preg_replace('/[^A-Za-z0-9_]/', '-', $matches[1]);
                            wp_enqueue_style($name, get_site_url().$value, ['tsd-rt'], null);
                        }
                    }
                }
            }
        }
    });
});
