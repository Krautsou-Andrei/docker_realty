<?php
function update_post($data, $post_id)
{
    date_default_timezone_set('Europe/Moscow');  

    $product_price = $data->product_price;
    $product_price_meter = $data->product_price_meter;
    $product_year_build = $data->product_year_build;
    $product_finishing = $data->product_finishing;

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }  

    carbon_set_post_meta($post_id, 'product-price', $product_price);
    carbon_set_post_meta($post_id, 'product-price-meter',  $product_price_meter);
    carbon_set_post_meta($post_id, 'product-year-build', $date_build);
    carbon_set_post_meta($post_id, 'product-finishing', $product_finishing);
    update_post_meta($post_id, 'post_modified', current_time('mysql'));
    update_post_meta($post_id, 'post_modified_gmt', current_time('mysql', 1));
    
}
