<?php

require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/create_title_post.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';

function create_post($data)
{
    prettyVarDump($data);
    $product_id = $data->id;
    $product_gallery = $data->product_gallery;
    $product_price = $data->product_price;
    $product_price_meter = $data->product_price_meter;
    $product_rooms = $data->product_rooms;
    $product_room_id = $data->product_room_id;
    $product_area = $data->product_area;
    $product_stage = $data->product_stage;
    $product_year_build = $data->product_year_build;
    $product_city = $data->product_city;
    $product_gk = $data->product_gk;
    $product_street = $data->product_street;
    $product_latitude = $data->coordinates[0] ?? '';
    $product_longitude = $data->coordinates[1] ?? '';
    $product_building_type = $data->product_building_type;
    $product_finishing = $data->product_finishing;
    $product_building_name = $data->building_name;

    // prettyVarDump($data);

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

    $id_city_category = create_category($product_city, get_transliterate($product_city), CATEGORIES_ID::CITIES);
    $id_gk_category = create_category($product_gk, get_transliterate($product_gk), CATEGORIES_ID::GK);
    $id_rooms_category = create_category($product_rooms, get_transliterate($product_rooms), CATEGORIES_ID::ROOMS);
    $id_area_category = create_category(ceil($product_area), get_transliterate(ceil($product_area)), CATEGORIES_ID::AREA);

    $post_id = wp_insert_post(array(
        'post_title'   => create_title_post($product_room_id, $product_area, $product_stage),
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_category' => [CATEGORIES_ID::CITIES, CATEGORIES_ID::GK, CATEGORIES_ID::ROOMS, CATEGORIES_ID::AREA, $id_city_category, $id_gk_category, $id_rooms_category, $id_area_category]
    ));

    var_dump(!is_wp_error($post_id));
    if (!is_wp_error($post_id)) {
        carbon_set_post_meta($post_id, 'product-id', $product_id);
        carbon_set_post_meta($post_id, 'product-gallery', [$attachment_id]);
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
        carbon_set_post_meta($post_id, 'product-builder-liter', $product_building_name);
    } else {
        // Вывод сообщения об ошибке
        echo 'Ошибка при создании поста: ' . $post_id->get_error_message();
    }
}
