<?php
require_once get_template_directory() . '/inc/lib/get_message_server.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';

function update_fields_gk($post_id, $block, $name_city)
{

    $ids_gallery_plan = [];

    if (!empty($block->plan)) {
        foreach ($block->plan as $render) {
            if (!empty($render)) {
                $attachment_id = upload_image_from_url($render);
                if (!is_wp_error($attachment_id)) {
                    $ids_gallery_plan[] = $attachment_id;
                }
            }
        }
    }
    $ids_gallery = [];

    if (!empty($block->renderer)) {
        foreach ($block->renderer as $render) {
            if (!empty($render)) {
                $attachment_id = upload_image_from_url($render);
                if (!is_wp_error($attachment_id)) {
                    $ids_gallery[] = $attachment_id;
                }
            }
            sleep(1);
        }
    }

    $description = $block->description;
    $description = preg_replace('/<a.*?>(.*?)<\/a>/', '', $description);
    $description = preg_replace('/<p.*?>(.*?)<\/p>/', '$1<br>', $description);

    carbon_set_post_meta($post_id, 'crb_gk_id', $block->_id);
    carbon_set_post_meta($post_id, 'crb_gk_name', $block->name);
    carbon_set_post_meta($post_id, 'crb_gk_plan', $ids_gallery_plan);
    carbon_set_post_meta($post_id, 'crb_gk_gallery', $ids_gallery);
    carbon_set_post_meta($post_id, 'crb_gk_description', $description);
    carbon_set_post_meta($post_id, 'crb_gk_city', $name_city);
    carbon_set_post_meta($post_id, 'crb_gk_address', !empty($block->address[0]) ? $block->address[0] : '');
    if (!empty($block->geometry->coordinates[0]) && !empty($block->geometry->coordinates[1])) {
        carbon_set_post_meta($post_id, 'crb_gk_latitude', $block->geometry->coordinates[0]);
        carbon_set_post_meta($post_id, 'crb_gk_longitude',  $block->geometry->coordinates[1]);
    }
    carbon_set_post_meta($post_id, 'crb_gk_min_price', '');
    carbon_set_post_meta($post_id, 'crb_gk_min_price_meter', '');
    carbon_set_post_meta($post_id, 'crb_gk_max_price', '');
    carbon_set_post_meta($post_id, 'crb_gk_max_price_meter', '');
    carbon_set_post_meta($post_id, 'crb_gk_min_area', '');
    carbon_set_post_meta($post_id, 'crb_gk_max_area', '');
    carbon_set_post_meta($post_id, 'crb_gk_min_rooms', '');
    carbon_set_post_meta($post_id, 'crb_gk_max_rooms', '');
    carbon_set_post_meta($post_id, 'crb_gk_is_studio', '');
    carbon_set_post_meta($post_id, 'crb_gk_is_house', '');
    carbon_set_post_meta($post_id, 'crb_gk_rooms', '');


    $crb_gk_is_not_view = carbon_get_post_meta($post_id, 'crb_gk_is_not_view', true);
    if (empty($crb_gk_is_not_view)) {
        carbon_set_post_meta($post_id, 'crb_gk_is_not_view', '');
    }

    if (strpos(mb_strtolower($block->name, 'UTF-8'), 'коттедж') !== false) {
        carbon_set_post_meta($post_id, 'crb_gk_is_house', 'yes');
    }

    $updated_page = array(
        'ID'         => $post_id,
        'post_title' => $block->name,
    );
    wp_update_post($updated_page);
}
