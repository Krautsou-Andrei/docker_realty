<?php

function get_names_children_categories($parent_id)
{

    $args = array(
        'parent' => $parent_id,
        'hide_empty' => true,
    );

    $categories = get_categories($args);
    $child_category_names = [];

    foreach ($categories as $category) {
        $child_category_names[] = $category->name;
    }

    return $child_category_names;
}
