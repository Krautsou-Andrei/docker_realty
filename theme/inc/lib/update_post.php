<?php
require_once get_template_directory() . '/inc/lib/create_title_post.php';

function update_post($data, $post_id)
{
    $product_id = $data->id;
    $product_room_id = $data->product_room_id;
    $product_area = $data->product_area;
    $product_stage = $data->product_stage;

    $product_price = $data->product_price;
    $product_price_meter = $data->product_price_meter;
    $product_year_build = $data->product_year_build;
    $product_finishing = $data->product_finishing;
    $product_block_id = $data->block_id;

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }

    $title = create_title_post($product_room_id, $product_area, $product_stage);
    $post_slug = $product_id;

    carbon_set_post_meta($post_id, 'product-price', $product_price);
    carbon_set_post_meta($post_id, 'product-price-meter',  $product_price_meter);
    carbon_set_post_meta($post_id, 'product-year-build', $date_build);
    carbon_set_post_meta($post_id, 'product-finishing', $product_finishing);

    $updated_post = array(
        'ID'         => $post_id,
        'post_title' => $title . ' ' . $product_id,
        'post_name'     => $post_slug,
    );
    wp_update_post($updated_post);
    if (!empty($product_block_id)) {
        update_min_max_value_gk($product_block_id, $product_price_meter, $product_price);
    }
}
function update_min_max_value_gk($product_block_id, $product_price_meter, $product_price)
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
            $min_price_gk_metr = carbon_get_post_meta($page->ID, 'crb_gk_min_price_meter');
            $max_price_gk = carbon_get_post_meta($page->ID, 'crb_gk_max_price');
            $max_price_gk_meter = carbon_get_post_meta($page->ID, 'crb_gk_max_price_meter');


            if (empty($min_price_gk) || intval($min_price_gk) > intval($product_price)) {
                carbon_set_post_meta($page->ID, 'crb_gk_min_price', $product_price);
            }
            if (empty($min_price_gk_metr) || intval($min_price_gk_metr) > intval($product_price_meter)) {
                carbon_set_post_meta($page->ID, 'crb_gk_min_price_meter', $product_price_meter);
            }

            if (empty($max_price_gk) || intval($max_price_gk) < intval($product_price)) {
                carbon_set_post_meta($page->ID, 'crb_gk_max_price', $product_price);
            }
            if (empty($max_price_gk_meter) || intval($max_price_gk_meter) > intval($product_price_meter)) {
                carbon_set_post_meta($page->ID, 'crb_gk_max_price_meter', $product_price_meter);
            }
        }
    }
}
