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

if ($where == '/novostrojki/?') $where = '/novostrojki/';

header('Location: ' . $where);
