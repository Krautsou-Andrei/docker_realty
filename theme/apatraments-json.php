<?php
/*
Template Name: JSON Apartaments
*/

declare(strict_types=1);
require_once get_template_directory() . '/vendor/autoload.php';
require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/create_page.php';
require_once get_template_directory() . '/inc/lib/update_post_images.php';
require_once get_template_directory() . '/inc/lib/create_chank_posts.php';
require_once get_template_directory() . '/inc/lib/insert_posts_in_db.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/lib/get_images_map.php';
require_once get_template_directory() . '/inc/lib/get_category_map.php';
require_once get_template_directory() . '/inc/lib/get_gk_map.php';
require_once get_template_directory() . '/inc/lib/get_latest_post.php';
require_once get_template_directory() . '/inc/lib/get_post_map.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/search_id_category_by_name.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';
require_once get_template_directory() . '/inc/lib/set_value_gk.php';
require_once get_template_directory() . '/inc/lib/update_post.php';
require_once get_template_directory() . '/inc/lib/update_posts_in_db.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/names_sities.php';
require_once get_template_directory() . '/inc/enums/template_name.php';

set_time_limit(0);

$category_cache = [];
$create_posts_map = [];
$image_cache = [];
$update_posts_map = [];
$update_posts_map_images = [];

use JsonMachine\Items;

