<?php

require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';

function create_post($data)
{

    $product_id = $data->id;
    $product_gallery = $data->product_gallery;
    $product_price = $data->product_price;
    $product_price_meter = $data->product_price_meter;
    $product_rooms = $data->product_rooms;
    $product_area = $data->product_area;
    $product_stage = $data->product_stage;
    $product_year_build = $data->product_year_build;
    $product_city = $data->product_city;
    $product_street = $data->product_street;
    $product_latitude = $data->coordinates[0] ?? '';
    $product_longitude = $data->coordinates[1] ?? '';
    $product_building_type = $data->product_building_type;
    $product_finishing = $data->product_finishing;

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }

    var_dump($date_build);

    $attachment_id = upload_image_from_url($product_gallery);

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }

    $post_id = wp_insert_post(array(
        'post_title'   => 'Заголовок поста 1',
        'post_content' => 'Содержимое поста',
        'post_status'  => 'publish',
        'post_type'    => 'post',
    ));

    var_dump(!is_wp_error($post_id));
    if (!is_wp_error($post_id)) {
        carbon_set_post_meta($post_id, 'product-id', $product_id);
        carbon_set_post_meta($post_id, 'product-gallery', [$attachment_id]);
        // carbon_set_post_meta($post_id, 'product-description', 'text');
        carbon_set_post_meta($post_id, 'product-price', $product_price);
        carbon_set_post_meta($post_id, 'product-price-meter',  $product_price_meter);
        carbon_set_post_meta($post_id, 'product-rooms', $product_rooms);
        carbon_set_post_meta($post_id, 'product-area',  $product_area);
        carbon_set_post_meta($post_id, 'product-stage', $product_stage);
        carbon_set_post_meta($post_id, 'product-year-build', $date_build);
        carbon_set_post_meta($post_id, 'product-building-type', $product_building_type);
        carbon_set_post_meta($post_id, 'product-finishing', $product_finishing);
        carbon_set_post_meta($post_id, 'product-city', $product_city);
        carbon_set_post_meta($post_id, 'product-street', $product_street);
        carbon_set_post_meta($post_id, 'product-latitude', $product_latitude);
        carbon_set_post_meta($post_id, 'product-longitude', $product_longitude);
    } else {
        // Вывод сообщения об ошибке
        echo 'Ошибка при создании поста: ' . $post_id->get_error_message();
    }
}
