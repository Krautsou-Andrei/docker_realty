<?php
function get_link_page_by_template($template)
{
    $args_page_map = array(
        'post_type'      => 'page', // Тип поста
        'post_status'    => 'publish',
        'meta_key'      => '_wp_page_template', // Мета-ключ для шаблона
        'meta_value'    => $template, // Имя шаблона
        'fields'        => 'ids', // Получаем только ID
    );

    $id_page_map = get_posts($args_page_map);
    $link_page_map = get_permalink(intval($id_page_map[0]));

    return $link_page_map;
}
