<?php
/*
Template Name: JSON Apartaments
*/


declare(strict_types=1);
require_once get_template_directory() . '/vendor/autoload.php';
require_once get_template_directory() . '/inc/lib/create_page.php';
require_once get_template_directory() . '/inc/lib/create_post.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/template_name.php';

set_time_limit(0);



$args_cities = array(
    'hide_empty' => false,
    'parent' => CATEGORIES_ID::CITIES,
);

$categories_cities = get_categories($args_cities);
$categories_cities_name = [];

foreach ($categories_cities as $city) {
    $categories_cities_name[] = $city->name;
}

$json_regions_path = get_template_directory() . '/json/regions.json';
$json_regions = file_get_contents($json_regions_path);
$regions = json_decode($json_regions);
$regions_ids = [];

foreach ($regions as $region) {
    if (in_array($region->name, $categories_cities_name)) {
        $regions_ids[] = $region->_id;
    }
}

$json_building_type_path = get_template_directory() . '/json/buildingtypes.json';
$json_building_type = file_get_contents($json_building_type_path);
$building_type = json_decode($json_building_type);
$building_type_ids = [];

foreach ($building_type as $type) {
    $building_type_ids[$type->_id] = $type->name;
}

$json_finishings_path = get_template_directory() . '/json/finishings.json';
$json_finishings = file_get_contents($json_finishings_path);
$finishings = json_decode($json_finishings);
$finishings_ids = [];

foreach ($finishings as $type) {
    $finishings_ids[$type->_id] = $type->name;
}

$json_rooms_path = get_template_directory() . '/json/room.json';
$json_rooms = file_get_contents($json_rooms_path);
$rooms = json_decode($json_rooms);
$rooms_ids = [];

foreach ($rooms as $room) {
    $rooms_ids[$room->crm_id] = $room->name_one;
}

prettyVarDump($regions);


$json_blocks_path = get_template_directory() . '/json/blocks.json';
$json_blocks = file_get_contents($json_blocks_path);
$blocks = json_decode($json_blocks);



foreach ($blocks as $block) {
    if (in_array($block->district, $regions_ids)) {
        $region = search_region($regions, $block->district);
        $region_name = $region->name;
        $region_category_id = get_term_by('name', $region_name, 'category')->term_id;

        $id_page = search_id_page_by_name(CATEGORIES_ID::PAGE_NEW_BUILDINGS, $region_name);

        if (!empty($id_page)) {
            create_page($id_page, $block, TEMPLATE_NAME::PAGE_GK, $region_name);
        }
    }
}


use JsonMachine\Items;

$json_folder_path = get_template_directory() . '/json/apartaments.json';
$items = Items::fromFile($json_folder_path);

$count = 0;

foreach ($items as $name => $item) {

    // if ($count >= 2) {
    //     break;
    // }

    if (in_array($item->block_district, $regions_ids)) {
        $data = new stdClass();

        $data->id = $item->_id;
        $data->product_gallery = $item->plan[0] ? $item->plan : [home_url('/wp-content/uploads/2024/09/no-photo-lg.png')];
        $data->product_price = $item->price ?? 0;
        $data->product_price_meter = $item->price && $item->area_total ? round(floatval($item->price) / floatval($item->area_total), 2) :  0;
        $data->product_rooms = $rooms_ids[$item->room] ?? 0;
        $data->product_room_id = $item->room ?? '';
        $data->product_area = $item->area_total ?? 0;
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

        create_post($data);
    }



    $count++;
}

function search_region($regions, $search_id)
{
    $searchRegion = array_filter($regions, function ($object) use ($search_id) {
        return $object->_id === $search_id;
    });

    return  reset($searchRegion);
}


function prettyVarDump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
