<?php

require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/get_slug_page.php';

function get_card_gk_single()
{
    $id_page_gk = $_POST['id_page_gk'];
    $slug_page = $_POST['slug_page'];

    $id_category_gk = get_term_by('slug', $slug_page, 'category')->term_id;

    $args_gk = [
        'post_type' => 'post', // Тип поста (может быть 'post', 'page', 'custom-post-type' и т.д.)
        'posts_per_page' => -1, // Количество постов на странице (-1 для вывода всех постов)
        'category__and' => [$id_category_gk],
    ];



    $query = new WP_Query($args_gk);

    $price_all = [];
    $price_meter_all = [];
    $finishing = [];
    $literal = [];
    $categories_area = [];
    $categories_rooms = [];
    $map_apartaments = [];

    if ($query->have_posts()) {

        while ($query->have_posts()) {
            $query->the_post();
            $id_post = get_the_ID();
            $price = carbon_get_post_meta($id_post, 'product-price');
            $price_meter = carbon_get_post_meta($id_post, 'product-price-meter');
            $apartament_finishing = carbon_get_post_meta($id_post, 'product-finishing');
            $liter = carbon_get_post_meta($id_post, 'product-builder-liter');
            $categories = get_the_category($id_post);

            if (!isset($map_apartaments[$liter])) {
                $map_apartaments[$liter] = [];
            }

            $map_apartaments[$liter][] = $id_post;

            foreach ($categories as $category) {
                if ($category->parent == CATEGORIES_ID::AREA && !in_array($category->term_id, array_column($categories_area, 'term_id'))) { // Проверяем, является ли родительская категория 
                    $categories_area[] = $category;
                }
                if ($category->parent == CATEGORIES_ID::ROOMS && !in_array($category->term_id, array_column($categories_rooms, 'term_id'))) {
                    $categories_rooms[] = $category;
                }
            }

            if (!in_array($apartament_finishing, $finishing)) {
                $finishing[] = $apartament_finishing;
            }
            if (!in_array($liter, $literal)) {
                $literal[] = $liter;
            }

            usort($categories_area, function ($a, $b) {
                return strcmp(intval($a->name), intval($b->name));
            });

            usort($categories_rooms, function ($a, $b) {
                return strcmp(intval($a->name), intval($b->name));
            });

            usort($literal, function ($a, $b) {
                return strcmp(intval($a), intval($b));
            });

            $price_all[] = $price;
            $price_meter_all[] = $price_meter;
        }

        wp_reset_postdata();
    }

    $params_page_gk = [
        'crb_gk_name' => carbon_get_post_meta($id_page_gk, 'crb_gk_name'),
        'crb_gk_plan' => carbon_get_post_meta($id_page_gk, 'crb_gk_plan'),
        'crb_gk_gallery' => carbon_get_post_meta($id_page_gk, 'crb_gk_gallery'),
        'crb_gk_description' =>  carbon_get_post_meta($id_page_gk, 'crb_gk_description'),
        'crb_gk_city' =>  carbon_get_post_meta($id_page_gk, 'crb_gk_city'),
        'crb_gk_address' => carbon_get_post_meta($id_page_gk, 'crb_gk_address'),
        'crb_gk_latitude' =>  carbon_get_post_meta($id_page_gk, 'crb_gk_latitude'),
        'crb_gk_longitude' =>  carbon_get_post_meta($id_page_gk, 'crb_gk_longitude'),
        'update_page' => get_the_modified_date('d-m-Y', $id_page_gk),
        'min_price' => $price_all ? min($price_all) : '',
        'min_price_meter' => $price_meter_all ? min($price_meter_all) : '',
        'finishing' => $finishing,
        'literal' => $literal,
        'categories_area' => $categories_area,
        'categories_rooms' => $categories_rooms,
        'map_apartaments' => $map_apartaments,
    ];



    ob_start();

    get_template_part('template-page/components/card_gk_single', null, $params_page_gk);

    $page_gk = ob_get_clean();

    wp_reset_postdata();

    $response = array(
        'pageGk' => $page_gk,
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_get_card_gk_single', 'get_card_gk_single');
add_action('wp_ajax_nopriv_get_card_gk_single', 'get_card_gk_single');
