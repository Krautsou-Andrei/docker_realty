<?php
function get_cookies_favorites($is_string = false)
{
    $post_ids = [];
    $cookie_value = '';
    if (isset($_COOKIE['favorites'])) {
        $cookie_value = $_COOKIE['favorites'];
    }


    if (!empty($cookie_value)) {
        $unescaped_value = stripslashes($cookie_value);
        $post_ids = json_decode($unescaped_value, true); // Декодирование JSON строки в массив
        if (!$is_string) {
            $post_ids = array_map('intval', $post_ids);
        }
    }

    return $post_ids;
}
