<?php
require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/get_slug_page.php';
require_once get_template_directory() . '/inc/lib/set_min_max_value_gk.php';

function set_value_gk($id_page_gk, $post_map_categories)
{
    $post = get_post($id_page_gk);
    $slug_page = $post ? $post->post_name : '';

    $id_category_gk = get_term_by('slug', $slug_page, 'category')->term_id;   

    $min_price = '';
    $min_price_meter = '';
    $max_price = '';
    $max_price_meter = '';
    $min_area = '';
    $max_area = '';
    $min_room = '';
    $max_room = '';
    $is_studio = false;
    $rooms = [];    

    foreach ($post_map_categories as $key => $ids_post_categories) {
        if (in_array($id_category_gk, $ids_post_categories)) {
            $id_post = $key;
            $price_meter = carbon_get_post_meta($id_post, 'product-price-meter');
            $price       = carbon_get_post_meta($id_post, 'product-price');
            $area        = carbon_get_post_meta($id_post, 'product-area-total-rooms');
            $room        = carbon_get_post_meta($id_post, 'product-rooms');
            $studio      = carbon_get_post_meta($id_post, 'product_type_aparts');

            if (empty($price_meter) || intval($min_price_meter) > intval($price_meter)) {
                $min_price_meter = $price_meter;
            }
            if (empty($price_meter) || intval($max_price_meter) < intval($price_meter)) {
                $max_price_meter = $price_meter;
            }
            if (empty($price) || intval($min_price) > intval($price)) {
                $min_price = $price;
            }
            if (empty($price_meter) || intval($max_price) < intval($price)) {
                $max_price = $price;
            }
            if (empty($area) || intval($min_area) > intval($area)) {
                $min_area = $area;
            }
            if (empty($area) || intval($max_area) < intval($area)) {
                $max_area = $area;
            }
            if (empty($room) || intval($min_room) > intval($room)) {
                $min_area = $room;
            }
            if (empty($room) || intval($max_room) < intval($room)) {
                $max_room = $room;
            }

            if (!in_array($room, $rooms)) {
                $rooms[] = $room;
            }

            if ($studio && !$is_studio) {
                $is_studio = true;
            }
        }
    }

    if ($is_studio) {
        carbon_set_post_meta($id_page_gk, 'crb_gk_is_studio', 'yes');
    }

    $data = new stdClass();

    $data->min_price = $min_price;
    $data->min_price_meter = $min_price_meter;
    $data->max_price = $max_price;
    $data->max_price_meter = $max_price_meter;
    $data->min_area = $min_area;
    $data->max_area = $max_area;
    $data->min_room = $min_room;
    $data->max_room = $max_room;
    $data->rooms = $rooms;
    $data->is_studio = $is_studio;

    set_min_max_value_gk($id_page_gk, $data);
}
