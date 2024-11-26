<?php
function get_latest_post($delete = true)
{
    $args = array(
        'numberposts' => 1,
        'post_type'   => 'post',
        'orderby'     => 'modified',
        'order'       => 'DESC', // Последние сначала
    );

    $latest_posts = get_posts($args);

    $product_id = null;

    if (!empty($latest_posts)) {
        $latest_post = $latest_posts[0];
        $post_id = $latest_post->ID;
        $product_id = carbon_get_post_meta($post_id, 'product-id');

        if ($product_id === '' && $delete) {
            wp_delete_post($post_id, true);
            get_latest_post();
        }
    }

    return $product_id;
}
