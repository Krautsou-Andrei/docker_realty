<?php
// Подключаем MODX
define('MODX_API_MODE', true);
require '../index.php';
require 'Import.php';

//подрубаем pdoТools для шаблонизации и поиска
$pdo = $modx->getService('pdoFetch');
$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->switchContext('web');

$items = [
    'krasnodar' => [
        'school' => 'https://krasnodar.jsprav.ru/api/map/category/3443/?bbox=38.8037,44.9703,39.1553,45.1260&zoom=13', 
        'kindergarten' => 'https://krasnodar.jsprav.ru/api/map/category/1171/?bbox=37.2656,44.2798,42.1875,46.2654&zoom=9',
        'market' => 'https://krasnodar.jsprav.ru/api/map/category/1629/?bbox=38.8037,44.9703,39.1553,45.1260&zoom=13',
        'hospital' => 'https://krasnodar.jsprav.ru/api/map/category/1816/?bbox=38.8037,44.9703,39.1553,45.1260&zoom=13',
    ],
    'novoross' => [
        'school' => 'https://novorossijsk.jsprav.ru/api/map/category/3443/?bbox=37.6172,44.6574,37.9248,44.7828&zoom=13', 
        'kindergarten' => 'https://novorossijsk.jsprav.ru/api/map/category/1171/?bbox=37.6172,44.6574,37.9248,44.7828&zoom=9',
        'market' => 'https://novorossijsk.jsprav.ru/api/map/category/1629/?bbox=37.6172,44.6574,37.9248,44.7828&zoom=13',
        'hospital' => 'https://novorossijsk.jsprav.ru/api/map/category/1816/?bbox=37.6172,44.6574,37.9248,44.7828&zoom=13',

    ],
];

foreach ($items as $key => $city) {
    foreach ($city as $k => $item) {
         if($data = Import::get($item, $k)){
            //var_dump($data);
            Import::save($key, $k, $data);
       }
    } 
}
