<?php
require_once get_template_directory() . '/inc/lib/get_message_server.php';

function upload_image_from_url($image_url)
{



    $existing_attachment = get_posts(array(
        'post_type'   => 'attachment',
        'meta_key'    => 'external_image_url',
        'meta_value'  => $image_url,
        'posts_per_page' => 1,
    ));

    if ($existing_attachment) {
        return $existing_attachment[0]->ID; // Возвращаем ID, если изображение уже существует
    }

    $max_attempts = 5;
    $delay = 3;

    $attachment_id = 0;

    for ($attempt = 1; $attempt <= $max_attempts; $attempt++) {
        $response = wp_remote_get($image_url);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            $error_message = is_wp_error($response) ? $response->get_error_message() : 'HTTP код: ' . wp_remote_retrieve_response_code($response);

            get_message_server($error_message, true);

            if ($attempt < $max_attempts) {
                sleep($delay); // Ждем 3 секунды
                continue; // Переходим к следующей попытке
            } else {
                return new WP_Error('upload_error', 'Ошибка при получении изображения после ' . $max_attempts . ' попыток.');
            }
        } else {
            // Получаем тело ответа
            $image_data = wp_remote_retrieve_body($response);

            // Загрузка изображения в медиа библиотеку
            $upload = wp_upload_bits(basename($image_url), null, $image_data);

            if ($upload['error']) {
                get_message_server('загрузка изображения' . $upload['error'], true);
                if ($attempt < $max_attempts) {
                    sleep($delay);
                    continue;
                } else {
                    return new WP_Error('upload_error', 'Ошибка загрузки изображения: ' . $upload['error']);
                }
            } else {

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
                    get_message_server('загрузка в базу ' . $attachment_id->get_error_message(), true);

                    if ($attempt < $max_attempts) {
                        sleep($delay);
                        continue;
                    } else {
                        return new WP_Error('upload_error', 'Ошибка при создании вложения.');
                    }
                } else {
                    // Подготовка метаданных для вложения
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
                    if (!$attach_data) {
                        get_message_server('Ошибка генерации метаданных для вложения.', true);
                    } else {
                        wp_update_attachment_metadata($attachment_id, $attach_data);

                        // Сохраняем URL изображения в метаданных
                        update_post_meta($attachment_id, 'external_image_url', $image_url);
                        break;
                    }
                }
            }
        }
    }
    return $attachment_id;
}
