<?php

require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/create_title_post.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/categories_name.php';
require_once get_template_directory() . '/inc/enums/rooms_id.php';

function create_post($data, $region_category_id)
{
    try {
        $product_id = $data->id;
        $product_rooms = $data->product_rooms;
        $product_room_id = $data->product_room_id;
        $product_area = $data->product_area;
        $product_area_kitchen = $data->product_area_kitchen;
        $product_area_rooms_total = $data->product_area_rooms_total;
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
        $product_apartament_number = $data->product_apartament_number;
        $product_apartamens_wc = $data->product_apartamens_wc;
        $product_height = $data->product_height;

        $product_agent_url = 'https://2bishop.ru/files/avatars/agph_23286_5jpeg.jpg';
        $product_agent_phone = carbon_get_theme_option('crb_phone_link');

        $date_build = '';

        if (!empty($product_year_build)) {
            $date = new DateTime($product_year_build);
            $date_build = $date->format("Y");
        }

        $ids_product_gallery = [];

        foreach ($product_gallery as $image) {
            $attachment_id = @upload_image_from_url($image);
            if (!is_wp_error($attachment_id)) {
                $ids_product_gallery[] = $attachment_id;
            } else {
                $error_message = $attachment_id->get_error_message();
                get_message_server_telegram('Ошибка загрузки картинки план' . $product_id, $error_message);
            }

            if (!$attachment_id) {
                get_message_server_telegram('Ошибка ожидания загрузки картинки' . $product_id, 'Тайминг');
            }
        }

        $id_image_agent = @upload_image_from_url($product_agent_url);
        if (is_wp_error($id_image_agent) || !$id_image_agent) {
            $id_image_agent = '';
        }


        $id_gk_category = create_category($product_gk, get_transliterate($product_gk), CATEGORIES_ID::GK);
        $id_city_category = create_category($product_city, get_transliterate($product_city), $region_category_id);
        $id_rooms_category = create_category(intval($product_rooms) ? intval($product_rooms) : $product_rooms, intval($product_rooms) ? 'rooms_' . intval($product_rooms) : get_transliterate($product_rooms), CATEGORIES_ID::ROOMS);
        $id_area_category = create_category(ceil($product_area), 'area_' . ceil($product_area), CATEGORIES_ID::AREA);

        $title = create_title_post($product_room_id, $product_area, $product_stage);
        $post_slug = $product_id;

        $post_id = wp_insert_post(array(
            'post_title'   => $title . ' ' . $product_id,
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_name'     => $post_slug,
            'post_category' => [
                CATEGORIES_ID::REGIONS,
                CATEGORIES_ID::GK,
                CATEGORIES_ID::ROOMS,
                CATEGORIES_ID::AREA,
                $region_category_id,
                $id_city_category,
                $id_gk_category,
                $id_rooms_category,
                $id_area_category
            ]
        ));

        if (!is_wp_error($post_id)) {
            carbon_set_post_meta($post_id, 'product-id', $product_id);
            carbon_set_post_meta($post_id, 'product-title', $title);
            carbon_set_post_meta($post_id, 'product-gallery', $ids_product_gallery);
            carbon_set_post_meta($post_id, 'product-price', $product_price);
            carbon_set_post_meta($post_id, 'product-price-meter',  $product_price_meter);
            carbon_set_post_meta($post_id, 'product-rooms', intval($product_rooms) ? intval($product_rooms) : $product_rooms);
            carbon_set_post_meta($post_id, 'product-area',  $product_area);
            carbon_set_post_meta($post_id, 'product-area-kitchen', $product_area_kitchen);
            carbon_set_post_meta($post_id, 'product-area-total-rooms', $product_area_rooms_total);
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
            carbon_set_post_meta($post_id, 'product-apartamens-number', $product_apartament_number);
            carbon_set_post_meta($post_id, 'product-apartamens-wc', $product_apartamens_wc);
            carbon_get_post_meta($post_id, 'product_height', $product_height);

            carbon_set_post_meta($post_id, 'product-agent-phone', $product_agent_phone);
            carbon_set_post_meta($post_id, 'product-agent-name', 'Арсен');
            carbon_set_post_meta($post_id, 'product-agent-photo', [$id_image_agent]);

            if ($product_rooms === CATEGORIES_NAME::STUDIO) {
                carbon_set_post_meta($post_id, 'product_type_aparts', 'yes');
            }
        } else {
            echo 'Ошибка при создании поста: ' . $post_id->get_error_message();
        }
    } catch (Exception $e) {
        get_message_server_telegram('Ошибка при создании поста из catch: ' . $data->id);
        return new WP_Error('create post', 'Ошибка при создании поста: ' . $e->getMessage());
    }
}
