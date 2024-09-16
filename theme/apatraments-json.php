<?php
/*
Template Name: JSON Apartaments
*/


declare(strict_types=1);
require_once get_template_directory() . '/inc/lib/create_page.php';
require_once get_template_directory() . '/inc/lib/create_post.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/template_name.php';

set_time_limit(0);

use JsonMachine\Items;

$crb_gk = carbon_get_post_meta(CATEGORIES_ID::PAGE_NEW_BUILDINGS, 'crb_gk');

$args_new_buildings = array(
    'post_type'      => 'page', // Тип поста - страница
    'post_parent'    => CATEGORIES_ID::PAGE_NEW_BUILDINGS, // ID родительской страницы
    'posts_per_page' => -1, // Получить все дочерние страницы    
);

// Выполняем запрос
$pages_children_new_buildings = get_posts($args_new_buildings);

// prettyVarDump($pages_children_new_buildings);

$args_cities = array(
    'hide_empty' => false,
    'parent' => CATEGORIES_ID::CITIES,
);

$categories_cities = get_categories($args_cities);
$categories_cities_name = [];


foreach ($categories_cities as $city) {
    $categories_cities_name[] = $city->name;
}

foreach ($categories_cities as $cities) {
    $args_gk = array(
        'hide_empty' => false,
        'parent' => $cities->term_id,
    );

    $categories_gk = get_categories($args_gk);
    $categories_gk_names = [];

    foreach ($categories_gk as $gk) {
        $categories_gk_names[] = $gk->name;
    }
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




// prettyVarDump($regions);


$json_blocks_path = get_template_directory() . '/json/blocks.json';
$json_blocks = file_get_contents($json_blocks_path);
$blocks = json_decode($json_blocks);

$crb_gk_new = [];

foreach ($blocks as $block) {
    if (in_array($block->district, $regions_ids)) {
        $region = search_region($regions, $block->district);
        $region_name = $region->name;
        $region_category_id = get_term_by('name', $region_name, 'category')->term_id;
        prettyVarDump($region_name);
        $id_page = search_id_page_by_name(CATEGORIES_ID::PAGE_NEW_BUILDINGS, $region_name);

        if (!empty($id_page)) {
            create_page($id_page, $block, TEMPLATE_NAME::PAGE_GK, $region_name);
        }

        // $crb_gk_new[] = get_new_complex_fields_gk($blocks, $region_name);

        prettyVarDump($id_page);
    }
}

if (!empty($crb_gk_new)) {
    // carbon_set_post_meta(CATEGORIES_ID::PAGE_NEW_BUILDINGS, 'crb_gk', $crb_gk_new);
}







$json_folder_path = get_template_directory() . '/json/apartaments.json';

$items = Items::fromFile($json_folder_path);

$count = 0; // Счётчик итераций

foreach ($items as $name => $item) {
    if ($count >= 10) {
        break; // Прерываем цикл после 10 итераций
    }

    $data = new stdClass(); // Создание нового объекта

    $data->_id = $item->_id;
    $data->product_gallery = $item->plan[0] ?? "https://cdn-dataout.trendagent.ru/images/o/y/7hbh0odgo51mue86z1bczyvh.png";
    $data->product_price = $item->price ?? 0;
    $data->product_price_meter = $item->price ?? 0;
    $data->product_rooms = $item->room ?? 0;
    $data->product_area = $item->area_total ?? 0;
    $data->product_stage = $item->floor ?? '';
    $data->product_city = $item->block_address ?? '';

    // create_post($data);

    $count++; // Увеличиваем счётчик
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
    echo '<pre>'; // Открываем тег <pre> для форматирования
    var_dump($data);
    echo '</pre>'; // Закрываем тег <pre>
}
