<?php
function set_min_max_value_gk($page_gk_id, $params)
{
    if (!empty($page_gk_id)) {   
        $min_price_gk = $params->min_price;
        $min_price_gk_metr = $params->min_price_metr;
        $max_price_gk =  $params->max_price;
        $max_price_gk_metr = $params->max_price_metr;

        $min_area_gk =  $params->min_area;
        $max_area_gk = $params->max_area;

        $min_rooms_gk = $params->min_room;
        $max_rooms_gk = $params->max_room;
        $rooms_gk = $params->rooms;
        $is_studio = $params->is_studio;

        if (!empty($min_price_gk)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_price', $min_price_gk);
        }
        if (!empty($min_price_gk_metr)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_price_meter', $min_price_gk_metr);
        }
        if (!empty($max_price_gk)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_price', $max_price_gk);
        }
        if (!empty($max_price_gk_metr)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_price_meter', $max_price_gk_metr);
        }
        if (!empty($min_area_gk)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_area', $min_area_gk);
        }
        if (!empty($max_area_gk)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_area', $max_area_gk);
        }
        if (!empty($min_rooms_gk)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_min_rooms', intval($min_rooms_gk));
        }
        if (!empty($max_rooms_gk)) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_max_rooms', intval($max_rooms_gk));
        }

        if (!empty($rooms_gk)) {
            $rooms_gk_string = implode(',', $rooms_gk);
            carbon_set_post_meta($page_gk_id, 'crb_gk_rooms', $rooms_gk_string);
        }

        if ($is_studio) {
            carbon_set_post_meta($page_gk_id, 'crb_gk_is_studio', 'yes');
        }
    }
}
