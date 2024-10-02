<?php
require_once get_template_directory() . '/inc/enums/template_name.php';

function sort_gk($name_gk)
{

    $args_search_page = array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => TEMPLATE_NAME::CITY_BY_NEW_BUILDING,
        'posts_per_page' => -1, 
    );
   
    $search_pages = get_posts($args_search_page);

    $id_gk = '';
    $page_ids = [];

    if (!empty($search_pages)) {
        foreach ($search_pages as $page) {

            if ($page->post_title === $name_gk) {
                $id_gk = $page->ID;
            }
        }
    }

    if (!empty($id_gk)) {

        $crb_gk_city = carbon_get_post_meta($id_gk, 'crb_gk');

        $page_titles = array_column($crb_gk_city, 'crb_gk_name_sity');

        if (!empty($page_titles)) {
            $args = array(
                'post_type'      => 'page',
                'post_status'    => 'publish',
                'meta_key'       => '_wp_page_template',
                'meta_value'     => TEMPLATE_NAME::PAGE_GK,
                'posts_per_page' => -1,
                'fields'         => 'ids',
            );

            $query = new WP_Query($args);

            $all_page_ids = $query->posts;

            $title_to_id = [];
            foreach ($all_page_ids as $id) {
                $title = get_the_title($id);
                $title_to_id[$title] = $id;
            }


            foreach ($page_titles as $title) {
                if (isset($title_to_id[$title])) {
                    $page_ids[] = $title_to_id[$title];
                }
            }
        }
    }
    return $page_ids;
}
