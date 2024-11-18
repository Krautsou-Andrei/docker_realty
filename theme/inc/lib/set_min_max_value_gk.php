<?php

require_once get_template_directory() . '/inc/enums/rooms_id.php';

function set_min_max_value_gk($page_gk_id, $product_price_meter, $product_price, $product_area, $product_rooms, $product_room_id)
{
    if (!empty($page_gk_id)) {

        $min_price_gk = carbon_get_post_meta($page_gk_id, 'crb_gk_min_price');
        $min_price_gk_metr = carbon_get_post_meta($page_gk_id, 'crb_gk_min_price_meter');
        $max_price_gk = carbon_get_post_meta($page_gk_id, 'crb_gk_max_price');
        $max_price_gk_meter = carbon_get_post_meta($page_gk_id, 'crb_gk_max_price_meter');

        $min_area_gk = carbon_get_post_meta($page_gk_id, 'crb_gk_min_area');
        $max_area_gk = carbon_get_post_meta($page_gk_id, 'crb_gk_max_area');

        $min_rooms_gk = carbon_get_post_meta($page_gk_id, 'crb_gk_min_rooms');
        $max_rooms_gk = carbon_get_post_meta($page_gk_id, 'crb_gk_max_rooms');
        $rooms_gk = !empty(carbon_get_post_meta($page_gk_id, 'crb_gk_rooms')) ? explode(',', carbon_get_post_meta($page_gk_id, 'crb_gk_rooms')) : [];

        $room = intval($product_rooms) ? intval($product_rooms) : $product_rooms;


        if (empty($min_price_gk) || intval($min_price_gk) > intval($product_price)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_price', $product_price);
        }
        if (empty($min_price_gk_metr) || intval($min_price_gk_metr) > intval($product_price_meter)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_price_meter', $product_price_meter);
        }

        if (empty($max_price_gk) || intval($max_price_gk) < intval($product_price)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_price', $product_price);
        }
        if (empty($max_price_gk_meter) || intval($max_price_gk_meter) > intval($product_price_meter)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_price_meter', $product_price_meter);
        }

        if (empty($min_area_gk) || intval($min_area_gk) > intval($product_area)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_area', $product_area);
        }
        if (empty($max_area_gk) || intval($max_area_gk) < intval($product_area)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_area', $product_area);
        }

        if (empty($min_rooms_gk) || intval($min_rooms_gk) > intval($product_rooms)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_rooms', intval($product_rooms));
        }
        if (empty($max_rooms_gk) || intval($max_rooms_gk) < intval($product_rooms)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_rooms', intval($product_rooms));
        }

        if (!in_array($room, $rooms_gk)) {
            $rooms_gk[] = $room;
            if (!empty($rooms_gk)) {
                $rooms_gk_string = implode(',', $rooms_gk);
                carbon_set_post_meta($page_gk_id, 'crb_gk_rooms', $rooms_gk_string);
            }
        }

        if ($product_room_id == ROOMS_ID::STUDIO_0 || $product_room_id == ROOMS_ID::STUDIO) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_is_studio', 'yes');
        }
        if ($product_room_id == ROOMS_ID::COTTADGE || $product_room_id == ROOMS_ID::TON_HOUSE) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_is_house', 'yes');
        }
    }
}
