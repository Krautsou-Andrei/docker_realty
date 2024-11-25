<?php

require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/categories_name.php';
require_once get_template_directory() . '/inc/lib/is_house.php';
require_once get_template_directory() . '/inc/lib/get_slug_page.php';

function get_card_gk_single()
{
    global $wpdb;

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
    $post_ids_placeholder = implode(',', array_fill(0, count($query), '%d'));

    $meta_query = $wpdb->prepare("
        SELECT post_id, meta_key, meta_value 
        FROM {$wpdb->postmeta} 
        WHERE post_id IN ($post_ids_placeholder) 
        AND (
        meta_key IN ('_product-price', '_product-price-meter', '_product-finishing', '_product-builder-liter', '_product-stage', '_product-rooms', '_product-area') 
        OR meta_key LIKE '_product-gallery%'
    )
    ", $query);

    $category_query = $wpdb->prepare("
        SELECT tr.object_id, tt.term_taxonomy_id 
        FROM {$wpdb->term_relationships} AS tr
        INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE tr.object_id IN ($post_ids_placeholder)
    ", $query);

    $meta_results = $wpdb->get_results($meta_query, ARRAY_A);
    $category_results = $wpdb->get_results($category_query);

    $meta_data = [];
    foreach ($meta_results as $row) {
        if (strpos($row['meta_key'], '_product-gallery') !== false) {
            $meta_data[$row['post_id']]['_product-gallery'][] = $row['meta_value'];
        } else {
            $meta_data[$row['post_id']][$row['meta_key']] = $row['meta_value'];
        }
    }

    $category_map_post_id_by_term_ids = [];
    foreach ($category_results as $result) {
        $category_map_post_id_by_term_ids[$result->object_id][] = $result->term_taxonomy_id;
    }

    $categories = wp_get_object_terms($query, 'category');
    $categories_by_post = [];
    foreach ($categories as $category) {
        $categories_by_post[$category->term_id][] = $category;
    }

    $price_all = [];
    $price_meter_all = [];
    $finishing = [];
    $literal = [];
    $map_apartaments = [];
    $map_houses = [];

    foreach ($query as $id_post) {
        $categories_ids = $category_map_post_id_by_term_ids[$id_post];
        $price = $meta_data[$id_post]['_product-price'] ?? null;
        $price_meter = $meta_data[$id_post]['_product-price-meter'] ?? null;
        $apartament_finishing = $meta_data[$id_post]['_product-finishing'] ?? null;
        $liter = $meta_data[$id_post]['_product-builder-liter'] ?? null;
        $floor = $meta_data[$id_post]['_product-stage'] ?? null;
        $rooms = $meta_data[$id_post]['_product-rooms'] ?? null;
        $area = $meta_data[$id_post]['_product-area'] ?? null;
        $gallery = $meta_data[$id_post]['_product-gallery'][0] ?? null;

        $apartament = [
            'id_post' => $id_post,
            'rooms' => $rooms,
            'area' => $area,
        ];

        if (!isset($map_apartaments[$liter])) {
            $map_apartaments[$liter] = [
                'floors' => [],
                'area' => [],
                'rooms' => [],
            ];
        }
        $map_apartaments[$liter]['floors'][$floor][] = $apartament;

        foreach ($categories_ids as $category_id) {

            $category = $categories_by_post[$category_id][0];
            if ($category->parent == CATEGORIES_ID::AREA && !in_array($category->term_id, array_column($map_apartaments[$liter]['area'], 'term_id'))) {
                $map_apartaments[$liter]['area'][] = (array) $category;
            }
            if ($category->parent == CATEGORIES_ID::ROOMS && !in_array($category->term_id, array_column($map_apartaments[$liter]['rooms'], 'term_id'))) {
                $map_apartaments[$liter]['rooms'][] = (array) $category;
            }

            if ($category->parent == CATEGORIES_ID::ROOMS && ($category->name == CATEGORIES_NAME::COTTADGE || $category->name == CATEGORIES_NAME::TON_HOUSE) || is_house($category->name)) {
                $map_houses[] = [
                    'post_id' => $id_post,
                    'image' => $gallery,
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
