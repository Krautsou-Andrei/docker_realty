<?php
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/template_name.php';
require_once get_template_directory() . '/inc/lib/sort_gk.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';

function get_query_filter_catalog($paged, $city = '')
{

    $filter_city = isset($_GET['city']) ? $_GET['city'] : 'Новороссийск';

    if (!empty($city)) {
        $filter_city = $city;
    }

    $filter_type_build = isset($_GET['type-build']) ? $_GET['type-build'] : 'Квартиры';

    $filter_price = isset($_GET['select_price']) ?  explode('-', $_GET['select_price']) : [];
    $filter_area = isset($_GET['select_area']) ? explode('-', $_GET['select_area']) : [];


    $filter_price_ot = isset($filter_price[0]) ? $filter_price[0] : '';
    $filter_price_do = isset($filter_price[1]) ? $filter_price[1] : '';

    $filter_area_ot = isset($filter_area[0]) ? $filter_area[0] : '';
    $filter_area_do = isset($filter_area[1]) ? $filter_area[1] : '';

    $filter_check_price = isset($_GET['check_price']) ? $_GET['check_price'] : '';

    $filter_rooms_array = isset($_GET['rooms']) ? explode(',', $_GET['rooms']) : [];

    $rooms_query = [];

    foreach ($filter_rooms_array as $room) {
        if (intval($room)) {
            $rooms_query[] = intval($room);
        } else {
            $rooms_query[] = $room;
        }
    }

    $id_page = search_id_page_by_name(CATEGORIES_ID::PAGE_NEW_BUILDINGS, $filter_city) ?? 1;

    $page_ids = sort_gk($filter_city);

    $args = array(
        'post_type'      => 'page', // Тип поста
        'posts_per_page' => 9, // Количество постов на странице
        'paged'          => $paged,
        'post_status'    => 'publish',
        'post_parent'    => $id_page, // Указываем родительскую категорию
        'meta_key'      => '_wp_page_template', // Мета-ключ для шаблона
        'meta_value'    => TEMPLATE_NAME::PAGE_GK, // Имя шаблона   
        'post__in'       => $page_ids, // Фильтруем по ID страниц
        'orderby'        => 'post__in', // Сохраняем порядок из массива
        'meta_query'     => array(
            'relation' => 'AND', // Указываем, что условия должны выполняться одновременно
        ),
    );

    $args['meta_query'][] = [
        'key'     => 'crb_gk_is_not_view',
        'value'   => '',
        'compare' => '='
    ];

    if ($filter_type_build !== '') {
        $args['meta_query'][] = array(
            'key'     => 'crb_gk_is_house',
            'value'   => $filter_type_build == 'Квартиры' ? '' : 'yes',
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
