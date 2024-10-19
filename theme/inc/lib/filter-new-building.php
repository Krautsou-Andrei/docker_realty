<?php


$type = $_GET['type'];
$where = '/' . $type . '/?';

if (isset($_GET['option-radio-city']) && $_GET['option-radio-city'] != '') {
    switch ($_GET['option-radio-city']) {
        case 'Краснодар':
            $where .= '&city=2301';
            break;
        case 'Новороссийск':
            $where .= '&city=2306';
            break;
        default:
            // Можно добавить обработку случая, когда город не соответствует ни одному из значений
            break;
    }
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
if (isset($_GET['select-price']) && $_GET['select-price'] != '' && isset($_GET['option-select-price-from']) && isset($_GET['option-select-price-to']) && $_GET['option-select-price-from'] == '' && $_GET['option-select-price-to'] == '') {
    $where .= '&select_price=' . $_GET['select-price'];
}
if (isset($_GET['select-area']) && $_GET['select-area'] != '' && isset($_GET['option-select-area-from']) && isset($_GET['option-select-area-to']) && $_GET['option-select-area-from'] == '' && $_GET['option-select-area-to'] == '') {
    $where .= '&select_area=' . $_GET['select-area'];
}

if (isset($_GET['check-price'])) {
    $where .= '&check_price=' . $_GET['check-price'];
}

if (isset($_GET['option-select-area-from']) && isset($_GET['option-select-area-to'])) {
    if ($_GET['option-select-area-from'] == '' && $_GET['option-select-area-to'] != '') {
        $where .= '&select_area=' . '1-' . $_GET['option-select-area-to'];
    }
    if ($_GET['option-select-area-from'] != '' && $_GET['option-select-area-to'] != '') {
        $where .= '&select_area=' . $_GET['option-select-area-from'] . '-' . $_GET['option-select-area-to'];
    }
    if ($_GET['option-select-area-from'] != '' && $_GET['option-select-area-to'] == '') {
        $where .= '&select_area=' . $_GET['option-select-area-from'];
    }
}

if (isset($_GET['option-select-price-from']) && isset($_GET['option-select-price-to'])) {
    if ($_GET['option-select-price-from'] == '' && $_GET['option-select-price-to'] != '') {
        $where .= '&select_price=' . '1-' . $_GET['option-select-price-to'];
    }
    if ($_GET['option-select-price-from'] != '' && $_GET['option-select-price-to'] != '') {
        $where .= '&select_price=' . $_GET['option-select-price-from'] . '-' . $_GET['option-select-price-to'];
    }
    if ($_GET['option-select-price-from'] != '' && $_GET['option-select-price-to'] == '') {
        $where .= '&select_price=' . $_GET['option-select-price-from'];
    }
}

if ($where == '/novostrojki/?') $where = '/novostrojki/';
if ($where == '/buildings_map/?') $where = '/buildings_map/';

header('Location: ' . $where);
