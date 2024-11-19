<?php
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';

function upload_image_from_url($image_url, $count = 0)
{
    $max_attempts = 5;
    $delay = 2;

    // Пытаемся загрузить изображение
    $attachment_id = upload_image($image_url);

    // Проверяем, произошла ли ошибка при загрузке изображения
    if (is_wp_error($attachment_id)) {
        // Проверяем, достигнут ли лимит попыток
        if ($count < $max_attempts) {
            sleep($delay); // Ждем перед следующей попыткой
            return upload_image_from_url($image_url, $count + 1); // Увеличиваем счетчик
        } else {
            return $attachment_id; // Возвращаем ошибку, если достигнут лимит попыток
        }
    }

    // Проверяем, является ли $attachment_id идентификатором вложения
    if (is_numeric($attachment_id) && $attachment_id > 0) {       
        return $attachment_id;
    } else {
        $webp_attachment_id = convert_image_to_webp($attachment_id, $image_url);

        if (is_wp_error($webp_attachment_id)) {
            return $webp_attachment_id;
        }

        global $image_cache;
        $webp_image_url = get_name_image_webp($image_url);

        $image_cache[$webp_image_url] = $webp_attachment_id;   
        return $webp_attachment_id;
    }
}

function convert_image_to_webp($image_data, $image_url)
{
    try {
        $imagick = new Imagick();
        $imagick->readImageBlob($image_data);


        if ($imagick->getImageWidth() > 2560 || $imagick->getImageHeight() > 1440) {
            $width = $imagick->getImageWidth();
            $height = $imagick->getImageHeight();

            $coefficient = 1;

            if ($width >= 2560) {
                $coefficient = ceil($width / 2560);
            }

            if ($height > 1440 && $width < 2560) {
                $coefficient = ceil($height / 1440);
            }

            $new_width = (int)($width / $coefficient);
            $new_height = (int)($height / $coefficient);

            $imagick->resizeImage(
                $new_width,
                $new_height,
                Imagick::FILTER_LANCZOS,
                1
            );
        }

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
            'guid' => $uploads['url'] . '/' . $webp_filename,
            'post_mime_type' => 'image/webp',
            'post_title' => sanitize_file_name($webp_filename),
            'post_content' => '',
            'post_status' => 'inherit'
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
        $webp_image_url = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $image_url);
        update_post_meta($webp_attachment_id, 'external_image_url', $webp_image_url);

        return $webp_attachment_id; // Возвращаем ID вложения WebP
    } catch (Exception $e) {
        return new WP_Error('conversion_error', 'Ошибка при конвертации: ' . $e->getMessage());
    }
}

function upload_image($image_url)
{
    global $image_cache;

    $webp_image_url = get_name_image_webp($image_url);

    $image_id = $image_cache[$webp_image_url] ?? false;    

    if ($image_id) {
        return $image_id;
    }

    $response = wp_remote_get($image_url);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        $error_message = 'Ошибка при получении изображения ';
        return new WP_Error($error_message);
    }

    // Получаем тело ответа
    $image_data = wp_remote_retrieve_body($response);

    if (empty($image_data)) {
        $error_message = 'Пустое тело ответа ';
        return new WP_Error($error_message);
    }

    // Возвращаем данные изображения для конвертации
    return $image_data; // Возвращаем данные изображения, чтобы конвертировать их в WebP
}

function get_name_image_webp($image_url)
{
    $file_name = basename($image_url);
    $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
    $webp_image_url = $file_name_without_extension . '.webp';

    return $webp_image_url;
}
