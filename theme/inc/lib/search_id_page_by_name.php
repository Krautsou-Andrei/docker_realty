<?php
require_once get_template_directory() . '/inc/lib/create_category.php';
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/lib/page_exists.php';

function search_id_page_by_name($post_title, $paren_page = null, $category_id = null, $template = null, $is_create = false)
{

    $page_slug = get_transliterate($post_title);
    $page_enabled_id = page_exists($page_slug);

    if ($page_enabled_id) {       
        return $page_enabled_id;
    }

    if ($is_create) {
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
