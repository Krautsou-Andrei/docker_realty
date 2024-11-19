<?php

function get_category_map()
{

    $categories = get_categories(array(
        'hide_empty' => false, // Установите false, чтобы получить все категории, включая пустые
    ));

    $category_map = [];

    if ($categories) {
        foreach ($categories as $category) {
            $category_map[$category->name] = $category->term_id;
        }
    }
    return $category_map;
}
