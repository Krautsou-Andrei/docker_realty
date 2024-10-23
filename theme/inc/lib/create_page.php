<?php

require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/update_fields_gk.php';

function create_page($parent_id, $page, $template, $city_name)
{

    $page_title = $page->name;
    $page_slug = get_transliterate($page_title);
    $page_enabled_id = page_exists($page_slug);

    create_category($page_title, $page_slug, CATEGORIES_ID::GK);

    $value_exists = false;

    $crb_gk_city = carbon_get_post_meta($parent_id, 'crb_gk');

    foreach ($crb_gk_city as $gk) {
        if ($gk['crb_gk_name_sity'] === $page_title) {
            $value_exists = true;
            break;
        }
    }

    if (!$value_exists) {
        $new_value = array(
            'crb_gk_name_sity' => $page_title,
        );
        $crb_gk_city[] = $new_value;
        carbon_set_post_meta($parent_id, 'crb_gk', $crb_gk_city);
    }

    // Проверка, существует ли страница с таким же слагом
    if ($page_enabled_id) {
        update_fields_gk($page_enabled_id, $page, $city_name);
        return;
    }

    // Создание массива данных для новой страницы
    $page_data = array(
        'post_title'    => $page_title,
        'post_status'   => 'publish', // Статус - опубликован
        'post_type'     => 'page', // Тип поста - страница
        'post_parent'   => $parent_id, // ID родительской страницы
        'post_name'     => $page_slug, // Слаг страницы
        'page_template' => $template, // Шаблон страницы
    );

    // Вставка страницы в БД
    $page_id = wp_insert_post($page_data);

    // Проверка на ошибки
    if (is_wp_error($page_id)) {
        $error_message = $page_id->get_error_message(); // Получаем сообщение об ошибке
        $to = 'andreysv2006@yandex.by'; // Замените на нужный адрес электронной почты
        $subject = 'Ошибка при вставке поста';
        $body = 'Произошла ошибка при вставке поста: ' . $error_message;
        $headers = 'From: no-reply@example.com' . "\r\n" . // Убедитесь, что у вас правильный адрес отправителя
            'Reply-To: no-reply@example.com' . "\r\n"; // Убедитесь, что у вас правильный адрес для ответа


        mail($to, $subject, $body, $headers);

        error_log('Ошибка при вставке поста: ' . $error_message);

        return $page_id; // Возвращаем объект ошибки
    }

    // Установка шаблона страницы
    if ($template) {
        update_post_meta($page_id, '_wp_page_template', $template);
        update_fields_gk($page_id, $page, $city_name);
    }

    return $page_id;
}

function page_exists($slug)
{
    $args = array(
        'name'        => $slug, // Слаг
        'post_type'   => 'page', // Тип поста
        'post_status' => 'publish', // Только опубликованные страницы
        'numberposts' => 1, // Только одна страница
    );

    $pages = get_posts($args);

    if (!empty($pages)) {
        return $pages[0]->ID;
    }
    return !empty($pages);
}
