<?php
function getPostsMap($ids_gk_category)
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

    $query = new WP_Query($args);

    $productIdMap = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            $product_id = carbon_get_post_meta($post_id, 'product-id');
            $productIdMap[$product_id] = $post_id;
        }
    }

    return $productIdMap;
}
