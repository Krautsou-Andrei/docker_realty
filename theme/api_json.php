<?php
require_once get_template_directory() . '/inc/lib/get_latest_post.php';
/*
Template Name: Serve Large JSON
*/

// header('Content-Type: application/json'); // Устанавливаем заголовок для JSON

// // Указываем путь к файлу
// $json_folder_path = get_template_directory() . '/json/';
// $file_name = isset($_GET['file']) ? $_GET['file'] : 'largefile'; // Укажите имя файла без расширения
// $file_path = $json_folder_path . sanitize_file_name($file_name) . '.json'; // Добавляем расширение

// if (file_exists($file_path)) {
//     // Устанавливаем заголовки для передачи файла
//     header('Content-Length: ' . filesize($file_path)); // Указываем размер файла
//     header('Content-Disposition: inline; filename="' . basename($file_path) . '"'); // Указываем имя файла

//     // Открываем файл
//     $file = fopen($file_path, 'rb');
//     if ($file) {
//         // Передаем содержимое файла
//         fpassthru($file);
//         fclose($file);
//     }
//     exit;
// } else {
//     // Если файл не найден, отправляем ошибку
//     http_response_code(404);
//     echo json_encode(['error' => 'File not found']);
//     exit;
// }

date_default_timezone_set('Europe/Moscow');

$date_query = new DateTime('now');

$date_query->modify('-1 hour');
$timestamp = $date_query->getTimestamp();

$args = [
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => [
        [
            'column' => 'post_modified',
            'after' => date('Y-m-d H:i:s', $timestamp),
        ],
    ],
    'fields' => 'ids',
];

$query = get_posts($args);

var_dump(count($query));

$latest_post = get_latest_post();

var_dump($latest_post);
var_dump(memory_get_usage());

// if ($query->have_posts()) :
//     echo '<ul>';
//     while ($query->have_posts()) : $query->the_post();
//         echo '<li>' . get_the_title() . ' - ' . get_the_modified_date() . '</li>';
//     endwhile;
//     echo '</ul>';
// else :
//     echo 'Нет постов, обновленных за последний час.';
// endif;

// // Сбрасываем глобальные переменные поста
// wp_reset_postdata();