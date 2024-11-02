<?php

function get_image_url($url, $is_full = false)
{
    if (!empty($url)) {
        if ($is_full) {
            return $url;
        } else {
            return $url[0];
        }
    }

    return get_template_directory_uri() . '/assets/images/no_image.png';
}
