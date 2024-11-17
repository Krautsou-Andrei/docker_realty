<?php
require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';

function search_id_page_by_name($post_title, $paren_page = null, $category_id = null, $template = null, $is_create = false)
{
    $args_search_page = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,

    );
    if ($paren_page !== null) {
        $args_search_page['post_parent'] = $paren_page;
    }

    $search_pages = get_posts($args_search_page);

    if (!empty($search_pages)) {
        foreach ($search_pages as $page) {
            if ($page->post_title === $post_title) {
                return $page->ID;
            }
        }
    }

    if ($is_create) {
        $page_slug = get_transliterate($post_title);

        $args_new_page = [
            'post_title'   => $post_title,
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'     => $page_slug,
        ];

        if ($paren_page !== null) {
            $args_new_page['post_parent'] = $paren_page;
        }

        if (!empty($template)) {
            $args_new_page['page_template'] = $template;
        }

        $id_city_category = create_category($post_title, get_transliterate($post_title), $category_id ? $category_id : CATEGORIES_ID::REGIONS);

        $new_page_id = wp_insert_post($args_new_page);

        if (is_wp_error($new_page_id)) {
            return null;
        }

        return $new_page_id;
    }

    return null;
}
