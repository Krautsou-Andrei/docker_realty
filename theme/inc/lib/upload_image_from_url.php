<?php

function upload_image_from_url($image_url)
{
    // Проверка, существует ли изображение в медиабиблиотеке
    $attachment_id = attachment_url_to_postid($image_url);
    if ($attachment_id) {
        return $attachment_id; // Возвращаем ID, если изображение уже существует
    }

    // Получаем содержимое изображения
    $response = wp_remote_get($image_url);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return new WP_Error('upload_error', 'Ошибка при получении изображения.');
    }

    // Получаем тело ответа
    $image_data = wp_remote_retrieve_body($response);

    // Загрузка изображения в медиа библиотеку
    $upload = wp_upload_bits(basename($image_url), null, $image_data);

    if ($upload['error']) {
        return new WP_Error('upload_error', 'Ошибка загрузки изображения: ' . $upload['error']);
    }

    // Создание вложения
    $attachment = array(
        'guid'           => $upload['url'],
        'post_mime_type' => $upload['type'],
        'post_title'     => sanitize_file_name(basename($image_url)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Вставка вложения в БД и получение ID
    $attachment_id = wp_insert_attachment($attachment, $upload['file']);

    // Подготовка метаданных для вложения
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    wp_update_attachment_metadata($attachment_id, $attach_data);

    return $attachment_id;
}
