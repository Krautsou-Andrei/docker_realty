<?php
require_once('/var/www/html/wp-load.php');
require_once get_template_directory() . '/get_json.php';
require_once get_template_directory() . '/apatraments-json.php';
require_once get_template_directory() . '/inc/lib/delete_old_posts.php';

$max_attempts = 10;

// Функция для выполнения задачи с повторными попытками
function execute_with_retries($function_name, $max_attempts = 10)
{
    for ($attempt = 1; $attempt <= $max_attempts; $attempt++) {
        try {
            // Вызов функции с параметрами, если они переданы
            $function_name();
            return; // Если функция выполнена успешно, выходим
        } catch (Exception $e) {
            error_log('Attempt ' . $attempt . ' failed for ' . $function_name . ': ' . $e->getMessage());
            if ($attempt === $max_attempts) {
                throw $e; // Если это последняя попытка, выбрасываем исключение
            }
        }
    }
}

// execute_with_retries('my_custom_task');

try {
    start();
} catch (Exception $e) {
    error_log('First attempt to start failed: ' . $e->getMessage());
    execute_with_retries('start'); // Повторный вызов с параметром true
}

// execute_with_retries('delete_old_posts');
