<?php
require_once get_template_directory() . '/inc/lib/get_names_children_categories.php';
require_once get_template_directory() . '/inc/lib/search_id_category_by_name.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';
require_once get_template_directory() . '/inc/enums/categories_name.php';
require_once get_template_directory() . '/inc/maps/map_cities.php';

function get_params_filter_catalog()
{
    global $MAP_CITIES;

    $filter_city = isset($_GET['city']) ? $_GET['city'] : '2306';
    $search_param_city = isset($MAP_CITIES[$filter_city]) ? $MAP_CITIES[$filter_city] : 'Новороссийск';

    $filter_type_build = isset($_GET['type-build']) ? $_GET['type-build'] : 'Квартиры';

    $filter_rooms = isset($_GET['rooms']) ? $_GET['rooms'] : '';
    $filter_rooms_array = isset($_GET['rooms']) ? explode(',', $_GET['rooms']) : [];

    $filter_price_array = isset($_GET['select_price']) ?  explode('-', $_GET['select_price']) : [];
    $filter_price = isset($_GET['select_price']) ?   $_GET['select_price'] : '';
    $filter_price_ot = isset($filter_price_array[0]) ? $filter_price_array[0] : '';
    $filter_price_do = isset($filter_price_array[1]) ? $filter_price_array[1] : '';

    $filter_check_price = isset($_GET['check_price']) ? $_GET['check_price'] : '';

    $filter_area_array = isset($_GET['select_area']) ? explode('-', $_GET['select_area']) : [];
    $filter_area = isset($_GET['select_area']) ? $_GET['select_area'] : '';
    $filter_area_ot = isset($filter_area_array[0]) ? $filter_area_array[0] : '';
    $filter_area_do = isset($filter_area_array[1]) ? $filter_area_array[1] : '';


    // $cities_parent_category_id  = search_id_category_by_name(CATEGORIES_NAME::CITIES);
    // $cities_names = !empty($cities_parent_category_id) ? get_names_children_categories($cities_parent_category_id) : [];

    $rooms_parent_category_id = search_id_category_by_name(CATEGORIES_NAME::ROOMS);
    $rooms_names = !empty($rooms_parent_category_id) ? get_names_children_categories($rooms_parent_category_id) : [];

    $args_categories_area = array(
        'hide_empty' => false,
        'parent' => CATEGORIES_ID::AREA,
    );

    $categories_area = get_categories($args_categories_area);
    $max_area = 0;

    foreach ($categories_area as $area) {
        if (intval($area->name) > $max_area) {
            $max_area = intval($area->name);
        }
    }

    $id_page_city = search_id_page_by_name(CATEGORIES_ID::PAGE_NEW_BUILDINGS, $search_param_city);

    $args_gk_city = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'parent' => $id_page_city,
    );

    $gk_city = get_posts($args_gk_city);
    $max_price = 0;

    foreach ($gk_city as $gk) {
        $gk_price = carbon_get_post_meta($gk->ID, 'crb_gk_max_price');

        if (intval($max_price) < $gk_price) {
            $max_price = intval($gk_price);
        }
    }

    $params = new stdClass();

    $params->filter_city = $filter_city;
    $params->search_param_city = $search_param_city;
    $params->filter_type_build = $filter_type_build;
    $params->filter_rooms = $filter_rooms;
    $params->filter_rooms_array = $filter_rooms_array;
    $params->filter_price_array = $filter_price_array;
    $params->filter_price = $filter_price;
    $params->filter_price_ot = $filter_price_ot;
    $params->filter_price_do = $filter_price_do;
    $params->filter_check_price = $filter_check_price;
    $params->filter_area_array = $filter_area_array;
    $params->filter_area = $filter_area;
    $params->filter_area_ot = $filter_area_ot;
    $params->filter_area_do = $filter_area_do;
    $params->max_area = $max_area;
    $params->max_price = $max_price;
    $params->rooms_parent_category_id = $rooms_parent_category_id;
    $params->rooms_names = $rooms_names;

    return $params;
}
