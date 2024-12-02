<?php
function get_post_map($ids_gk_category)
{
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1, // Получаем все посты
        'fields'         => 'ids', // Получаем только ID    
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $ids_gk_category,
            ]
        ],
    ];

    $query = get_posts($args);

    $productIdMap = [];

    foreach ($query as $post_id) {
        $product_id = carbon_get_post_meta($post_id, 'product-id');
        $productIdMap[$product_id] = $post_id;
    }

    return $productIdMap;
}

function get_post_map_category($ids_gk_category)
{
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1, // Получаем все посты      
        'fields'         => 'ids',
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $ids_gk_category,
            ]
        ],
    ];

    $query = get_posts($args);

    $productIdMap = [];

    foreach ($query as $post_id) {
        $categories = get_the_category($post_id);
        $categories_id = array_column($categories, 'term_id');
        $productIdMap[$post_id] = $categories_id;
    }

    return $productIdMap;
}
function get_post_map_no_image($ids_gk_category)
{
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1, // Получаем все посты
        'fields'         => 'ids', // Получаем только ID    
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $ids_gk_category,
            ]
        ],
    ];

    $query = get_posts($args);

    $productIdMap = [];

    foreach ($query as $post_id) {
        $product_id = carbon_get_post_meta($post_id, 'product-id');
        $gallery = carbon_get_post_meta($post_id, 'product-gallery');

        if (empty($gallery)) {
            $productIdMap[$product_id] = $post_id;
        }
    }

    return $productIdMap;
}
