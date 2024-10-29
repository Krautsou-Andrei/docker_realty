<?php
require_once get_template_directory() . '/inc/lib/get_message_server.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';

function upload_image_from_url($image_url, $count = 0)
{
    $max_attempts = 5;
    $delay = 2;

    $attachment_id = upload_image($image_url);

    if (is_wp_error($attachment_id)) {

        if ($count < $max_attempts) {
            sleep($delay);
            return upload_image_from_url($image_url, $count + 1); // Увеличиваем счетчик
        } else {
            return $attachment_id;
        }
    }

    return $attachment_id;
}

function convert_image_to_webp($image_data, $image_url)
{
    // Максимально допустимый размер изображения (например, 5MB)
    $max_size = 10 * 1024 * 1024; // 10 мегабайт

    // Проверяем размер изображения
    if (strlen($image_data) > $max_size) {
        return new WP_Error('size_error', 'Изображение слишком большое для обработки.');
    }

    try {
        $imagick = new Imagick();
        $imagick->readImageBlob($image_data);

        // Получаем расширение файла
        $file_extension = pathinfo($image_url, PATHINFO_EXTENSION);

        // Указываем путь для сохранения WebP
        $uploads = wp_upload_dir();
        $webp_filename = sanitize_file_name(basename($image_url, '.' . $file_extension)) . '.webp';
        $webp_path = $uploads['path'] . '/' . $webp_filename;

        // Установим формат и качество
        $imagick->setImageFormat('webp');
        $imagick->setImageCompressionQuality(80); // 80 - качество

        // Сохраняем изображение в формате WebP
        $imagick->writeImage($webp_path);

        // Освобождаем память
        $imagick->clear();
        $imagick->destroy();

        // Создание вложения для WebP
        $webp_attachment = array(
            'guid'           => $uploads['url'] . '/' . $webp_filename,
            'post_mime_type' => 'image/webp',
            'post_title'     => sanitize_file_name($webp_filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Вставка вложения в БД и получение ID
        $webp_attachment_id = wp_insert_attachment($webp_attachment, $webp_path);

        // Проверка на ошибку при вставке
        if (is_wp_error($webp_attachment_id)) {
            return new WP_Error('insert_error', 'Ошибка при вставке вложения в базу данных: ' . $webp_attachment_id->get_error_message());
        }

        // Подготовка метаданных для WebP
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($webp_attachment_id, $webp_path);
        wp_update_attachment_metadata($webp_attachment_id, $attach_data);

        // Сохраняем URL изображения в метаданных
        update_post_meta($webp_attachment_id, 'external_image_url', $image_url);

        return $webp_attachment_id; // Возвращаем ID вложения WebP
    } catch (Exception $e) {
        return new WP_Error('conversion_error', 'Ошибка при конвертации: ' . $e->getMessage());
    }
}

function upload_image($image_url)
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

    $response = wp_remote_get($image_url);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        $error_message = 'Ошибка при получении изображения ';
        // get_message_server($error_message);
        get_message_server_telegram('Ошибка ', $error_message);
        return new WP_Error($error_message);
    }

    // Получаем тело ответа
    $image_data = wp_remote_retrieve_body($response);

    if (empty($image_data)) {
        $error_message = 'Пустое тело ответа ';
        // get_message_server($error_message);
        get_message_server_telegram('Ошибка ', $error_message);
        return new WP_Error($error_message);
    }

    // Конвертация изображения в WebP
    $webp_attachment_id = convert_image_to_webp($image_data, $image_url);

    if (is_wp_error($webp_attachment_id)) {
        $error_message = 'Ошибка при конвертации ';
        get_message_server_telegram('Ошибка ', $error_message);
        return new WP_Error($error_message);
    }

    return $webp_attachment_id;
}
