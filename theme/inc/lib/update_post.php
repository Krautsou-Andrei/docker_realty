<?php
require_once get_template_directory() . '/inc/lib/update_posts_in_db.php';

function update_post($data, $post_id)
{
    global $update_posts_map;
    date_default_timezone_set('Europe/Moscow');

    $product_price = $data['product_price'];
    $product_price_meter = $data['product_price_meter'];
    $product_year_build = $data['product_year_build'];
    $product_finishing = $data['product_finishing'];

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }

    $data_update_post_meta = [
        '_product-price' => $product_price,
        '_product-price-meter' => $product_price_meter,
        '_product-year-build' => $date_build,
        '_product-finishing' => $product_finishing,
    ];

    $data_update_post = [
        'post_modified' => current_time('mysql'),
        'post_modified_gmt' => current_time('mysql', 1)
    ];

    $update_posts_map[$post_id] = [$data_update_post_meta, $data_update_post];

    if (count($update_posts_map) >= 1000) {
        update_posts_in_db();
    }
}
