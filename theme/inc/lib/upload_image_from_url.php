<?php

function upload_image_from_url($image_url)
{
    // Проверяем существование изображения по сохраненному URL
    $existing_attachment = get_posts(array(
        'post_type'   => 'attachment',
        'meta_key'    => 'external_image_url',
        'meta_value'  => $image_url,
        'posts_per_page' => 1,
    ));

    if ($existing_attachment) {
        return $existing_attachment[0]->ID; // Возвращаем ID, если изображение уже существует
    }

    // Получаем содержимое изображения
    $response = wp_remote_get($image_url);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        $error_message = is_wp_error($response) ? $response->get_error_message() : 'HTTP код: ' . wp_remote_retrieve_response_code($response);

        // Отправка ошибки на email
        mail_error($error_message);
        return new WP_Error('upload_error', 'Ошибка при получении изображения.');
    }

    // Получаем тело ответа
    $image_data = wp_remote_retrieve_body($response);

    // Загрузка изображения в медиа библиотеку
    $upload = wp_upload_bits(basename($image_url), null, $image_data);

    if ($upload['error']) {
        mail_error($upload['error']); // Отправка ошибки на email
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

    if (is_wp_error($attachment_id)) {
        mail_error($attachment_id->get_error_message()); // Отправка ошибки на email
        return new WP_Error('upload_error', 'Ошибка при создании вложения.');
    }

    // Подготовка метаданных для вложения
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    if (!$attach_data) {
        mail_error('Ошибка генерации метаданных для вложения.'); // Отправка ошибки на email
    }
    wp_update_attachment_metadata($attachment_id, $attach_data);

    // Сохраняем URL изображения в метаданных
    update_post_meta($attachment_id, 'external_image_url', $image_url);

    return $attachment_id;
}

// Функция для отправки ошибок на email
function mail_error($error_message)
{
    $to = 'andreysv2006@yandex.by'; // Замените на нужный адрес электронной почты
    $subject = 'Ошибка при вставке поста';
    $body = 'Произошла ошибка: ' . $error_message;
    $headers = 'From: no-reply@example.com' . "\r\n" .
        'Reply-To: no-reply@example.com' . "\r\n";

    mail($to, $subject, $body, $headers);
}
