<?php

function create_post()
{

    $attachment_id = upload_image_from_url('https://cdn-dataout.trendagent.ru/images/oe/q1/b958e3b0954bcdfb84686c1c975fdd41.jpeg');

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }

    $post_id = wp_insert_post(array(
        'post_title'   => 'Заголовок поста 1',
        'post_content' => 'Содержимое поста',
        'post_status'  => 'publish',
        'post_type'    => 'post',
    ));


    if (!is_wp_error($post_id)) {
        carbon_set_post_meta($post_id, 'product-id', '12328600010');
        carbon_set_post_meta($post_id, 'product-gallery', [$attachment_id]);
        carbon_set_post_meta($post_id, 'product-description', 'text');
        carbon_set_post_meta($post_id, 'product-price', '1');
        carbon_set_post_meta($post_id, 'product-price-meter', '2');
        carbon_set_post_meta($post_id, 'product-rooms', '3');
        carbon_set_post_meta($post_id, 'product-area', '4');
        carbon_set_post_meta($post_id, 'product-stage', '5');
        carbon_set_post_meta($post_id, 'product-building-type', 'Гомель');
        carbon_set_post_meta($post_id, 'product-finishing', 'Гомель');
        carbon_set_post_meta($post_id, 'product-city', 'Гомель');
        carbon_set_post_meta($post_id, 'product-street', 'Гомель');
        carbon_set_post_meta($post_id, 'product-latitude', '34');
        carbon_set_post_meta($post_id, 'product-longitude', '46');
    } else {
        echo 'Ошибка при создании поста: ' . $post_id->get_error_message();
    }
}

function upload_image_from_url($image_url)
{
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
