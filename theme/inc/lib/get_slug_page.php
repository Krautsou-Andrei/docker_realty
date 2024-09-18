<?php


function get_slug_page()
{
    $current_uri = $_SERVER['REQUEST_URI'];
    $uri_parts = explode('/', trim($current_uri, '/'));
    $slug = end($uri_parts);

    return $slug;
}
