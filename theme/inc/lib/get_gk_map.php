<?php
function get_gk_map($parent_id)
{
    $args = [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_parent'   => $parent_id,
        'fields'        => 'ids'
    ];

    $query = get_posts($args);

    $argsProducts = [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_parent__in' => $query,
    ];

    $pages_city = get_posts($argsProducts);

    $productIdMap = [];

    if (!empty($pages_city)) {
        foreach ($pages_city as $page) {
            $post_id = $page->ID;
            $crb_gk_id = carbon_get_post_meta($post_id, 'crb_gk_id');
            $productIdMap[$crb_gk_id] = $post_id;
        }
    }

    return $productIdMap;
}
