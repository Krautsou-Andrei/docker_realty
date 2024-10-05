<?php
/*
Template Name: Serve Large JSON
*/

header('Content-Type: application/json'); // Устанавливаем заголовок для JSON

// Указываем путь к файлу
$json_folder_path = get_template_directory() . '/json/';
$file_name = isset($_GET['file']) ? $_GET['file'] : 'largefile'; // Укажите имя файла без расширения
$file_path = $json_folder_path . sanitize_file_name($file_name) . '.json'; // Добавляем расширение

if (file_exists($file_path)) {
    // Устанавливаем заголовки для передачи файла
    header('Content-Length: ' . filesize($file_path)); // Указываем размер файла
    header('Content-Disposition: inline; filename="' . basename($file_path) . '"'); // Указываем имя файла

    // Открываем файл
    $file = fopen($file_path, 'rb');
    if ($file) {
        // Передаем содержимое файла
        fpassthru($file);
        fclose($file);
    }
    exit;
} else {
    // Если файл не найден, отправляем ошибку
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit;
}