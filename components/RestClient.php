<?php
namespace components;

class RestClient
{
    public static function post($url, $post = null) {
        if (is_array($post)) {
            ksort($post);
            $content = http_build_query($post);
            $content_length = strlen($content);
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'timeout' => 5,
                    'header' =>
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length: $content_length\r\n",
                    'content' => $content
                )
            );
            return @file_get_contents($url, false, stream_context_create($options));
        }
    }

    public static function put($url, $post = null) {
        if (is_array($post)) {
            ksort($post);
            $content = http_build_query($post);
            $content_length = strlen($content);
            $options = array(
                'http' => array(
                    'method' => 'PUT',
                    'timeout' => 5,
                    'header' =>
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length: $content_length\r\n",
                    'content' => $content
                )
            );
            return @file_get_contents($url, false, stream_context_create($options));
        }
    }

    public static function get($url, $post = null) {
        if (is_array($post)) {
            ksort($post);
            $url = $url.'?'.http_build_query($post);
        }
        $options = array(
            'http' => array(
                'method' => 'GET',
                'timeout' => 5,
            )
        );
        return @file_get_contents($url, false, stream_context_create($options));
    }
}
