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

    $min_price = 0;
    $min_price_metr = 0;
    $max_price = 0;
    $max_price_metr = 0;
    $min_area = 0;
    $max_area = 0;
    $min_room = 0;
    $max_room = 0;
    $is_studio = false;
    $rooms = [];
  
    foreach ($post_map_categories as $key => $ids_post_categories) {
        if (in_array($id_category_gk, $ids_post_categories)) {           
            $id_post = $key;
            $price_metr = carbon_get_post_meta($id_post, 'product-price-meter');
            $price       = carbon_get_post_meta($id_post, 'product-price');
            $area        = carbon_get_post_meta($id_post, 'product-area-total-rooms');
            $room        = carbon_get_post_meta($id_post, 'product-rooms');
            $studio      = carbon_get_post_meta($id_post, 'product_type_aparts');

            if (empty($min_price_metr) || intval($min_price_metr) > intval($price_metr)) {
                $min_price_metr = $price_metr;
            }
            if (empty($max_price_metr) || intval($max_price_metr) < intval($price_metr)) {
                $max_price_metr = $price_metr;
            }
            if (empty($min_price) || intval($min_price) > intval($price)) {
                $min_price = $price;
            }
            if (empty($max_price) || intval($max_price) < intval($price)) {
                $max_price = $price;
            }
            if (empty($min_area) || intval($min_area) > intval($area)) {
                $min_area = $area;
            }
            if (empty($max_area) || intval($max_area) < intval($area)) {
                $max_area = $area;
            }
            if (empty($min_room) || intval($min_room) > intval($room)) {
                $min_room = $room;
            }
            if (empty($max_room) || intval($max_room) < intval($room)) {
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
    $data->min_price_metr = $min_price_metr;
    $data->max_price = $max_price;
    $data->max_price_metr = $max_price_metr;
    $data->min_area = $min_area;
    $data->max_area = $max_area;
    $data->min_room = $min_room;
    $data->max_room = $max_room;
    $data->rooms = $rooms;
    $data->is_studio = $is_studio;

    set_min_max_value_gk($id_page_gk, $data);
}
