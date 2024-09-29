<?php

require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/create_title_post.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';

function create_post($data)
{
    $product_id = $data->id;
    $product_rooms = $data->product_rooms;
    $product_room_id = $data->product_room_id;
    $product_area = $data->product_area;
    $product_stage = $data->product_stage;
    $product_city = $data->product_city;
    $product_gk = $data->product_gk;

    $product_gallery = $data->product_gallery;
    $product_price = $data->product_price;
    $product_price_meter = $data->product_price_meter;
    $product_stages = $data->product_stages;
    $product_year_build = $data->product_year_build;
    $product_street = $data->product_street;
    $product_latitude = $data->coordinates[1] ?? '';
    $product_longitude = $data->coordinates[0] ?? '';
    $product_building_type = $data->product_building_type;
    $product_finishing = $data->product_finishing;
    $product_building_name = $data->building_name;
    $product_block_id = $data->block_id;


    $product_agent_url = 'https://2bishop.ru/files/avatars/agph_23286_5jpeg.jpg';
    $product_agent_phone = carbon_get_theme_option('crb_phone_link');

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }

    $ids_product_gallery = [];

    foreach ($product_gallery as $image) {
        $attachment_id = upload_image_from_url($image);

        if (!is_wp_error($attachment_id)) {
            $ids_product_gallery[] = $attachment_id;
        }
    }

    $id_image_agent = upload_image_from_url($product_agent_url);


    $id_city_category = create_category($product_city, get_transliterate($product_city), CATEGORIES_ID::CITIES);
    $id_gk_category = create_category($product_gk, get_transliterate($product_gk), CATEGORIES_ID::GK);
    $id_rooms_category = create_category(intval($product_rooms) ? intval($product_rooms) : $product_rooms, intval($product_rooms) ? 'rooms_' . intval($product_rooms) : get_transliterate($product_rooms), CATEGORIES_ID::ROOMS);
    $id_area_category = create_category(ceil($product_area), 'area_' . ceil($product_area), CATEGORIES_ID::AREA);

    $title = create_title_post($product_room_id, $product_area, $product_stage);

    $args_test = [
        'post_type'      => 'post', // Укажите тип поста
        'key'      => 'product-id',
        'meta_value'    => $product_id,
        'posts_per_page' => 1, // Получить только один пост
        'fields'         => 'ids' // Вернуть только ID поста
    ];

    $existing_posts = get_posts($args_test);

    if ($existing_posts) {
        $post_id = $existing_posts[0]; // Получаем ID существующего поста
        if (!empty($product_block_id)) {
            update_min_price_gk($product_block_id, $product_price_meter);
        }
    } else {
        $post_id = wp_insert_post(array(
            'post_title'   => $title,
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_category' => [
                CATEGORIES_ID::CITIES,
                CATEGORIES_ID::GK,
                CATEGORIES_ID::ROOMS,
                CATEGORIES_ID::AREA,
                $id_city_category,
                $id_gk_category,
                $id_rooms_category,
                $id_area_category
            ]
        ));

        if (!is_wp_error($post_id)) {
            carbon_set_post_meta($post_id, 'product-id', $product_id);
            carbon_set_post_meta($post_id, 'product-gallery', [$attachment_id]);
            carbon_set_post_meta($post_id, 'product-price', $product_price);
            carbon_set_post_meta($post_id, 'product-price-meter',  $product_price_meter);
            carbon_set_post_meta($post_id, 'product-rooms', intval($product_rooms) ? intval($product_rooms) : $product_rooms);
            carbon_set_post_meta($post_id, 'product-area',  $product_area);
            carbon_set_post_meta($post_id, 'product-stage', $product_stage);
            carbon_set_post_meta($post_id, 'product-stages', $product_stages);
            carbon_set_post_meta($post_id, 'product-year-build', $date_build);
            carbon_set_post_meta($post_id, 'product-building-type', $product_building_type);
            carbon_set_post_meta($post_id, 'product-finishing', $product_finishing);
            carbon_set_post_meta($post_id, 'product-city', $product_city);
            carbon_set_post_meta($post_id, 'product-street', $product_street);
            carbon_set_post_meta($post_id, 'product-latitude', $product_latitude);
            carbon_set_post_meta($post_id, 'product-longitude', $product_longitude);
            carbon_set_post_meta($post_id, 'product-builder-liter', $product_building_name);

            carbon_set_post_meta($post_id, 'product-agent-phone', $product_agent_phone);
            carbon_set_post_meta($post_id, 'product-agent-name', 'Арсен');
            carbon_set_post_meta($post_id, 'product-agent-photo', [$id_image_agent]);

            update_min_price_gk($product_block_id, $product_price_meter);
        } else {
            // Вывод сообщения об ошибке
            echo 'Ошибка при создании поста: ' . $post_id->get_error_message();
        }
    }
}
function update_min_price_gk($product_block_id, $product_price_meter)
{
    if (!empty($product_block_id)) {
        $args_post = array(
            'post_type'      => 'page', // Тип поста
            'post_status'    => 'publish', // Только опубликованные страницы
            'key'      => 'crb_gk_id', // Мета-ключ
            'meta_value'    => $product_block_id, // Значение мета-ключа
            'posts_per_page' => 1, // Ограничиваем количество выводимых страниц
        );

        $pages = get_posts($args_post);

        if (!empty($pages)) {

            $page = $pages[0];

            $min_price_gk = carbon_get_post_meta($page->ID, 'crb_gk_min_price');

            if (empty($min_price_gk) || intval($min_price_gk) > intval($product_price_meter)) {
                carbon_set_post_meta($page->ID, 'crb_gk_min_price', $product_price_meter);
            }
        }
    }
}
