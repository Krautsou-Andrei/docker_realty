<?php

require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/page_exists.php';
require_once get_template_directory() . '/inc/lib/update_fields_gk.php';

function create_page($parent_id, $page, $template, $city_name)
{

    $page_title = $page->name;
    $page_slug = get_transliterate($page_title) . '_' .  trim(get_transliterate($city_name));
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
        update_fields_gk($page_enabled_id, $page, $city_name, true);
        return;
    }

    $page_data = array(
        'post_title'    => $page_title,
        'post_status'   => 'publish', // Статус - опубликован
        'post_type'     => 'page', // Тип поста - страница
        'post_parent'   => $parent_id, // ID родительской страницы
        'post_name'     => $page_slug, // Слаг страницы
        'page_template' => $template, // Шаблон страницы
    );

    $page_id = wp_insert_post($page_data);

    if (is_wp_error($page_id)) {
        get_message_server_telegram('Ошибка при создании страницы: ' . $city_name);
        return $page_id;
    }

    update_fields_gk($page_id, $page, $city_name);

    return $page_id;
}
