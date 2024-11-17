<?php
$type = $_GET['type'];
$where = '/' . $type . '/?';

if (isset($_GET['option-radio-region']) && $_GET['option-radio-region'] != '') {
    $where .= '&region=' . $_GET['option-radio-region'];
}

if (isset($_GET['option-radio-city']) && $_GET['option-radio-city'] != '') {
    $where .= '&city=' . $_GET['option-radio-city'];
}

if (isset($_GET['option-radio-type-build']) && $_GET['option-radio-type-build'] != '') {
    $where .= '&type-build=' . $_GET['option-radio-type-build'];
}

if (isset($_GET['option-checkbox-rooms']) && !empty($_GET['option-checkbox-rooms'])) {
    $rooms = $_GET['option-checkbox-rooms']; // Получаем массив

    // Преобразуем массив в строку, разделяя элементы запятой
    $rooms_string = implode(',', $rooms);

    // Добавляем в $where, используя правильный формат
    $where .= '&rooms=' . urlencode($rooms_string); // Используем urlencode для безопасного добавления в URL
}
if (isset($_GET['select-price']) && $_GET['select-price'] != '') {
    $where .= '&select_price=' . $_GET['select-price'];
}
if (isset($_GET['select-area']) && $_GET['select-area'] != '') {
    $where .= '&select_area=' . $_GET['select-area'];
}

if (isset($_GET['check-price'])) {
    $where .= '&check_price=' . $_GET['check-price'];
}



if ($where == '/novostrojki/?') $where = '/novostrojki/';
if ($where == '/buildings_map/?') $where = '/buildings_map/';

header('Location: ' . $where);
