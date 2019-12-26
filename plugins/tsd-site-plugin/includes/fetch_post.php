<?php

// standard routine for posting data to an api

define('TSD_API_LOC', 'http://localhost/3sixd/api/');
//define('TSD_API_LOC','http://13.90.143.153/3sixd/api/' );
define('API_QUERY', '?api_cc=three&api_key=fj49fk390gfk3f50');

function fetch_post($endpoint, $query_str, $body)
{
    $url = TSD_API_LOC.$endpoint.API_QUERY.$query_str;
    $options = [
        'body' => $body,
    ];

    $response = wp_remote_post($url, $options);
    if (is_wp_error($response)) {
        show_message($response->get_error_message());

        return false;
    }
    $json_ret = json_decode($response['body']);
    if ($json_ret->error) {
        return $json_ret;
    }

    return $json_ret->data;
}
