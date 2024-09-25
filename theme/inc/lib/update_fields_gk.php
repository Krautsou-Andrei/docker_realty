<?php
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';

function update_fields_gk($post_id, $block, $name_city)
{

    $id_plan = upload_image_from_url($block->plan[0]);
    $ids_gallery = [];

    foreach ($block->renderer as $render) {
        $ids_gallery[] = upload_image_from_url($render);
    }

    carbon_set_post_meta($post_id, 'crb_gk_id', $block->_id);
    carbon_set_post_meta($post_id, 'crb_gk_name', $block->name);
    carbon_set_post_meta($post_id, 'crb_gk_plan', [$id_plan]);
    carbon_set_post_meta($post_id, 'crb_gk_gallery', $ids_gallery);
    carbon_set_post_meta($post_id, 'crb_gk_description', $block->description,);
    carbon_set_post_meta($post_id, 'crb_gk_city', $name_city);
    carbon_set_post_meta($post_id, 'crb_gk_address', $block->address[0]);
    carbon_set_post_meta($post_id, 'crb_gk_latitude',  $block->geometry->coordinates[0]);
    carbon_set_post_meta($post_id, 'crb_gk_longitude', $block->geometry->coordinates[1]);
}
