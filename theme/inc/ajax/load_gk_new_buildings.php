<?php
require_once get_template_directory() . '/inc/lib/get_query_filter_catalog.php';

function load_gk_new_buildings()
{
    $paged = $_POST['paged'];
    $query = get_query_filter_catalog($paged);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $params = [
                'id' => get_the_ID(),
                'crb_gk_name' => carbon_get_post_meta(get_the_ID(), 'crb_gk_name'),
                'crb_gk_plan' => carbon_get_post_meta(get_the_ID(), 'crb_gk_plan'),
                'crb_gk_gallery' => carbon_get_post_meta(get_the_ID(), 'crb_gk_gallery'),
                'crb_gk_description' => carbon_get_post_meta(get_the_ID(), 'crb_gk_description'),
                'crb_gk_city' => carbon_get_post_meta(get_the_ID(), 'crb_gk_city'),
                'crb_gk_address' => carbon_get_post_meta(get_the_ID(), 'crb_gk_address'),
                'crb_gk_min_price_meter'=> carbon_get_post_meta(get_the_ID(), 'crb_gk_min_price_meter'),
                'crb_gk_permalink' => get_permalink(),
            ];

            get_template_part('template-page/components/card_gk', null, $params);
        }
    }

    wp_reset_postdata();

    $response = ob_get_clean();

    wp_send_json_success($response);
}

add_action('wp_ajax_load_gk_new_buildings', 'load_gk_new_buildings');
add_action('wp_ajax_nopriv_load_gk_new_buildings', 'load_gk_new_buildings');
