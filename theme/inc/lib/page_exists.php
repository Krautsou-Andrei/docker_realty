<?php
function page_exists($slug)
{
    $args = array(
        'name'        => $slug, // Слаг
        'post_type'   => 'page', // Тип поста
        'post_status' => 'publish', // Только опубликованные страницы
        'numberposts' => 1, // Только одна страница
    );

    $pages = get_posts($args);

    if (!empty($pages)) {
        return $pages[0]->ID;
    }
    return !empty($pages);
}