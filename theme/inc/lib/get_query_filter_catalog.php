<?php
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/template_name.php';
require_once get_template_directory() . '/inc/lib/sort_gk.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';

function get_query_filter_catalog($paged, $region = '', $city = '', $type_build = '', $rooms = '', $selectPrice = '', $selectArea = '')
{

    global  $names_default_cities;

    $filter_region = isset($_GET['region']) ? $_GET['region'] : DEFAULT_ENUM::DEFAULT_FILTER_REGION;

    $dafault_city = $names_default_cities[$filter_region] ?? DEFAULT_ENUM::DEFAULT_FILTER_CITY;
    $filter_city = isset($_GET['city']) ? $_GET['city'] : $dafault_city;

    if (!empty($city)) {
        $filter_city = $city;
    }

    if (!empty($region)) {
        $filter_region = $region;
    }

    $filter_type_build = isset($_GET['type-build']) ? $_GET['type-build'] : DEFAULT_ENUM::DEFAULT_FILTER_APARTAMENTS;

    if (!empty($type_build)) {
        $filter_type_build = $type_build;
    }

    $filter_price = isset($_GET['select_price']) ?  explode('-', $_GET['select_price']) : [];
    if (!empty($selectPrice)) {
        $filter_price =  explode('-', $selectPrice);
    }
    $filter_area = isset($_GET['select_area']) ? explode('-', $_GET['select_area']) : [];
    if (!empty($selectArea)) {
        $filter_area =  explode('-', $selectArea);
    }

    $filter_price_ot = isset($filter_price[0]) ? $filter_price[0] : '';
    $filter_price_do = isset($filter_price[1]) ? $filter_price[1] : '';

    $filter_area_ot = isset($filter_area[0]) ? $filter_area[0] : '';
    $filter_area_do = isset($filter_area[1]) ? $filter_area[1] : '';

    $filter_check_price = isset($_GET['check_price']) ? $_GET['check_price'] : '';

    $filter_rooms_array = isset($_GET['rooms']) ? explode(',', $_GET['rooms']) : [];

    if (!empty($rooms)) {
        $filter_rooms_array = explode(',', $rooms);
    }

    $rooms_query = [];

    foreach ($filter_rooms_array as $room) {
        if (intval($room)) {
            $rooms_query[] = intval($room);
        } else {
            $rooms_query[] = $room;
        }
    }

    $id_page_region = search_id_page_by_name($filter_region, CATEGORIES_ID::PAGE_NEW_BUILDINGS);

    $id_page_city = search_id_page_by_name($filter_city, $id_page_region) ?? 1;

    $page_ids = sort_gk($filter_city);

    $args = array(
        'post_type'      => 'page', // Тип поста
        'posts_per_page' => 9, // Количество постов на странице
        'paged'          => $paged,
        'post_status'    => 'publish',
        'post_parent'    => $id_page_city, // Указываем родительскую категорию
        'meta_key'      => '_wp_page_template', // Мета-ключ для шаблона
        'meta_value'    => TEMPLATE_NAME::PAGE_GK, // Имя шаблона   
        'post__in'       => $page_ids, // Фильтруем по ID страниц
        'orderby'        => 'post__in', // Сохраняем порядок из массива
        'meta_query'     => array(
            'relation' => 'AND', // Указываем, что условия должны выполняться одновременно
        ),
    );

    $is_view_all_page = carbon_get_post_meta(CATEGORIES_ID::PAGE_NEW_BUILDINGS, 'crb_gk_is_not_all_view');

    if ($is_view_all_page) {
        $args['meta_query'][] = [
            'key'     => 'crb_gk_min_price_meter',
            'value'   => '',
            'compare' => '!='
        ];
    }

    $args['meta_query'][] = [
        'key'     => 'crb_gk_is_not_view',
        'value'   => '',
        'compare' => '='
    ];


    if ($filter_type_build !== '') {
        $args['meta_query'][] = array(
            'key'     => 'crb_gk_is_house',
            'value'   => $filter_type_build == DEFAULT_ENUM::DEFAULT_FILTER_APARTAMENTS ? '' : 'yes',
            'compare' => '=',
        );
    }

    if ($filter_price_ot !== '') {

        $args['meta_query'][] = array(
            'key'     => !empty($filter_check_price) ? 'crb_gk_min_price' : 'crb_gk_min_price_meter',
            'value'   => $filter_price_ot == 0 ? 1 : $filter_price_ot,
            'compare' => '>=',
            'type'    => 'NUMERIC'
        );
    }

    if ($filter_price_do !== '') {
        $args['meta_query'][] = array(
            'key'     => !empty($filter_check_price) ? 'crb_gk_min_price' : 'crb_gk_min_price_meter',
            'value'   => $filter_price_do,
            'compare' => '<=',
            'type'    => 'NUMERIC'
        );
    }

    if ($filter_area_ot !== '') {
        $args['meta_query'][] = array(
            'key'     => 'crb_gk_min_area',
            'value'   => $filter_area_ot == 0 ? 1 : $filter_area_ot,
            'compare' => '>=',
            'type'    => 'NUMERIC'
        );
    }

    if ($filter_area_do !== '') {
        $args['meta_query'][] = array(
            'key'     => 'crb_gk_max_area',
            'value'   => $filter_area_do,
            'compare' => '<=',
            'type'    => 'NUMERIC'
        );
    }

    if (!empty($rooms_query)) {
        $meta_query = array('relation' => 'OR');

        foreach ($rooms_query as $value) {
            $meta_query[] = array(
                'key'     => 'crb_gk_rooms',
                'value'   => trim($value),
                'compare' => 'LIKE',
            );
        }

        $args['meta_query'][] = $meta_query;
    }

    $query = new WP_Query($args);

    return $query;
}
