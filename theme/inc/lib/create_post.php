<?php

require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/create_title_post.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/update_fields_apartaments.php';
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

    $id_city_category = create_category($product_city, get_transliterate($product_city), CATEGORIES_ID::CITIES);
    $id_gk_category = create_category($product_gk, get_transliterate($product_gk), CATEGORIES_ID::GK);
    $id_rooms_category = create_category(intval($product_rooms) ? intval($product_rooms) : $product_rooms, intval($product_rooms) ? 'rooms' . intval($product_rooms) : get_transliterate($product_rooms), CATEGORIES_ID::ROOMS);
    $id_area_category = create_category(ceil($product_area), get_transliterate(ceil($product_area)), CATEGORIES_ID::AREA);

    $title = create_title_post($product_room_id, $product_area, $product_stage);

    $args_test = [
        'post_type'      => 'post', // Укажите тип поста
        'meta_key'      => '_product-id',
        'meta_value'    => $product_id,
        'posts_per_page' => 1, // Получить только один пост
        'fields'         => 'ids' // Вернуть только ID поста
    ];

    $existing_posts = get_posts($args_test);

    if ($existing_posts) {
        $post_id = $existing_posts[0]; // Получаем ID существующего поста
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
            update_fields_apartaments($post_id, $data);
        } else {
            // Вывод сообщения об ошибке
            echo 'Ошибка при создании поста: ' . $post_id->get_error_message();
        }
    }
}
