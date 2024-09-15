<?php
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';

function get_new_complex_fields_gk($block, $name_city)
{

    $id_plan = upload_image_from_url($block->plan[0]);
    $ids_gallery = [];

    foreach ($block->renderer as $render) {
        $ids_gallery[] = upload_image_from_url($render);
    }



    $new_item = array(
        'crb_gk_name' => $block->name,
        'crb_gk_plan' => [$id_plan],
        'crb_gk_gallery' => $ids_gallery,
        'crb_gk_description' => $block->description,
        'crb_gk_city' => $name_city,
        'crb_gk_address' => $block->address[0],
        'crb_gk_latitude' => $block->geometry->coordinates[0],
        'crb_gk_longitude' => $block->geometry->coordinates[1],
    );

    return $new_item;
}
