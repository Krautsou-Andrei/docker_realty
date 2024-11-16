<?php
require_once get_template_directory() . '/inc/lib/get_transliterate.php';
require_once get_template_directory() . '/inc/enums/template_name.php';


function search_id_page_by_name($paren_page, $post_title)
{
    $args_search_page = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post_parent'    => $paren_page,
    );

    $search_pages = get_posts($args_search_page);

    if (!empty($search_pages)) {
        foreach ($search_pages as $page) {
            if ($page->post_title === $post_title) {
                return $page->ID;
            }
        }
    }

    $page_slug = get_transliterate($post_title);
    $template = TEMPLATE_NAME::CITY_BY_NEW_BUILDING;

    $new_page_id = wp_insert_post(array(
        'post_title'   => $post_title,
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_parent'  => $paren_page,        
        'post_name'     => $page_slug, 
        'page_template' => $template, 
    ));
    
    

    if (is_wp_error($new_page_id)) {
        return null;
    }

    return $new_page_id;
}
