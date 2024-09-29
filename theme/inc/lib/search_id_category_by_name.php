<?php

function search_id_category_by_name($category_name)
{
    $category = get_term_by('name', $category_name, 'category');

    if ($category) {
        return $category->term_id;
    } else {
        return null;
    }
}
