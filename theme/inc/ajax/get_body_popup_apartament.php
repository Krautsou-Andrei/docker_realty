<?php

function get_body_popup_apartament()
{
    $id_apartament = $_POST['id_apartament'];

    $area =carbon_get_post_meta($id_apartament, 'product-area');
    $image = isset(carbon_get_post_meta($id_apartament, 'product-gallery')[0]) ? carbon_get_post_meta($id_apartament, 'product-gallery')[0] : '';
    $price = carbon_get_post_meta($id_apartament, 'product-price');
    $price_meter = carbon_get_post_meta($id_apartament, 'product-price-meter');

    $params = [
        'area' => $area,
        'image' => $image,
        'price' => $price,
        'price_meter' => $price_meter
    ];


    ob_start();

    get_template_part('template-page/blocks/body_popup_apartament', null, $params);

    $body_popup_apartament = ob_get_clean();
    wp_reset_postdata();



    $response = array(
        'body_popup_apartament' => $body_popup_apartament,
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_get_body_popup_apartament', 'get_body_popup_apartament');
add_action('wp_ajax_nopriv_get_body_popup_apartament', 'get_body_popup_apartament');