function start($is_continue_load = false)
{
    global  $names_cities, $image_cache, $category_cache, $wpdb, $create_posts_map, $update_posts_map, $update_posts_map_images;

    $category_cache = get_category_map();
    $image_cache = get_images_map();

    foreach ($names_cities as $key_city_region => $city_region) {
        $id_page_krai = search_id_page_by_name($city_region, CATEGORIES_ID::PAGE_NEW_BUILDINGS, null, TEMPLATE_NAME::REGION, true);
        $region_category_id = search_id_category_by_name($city_region);

        $regions = convert_json_to_array('/json/' . $key_city_region . '/regions.json');

        $building_type = convert_json_to_array('/json/' . $key_city_region . '/buildingtypes.json');
        $building_type_ids = [];

        foreach ($building_type as $type) {
            $building_type_ids[$type->_id] = $type->name;
        }

        $finishings = convert_json_to_array('/json/' . $key_city_region . '/finishings.json');
        $finishings_ids = [];

        foreach ($finishings as $type) {
            $finishings_ids[$type->_id] = $type->name;
        }

        $rooms = convert_json_to_array('/json/' . $key_city_region . '/room.json');
        $rooms_ids = [];

        foreach ($rooms as $room) {
            $rooms_ids[$room->crm_id] = $room->name_one;
        }

        $blocks = convert_json_to_array('/json/' . $key_city_region . '/blocks.json');

        get_message_server_telegram('Успех', 'Начало загрузки жилых комплексов ' . $key_city_region);

        foreach ($blocks as $block) {
            $region = search_region($regions, $block->district);
            $region_name = $region->name;

            $id_page = '';

            if (!empty($region_name)) {
                $id_page = search_id_page_by_name($region_name, $id_page_krai, $region_category_id, TEMPLATE_NAME::CITY_BY_NEW_BUILDING, true);
            }

            if (!empty($id_page)) {
                create_page($id_page, $block, TEMPLATE_NAME::PAGE_GK, $region_name);
            }

            wp_cache_flush();
        }

        get_message_server_telegram('Успех', 'Загрузились жилые комплексы городов: ' . $key_city_region . ' в количестве: ' . count($blocks));

        $blocks = [];
        $rooms_ids = [];
        $rooms = [];
        $finishings = [];
        $finishings_ids = [];
        $building_type = [];
        $building_type_ids = [];
        $regions = [];

        sleep(5);
    }

    $is_load = false;

    foreach ($names_cities as $key_city_region => $city_region) {
        $id_page_krai = search_id_page_by_name($city_region, CATEGORIES_ID::PAGE_NEW_BUILDINGS, null, TEMPLATE_NAME::REGION, true);
        $region_category_id = search_id_category_by_name($city_region);
        $regions = convert_json_to_array('/json/' . $key_city_region . '/regions.json');

        $rooms = convert_json_to_array('/json/' . $key_city_region . '/room.json');
        $rooms_ids = [];

        foreach ($rooms as $room) {
            $rooms_ids[$room->crm_id] = $room->name_one;
        }

        $regions_names = array_column($regions, 'name');

        $args_cities = array(
            'hide_empty' => false,
            'parent' => $region_category_id ?? 0,
        );

        $categories_cities = get_categories($args_cities);

        $search_categories_cities = [];

        foreach ($categories_cities as $category_city) {
            if (in_array($category_city->name, $regions_names)) {
                $search_categories_cities[] = $category_city->term_id;
            }
        }

        $post_map = get_post_map($search_categories_cities);

        $json_folder_path = get_template_directory() . '/json/' . $key_city_region . '/apartments.json';
        $items = Items::fromFile($json_folder_path);

        $count = 0;
        $last_post_id = $wpdb->get_var("SELECT MAX(ID) FROM {$wpdb->posts}");
        $latest_post_id = get_latest_post();

        get_message_server_telegram('Успех', 'Начало загрузки объявлений ' . $key_city_region . ' last_post_id: ' . $last_post_id);

        foreach ($items as $name => $item) {
            $count++;
            if ($is_continue_load && !$is_load && $item->_id !== $latest_post_id && $latest_post_id !== null) {
                continue;
            }

            $is_load = true;

            $data = [
                'id' => $item->_id,
                'product_price' => $item->price ?? 0,
                'product_price_meter' => ($item->price && $item->area_total) ? round(floatval($item->price) / floatval($item->area_total), 2) : 0,
                'product_rooms' => $rooms_ids[$item->room] ?? 0,
                'product_room_id' => $item->room ?? '',
                'product_area' => $item->area_total ?? 0,
                'product_area_kitchen' => $item->area_kitchen ?? '',
                'product_area_rooms_total' => $item->area_rooms_total ?? '',
                'product_stage' => $item->floor ?? '',
                'product_stages' => $item->floors ?? '',
                'product_year_build' => $item->building_deadline ?? '',
                'product_city' => $item->block_district_name ?? '',
                'product_gk' => $item->block_name ?? '',
                'product_street' => $item->block_address ?? '',
                'coordinates' => $item->block_geometry->coordinates ?? [],
                'product_building_type' => $building_type_ids[$item->building_type] ?? '',
                'product_finishing' => $finishings_ids[$item->finishing] ?? '',
                'building_name' => $item->building_name ?? '',
                'block_id' => $item->block_id ?? '',
                'product_apartament_number' => $item->number ?? '',
                'product_apartamens_wc' => $item->wc_count ?? '',
                'product_height' => $item->height ?? '',
            ];


            $post_id = $post_map[$item->_id] ?? false;

            if ($post_id) {
                update_post($data, $post_id);
            } else {
                $last_post_id++;
                create_chank_posts($data, $region_category_id, $last_post_id);
            }
        }

        if (!empty($create_posts_map)) {
            insert_posts_in_db();
        }

        if (!empty($update_posts_map)) {
            update_posts_in_db();
        }

        $post_map = [];
        gc_collect_cycles();
        wp_cache_flush();

        $rooms_ids = [];
        $rooms = [];
        $regions = [];

        if ($is_load) {
            get_message_server_telegram('Успех', 'Начало обновления цены ' . $key_city_region);

            $gk_map = get_gk_map($id_page_krai);
            $post_map_categories = get_post_map_category($search_categories_cities);

            foreach ($gk_map as $gk_id) {
                set_value_gk($gk_id, $post_map_categories);
            }

            $gk_map = null;
            $post_map_categories = null;
        }
        
        get_message_server_telegram('Успех', 'Загрузились объявления: ' . $key_city_region . ' в количестве: ' . $count);
        sleep(5);
    }


    foreach ($names_cities as $key_city_region => $city_region) {
        $region_category_id = search_id_category_by_name($city_region);
        $regions = convert_json_to_array('/json/' . $key_city_region . '/regions.json');

        $regions_names = array_column($regions, 'name');

        $args_cities = array(
            'hide_empty' => false,
            'parent' => $region_category_id ?? 0,
        );

        $categories_cities = get_categories($args_cities);

        $search_categories_cities = [];

        foreach ($categories_cities as $category_city) {
            if (in_array($category_city->name, $regions_names)) {
                $search_categories_cities[] = $category_city->term_id;
            }
        }

        $post_map = get_post_map($search_categories_cities);

        $json_folder_path = get_template_directory() . '/json/' . $key_city_region . '/apartments.json';
        $items = Items::fromFile($json_folder_path);

        get_message_server_telegram('Успех', 'Начало загрузки картинок для объявлений ' . $key_city_region);

        foreach ($items as $name => $item) {
            $data = [
                'id'              => $item->_id,
                'product_gallery' => !empty($item->plan) ? $item->plan : [],
            ];

            $post_id = $post_map[$item->_id] ?? false;

            if ($post_id) {
                update_post_images($data, $post_id);
            }
        }

        if (!empty($update_posts_map_images)) {
            update_posts_images_in_db();
        }

        $post_map = [];
        gc_collect_cycles();
        wp_cache_flush();

        get_message_server_telegram('Успех', 'Загрузились картинки для объявлений: ' . $key_city_region);
        sleep(5);
    }

    sleep(10);
    get_message_server_telegram('Успех', 'Загрузились все объявления');
}

function search_region($regions, $search_id)
{
    $searchRegion = array_filter($regions, function ($object) use ($search_id) {
        return $object->_id === $search_id;
    });

    if (empty($searchRegion)) {
        prettyVarDump($search_id);
    }

    return  reset($searchRegion);
}

function convert_json_to_array($path_json)
{
    $json_building_type_path = get_template_directory() . $path_json;
    $current_array = [];

    if (file_exists($json_building_type_path)) {
        $json_building_type = file_get_contents($json_building_type_path);
        if ($json_building_type === false) {
            echo "Не удалось прочитать файл.";
        } else {
            $current_array = json_decode($json_building_type);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Ошибка JSON: " . json_last_error_msg();
            }
        }
    } else {
        echo "Файл не найден: $json_building_type_path";
    }

    return $current_array ?? [];
}

function prettyVarDump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
