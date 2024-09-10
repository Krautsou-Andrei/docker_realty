<?php
/*
Template Name: JSON Apartaments
*/


declare(strict_types=1);
require_once get_template_directory() . '/inc/lib/create_post.php';

set_time_limit(0);

use JsonMachine\Items;



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

    create_post($data);

    $count++; // Увеличиваем счётчик
}

function prettyVarDump($data)
{
    echo '<pre>'; // Открываем тег <pre> для форматирования
    var_dump($data);
    echo '</pre>'; // Закрываем тег <pre>
}
