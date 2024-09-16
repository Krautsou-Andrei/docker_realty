<?php


function search_id_page_by_name($paren_page, $post_title)
{

    $args_search_page = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_parent'    => $paren_page, // ID родительской страницы

    );
    // Выполняем запрос
    $search_pages = get_posts($args_search_page);

    if (!empty($search_pages)) {
        foreach ($search_pages as $page) {

            if ($page->post_title === $post_title) {
                return $page->ID; // Возвращаем объект найденной родительской страницы
            }
        }
        return null;
    } else {
        return null;
    }
}
