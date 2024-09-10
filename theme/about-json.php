<?php
/*
Template Name: JSON About
*/

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate'); // Запрет кэширования
header('Pragma: no-cache'); // Запрет кэширования
header('Expires: 0'); // Запрет кэширования

// Указываем путь к папке с JSON файлами
$json_folder_path = get_template_directory() . '/json/';

// Получаем имя файла из URL параметра 'file'
$file_name = isset($_GET['file']) ? $_GET['file'] : '';

// Проверяем, существует ли файл
$file_path = $json_folder_path . sanitize_file_name($file_name) . '.json';

if (file_exists($file_path)) {
    // Устанавливаем заголовок для передачи размера файла
    header('Content-Length: ' . filesize($file_path));
    header('Content-Disposition: inline; filename="' . basename($file_path) . '"');

    // Открываем файл для чтения
    $stream = fopen($file_path, 'rb');
    if ($stream) {
        // Читаем файл и отправляем его содержимое
        while (!feof($stream)) {
            echo fread($stream, 8192); // Читаем по 8 КБ
            flush(); // Очищаем буфер вывода
        }
        fclose($stream); // Закрываем файл
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Unable to read file']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
}

exit;
