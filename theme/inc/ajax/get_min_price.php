<?php

require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/get_slug_page.php';

function get_min_price()
{
    $id_page_gk = $_POST['id_page_gk'];
    $post = get_post($id_page_gk);
    $slug_page = $post ? $post->post_name : '';

    $id_category_gk = get_term_by('slug', $slug_page, 'category')->term_id;

    $args_gk = [
        'post_type' => 'post', // Тип поста (может быть 'post', 'page', 'custom-post-type' и т.д.)
        'posts_per_page' => -1, // Количество постов на странице (-1 для вывода всех постов)
        'category__and' => [$id_category_gk],
    ];

    $query = new WP_Query($args_gk);

    $price_meter_all = [];

    if ($query->have_posts()) {

        while ($query->have_posts()) {
            $query->the_post();
            $id_post = get_the_ID();

            $price_meter = carbon_get_post_meta($id_post, 'product-price-meter');

            $price_meter_all[] = $price_meter;
        }

        wp_reset_postdata();
    }

    $min_price = $price_meter_all ? number_format(round(min($price_meter_all)), 0, '.', ' ') : '';

    wp_reset_postdata();

    $response = array(
        'minPrice' => $min_price,
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_get_min_price', 'get_min_price');
add_action('wp_ajax_nopriv_get_min_price', 'get_min_price');
