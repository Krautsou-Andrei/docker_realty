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
        return $page_id;
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
