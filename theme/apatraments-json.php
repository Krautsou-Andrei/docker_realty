<?php
/*
Template Name: JSON Apartaments
*/

declare(strict_types=1);
require_once get_template_directory() . '/vendor/autoload.php';
require_once get_template_directory() . '/inc/lib/create_page.php';
require_once get_template_directory() . '/inc/lib/create_post.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';
require_once get_template_directory() . '/inc/lib/update_post.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/template_name.php';

set_time_limit(0);

use JsonMachine\Items;

function start()
{
    $args_cities = array(
        'hide_empty' => false,
        'parent' => CATEGORIES_ID::CITIES,
    );

    $categories_cities = get_categories($args_cities);
    $categories_cities_name = [];

    foreach ($categories_cities as $city) {
        $categories_cities_name[] = $city->name;
    }


    $regions = convert_json_to_array('/json/regions.json');
    $regions_ids = [];

    foreach ($regions as $region) {
        if (in_array($region->name, $categories_cities_name)) {
            $regions_ids[] = $region->_id;
        }
    }

    $building_type = convert_json_to_array('/json/buildingtypes.json');
    $building_type_ids = [];

    foreach ($building_type as $type) {
        $building_type_ids[$type->_id] = $type->name;
    }

    $finishings = convert_json_to_array('/json/finishings.json');
    $finishings_ids = [];

    foreach ($finishings as $type) {
        $finishings_ids[$type->_id] = $type->name;
    }

    $rooms = convert_json_to_array('/json/room.json');
    $rooms_ids = [];

    foreach ($rooms as $room) {
        $rooms_ids[$room->crm_id] = $room->name_one;
    }

    $blocks = convert_json_to_array('/json/blocks.json');

    get_message_server_telegram('Успех', 'Начало загрузки жилых комплексов');

    foreach ($blocks as $block) {
        if (in_array($block->district, $regions_ids)) {
            $region = search_region($regions, $block->district);
            $region_name = $region->name;

            $id_page = search_id_page_by_name(CATEGORIES_ID::PAGE_NEW_BUILDINGS, $region_name);

            if (!empty($id_page)) {
                create_page($id_page, $block, TEMPLATE_NAME::PAGE_GK, $region_name);
            }
        }        
        wp_cache_flush();
    }

    get_message_server_telegram('Успех', 'Загрузились жилые комплексы городов: ' .  implode(', ', $categories_cities_name));


    prettyVarDump($regions_ids);

    $json_folder_path = get_template_directory() . '/json/apartments.json';
    $items = Items::fromFile($json_folder_path);
    get_message_server_telegram('Успех', 'Начало загрузки объявлений');

    foreach ($items as $name => $item) {
        if (in_array($item->block_district, $regions_ids)) {
            $data = new stdClass();

            $data->id = $item->_id;
            $data->product_gallery = $item->plan[0] ? $item->plan : '';
            $data->product_price = $item->price ?? 0;
            $data->product_price_meter = $item->price && $item->area_total ? round(floatval($item->price) / floatval($item->area_total), 2) :  0;
            $data->product_rooms = $rooms_ids[$item->room] ?? 0;
            $data->product_room_id = $item->room ?? '';
            $data->product_area = $item->area_total ?? 0;
            $data->product_area_kitchen = $item->area_kitchen ?? '';
            $data->product_area_rooms_total = $item->area_rooms_total ?? '';
            $data->product_stage = $item->floor ?? '';
            $data->product_stages = $item->floors ?? '';
            $data->product_year_build = $item->building_deadline ?? '';
            $data->product_city = $item->block_district_name ?? '';
            $data->product_gk = $item->block_name ?? '';
            $data->product_street = $item->block_address ?? '';
            $data->coordinates = $item->block_geometry->coordinates ?? [];
            $data->product_building_type = $building_type_ids[$item->building_type] ?? '';
            $data->product_finishing = $finishings_ids[$item->finishing] ?? '';
            $data->building_name = $item->building_name ?? '';
            $data->block_id = $item->block_id ?? '';
            $data->product_apartament_number = $item->number ?? '';
            $data->product_apartamens_wc = $item->wc_count ?? '';
            $data->product_height = $item->height ?? '';

            $args_test = [
                'post_type'      => 'post', // Укажите тип поста
                'key'      => 'product-id',
                'meta_value'    => $item->_id,
                'posts_per_page' => 1, // Получить только один пост
                'fields'         => 'ids' // Вернуть только ID поста
            ];

            $existing_posts = get_posts($args_test);

            if ($existing_posts) {
                $post_id = $existing_posts[0]; // Получаем ID существующего поста   
                update_post($data, $post_id);
            } else {
                create_post($data);
            }
        }        
        wp_cache_flush();
    }

    get_message_server_telegram('Успех', 'Загрузились объявления');
}


function search_region($regions, $search_id)
{
    $searchRegion = array_filter($regions, function ($object) use ($search_id) {
        return $object->_id === $search_id;
    });

    return  reset($searchRegion);
}

function convert_json_to_array($path_json)
{
    $json_building_type_path = get_template_directory() . $path_json;
    $json_building_type = file_get_contents($json_building_type_path);
    $current_array = json_decode($json_building_type);
    return $current_array;
}

function prettyVarDump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
