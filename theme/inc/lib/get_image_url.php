<?php

function get_image_url($url)
{
    if (!empty($url)) {
        return $url[0];
    }

    return get_template_directory_uri() . '/assets/images/no_image.png';
}
