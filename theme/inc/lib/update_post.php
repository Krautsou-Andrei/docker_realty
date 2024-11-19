<?php
require_once get_template_directory() . '/inc/lib/create_title_post.php';
require_once get_template_directory() . '/inc/lib/set_min_max_value_gk.php';

function update_post($data, $post_id, $page_gk_id)
{
    $product_id = $data->id;
    $product_room_id = $data->product_room_id;
    $product_area = $data->product_area;
    $product_rooms = $data->product_rooms;
    $product_stage = $data->product_stage;

    $product_price = $data->product_price;
    $product_price_meter = $data->product_price_meter;
    $product_year_build = $data->product_year_build;
    $product_finishing = $data->product_finishing;
    $product_block_id = $data->block_id;

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }

    $title = create_title_post($product_room_id, $product_area, $product_stage);
    $post_slug = $product_id;

    carbon_set_post_meta($post_id, 'product-price', $product_price);
    carbon_set_post_meta($post_id, 'product-price-meter',  $product_price_meter);
    carbon_set_post_meta($post_id, 'product-year-build', $date_build);
    carbon_set_post_meta($post_id, 'product-finishing', $product_finishing);

    $updated_post = array(
        'ID'         => $post_id,
        'post_title' => $title . ' ' . $product_id,
        'post_name'     => $post_slug,
    );
    wp_update_post($updated_post);
    
    if (!empty($page_gk_id)) {
        set_min_max_value_gk($page_gk_id, $product_price_meter, $product_price, $product_area, $product_rooms, $product_room_id);
    }
}
