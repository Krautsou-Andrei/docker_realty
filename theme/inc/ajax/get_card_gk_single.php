<?php

require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/categories_name.php';
require_once get_template_directory() . '/inc/lib/get_slug_page.php';

function get_card_gk_single()
{
    $id_page_gk = $_POST['id_page_gk'];
    $slug_page = $_POST['slug_page'];

    $id_category_gk = get_term_by('slug', $slug_page, 'category')->term_id;

    $args_gk = [
        'post_type' => 'post', // Тип поста (может быть 'post', 'page', 'custom-post-type' и т.д.)
        'posts_per_page' => -1, // Количество постов на странице (-1 для вывода всех постов)
        'fields'        => 'ids',
        'category__and' => [$id_category_gk],
    ];

    $query = get_posts($args_gk);

    $price_all = [];
    $price_meter_all = [];
    $finishing = [];
    $literal = [];
    $map_apartaments = [];
    $map_houses = [];

    foreach ($query as $id_post) {
        $price = carbon_get_post_meta($id_post, 'product-price');
        $price_meter = carbon_get_post_meta($id_post, 'product-price-meter');
        $apartament_finishing = carbon_get_post_meta($id_post, 'product-finishing');
        $liter = carbon_get_post_meta($id_post, 'product-builder-liter');
        $categories = get_the_category($id_post);
        $floor = carbon_get_post_meta($id_post, 'product-stage');
        $rooms = carbon_get_post_meta($id_post, 'product-rooms');
        $area = carbon_get_post_meta($id_post, 'product-area');

        $apartament = [];
        $apartament['id_post'] = $id_post;
        $apartament['rooms'] = $rooms;
        $apartament['area'] = $area;

        if (!isset($map_apartaments[$liter]['floors'])) {
            $map_apartaments[$liter]['floors'] = [];
        }
        if (!isset($map_apartaments[$liter]['area'])) {
            $map_apartaments[$liter]['area'] = [];
        }
        if (!isset($map_apartaments[$liter]['rooms'])) {
            $map_apartaments[$liter]['rooms'] = [];
        }

        $map_apartaments[$liter]['floors'][$floor][] = $apartament;

        foreach ($categories as $category) {
            if ($category->parent == CATEGORIES_ID::AREA && !in_array($category->term_id, array_column($map_apartaments[$liter]['area'], 'term_id'))) {
                $map_apartaments[$liter]['area'][] = (array) $category;
            }
            if ($category->parent == CATEGORIES_ID::ROOMS && !in_array($category->term_id, array_column($map_apartaments[$liter]['rooms'], 'term_id'))) {
                $map_apartaments[$liter]['rooms'][] = (array) $category;
            }
            if ($category->parent == CATEGORIES_ID::ROOMS && ($category->name == CATEGORIES_NAME::COTTADGE || $category->name == CATEGORIES_NAME::TON_HOUSE)) {
                $map_houses[] = [
                    'post_id' => $id_post,
                    'image' => carbon_get_post_meta($id_post, 'product-gallery')[0],
                    'area' => $area,
                    'price' => $price
                ];
            }
        }

        if (!in_array($apartament_finishing, $finishing)) {
            $finishing[] = $apartament_finishing;
        }
        if (!in_array($liter, $literal)) {
            $literal[] = $liter;
        }

        $price_all[] = $price;
        $price_meter_all[] = $price_meter;
    }

    foreach ($map_apartaments as $liter => &$data) {
        usort($data['area'], fn($a, $b) => intval($a['name']) - intval($b['name']));
        usort($data['rooms'], fn($a, $b) => strcmp(intval($a['name']), intval($b['name'])));
        krsort($data['floors']);
    }
    unset($data);     

    usort($literal, function ($a, $b) {
        return strcmp(intval($a), intval($b));
    });    

    $params_table = [
        'id_page_gk' => $id_page_gk,
        'literal' => $literal,
        'map_apartaments' => $map_apartaments,
        'map_houses' => $map_houses,
        'crb_gk_plan' => carbon_get_post_meta($id_page_gk, 'crb_gk_plan'),

    ];

    $params_agent_info = [
        'update_page' => get_the_modified_date('d-m-Y', $id_page_gk),
        'min_price' => $price_all ? min($price_all) : '',
        'min_price_meter' => $price_meter_all ? min($price_meter_all) : '',
        'finishing' => $finishing,
    ];

    ob_start();
    get_template_part('template-page/components/gk_table', null, $params_table);
    $page_gk = ob_get_clean();

    ob_start();
    get_template_part('template-page/blocks/card_agent_info', null, $params_agent_info);
    $agent_info = ob_get_clean();
    wp_reset_postdata();

    $response = array(
        'pageGk' => $page_gk,
        'agentInfo' => $agent_info,
        'paramsTable' => json_encode($params_table),
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_get_card_gk_single', 'get_card_gk_single');
add_action('wp_ajax_nopriv_get_card_gk_single', 'get_card_gk_single');
