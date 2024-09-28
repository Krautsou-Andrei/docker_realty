<?php

function get_table_gk()
{
    $params_table_query = $_POST['params_table'];
    $current_liter = $_POST['current_liter'];
    $form_apartamens = $_POST['form_apartamens'];

    $decoded_params_table = urldecode($params_table_query);
    $decoded_params_table_array = [];
    parse_str($decoded_params_table,  $decoded_params_table_array);


    $categories_rooms_checked = [];
    $categories_area_checked = [];

    foreach ($form_apartamens as $field) {
        if ($field['name'] == 'gk-apartament-rooms') {

            $categories_rooms_checked[] = $field['value'];
        }

        if ($field['name'] == 'gk-apartament-area') {
            $categories_area_checked[] = $field['value'];
        }
    }


    $params_table = [
        'literal' => $decoded_params_table_array['literal'],
        'categories_area' => $decoded_params_table_array['categories_area'],
        'categories_rooms' => $decoded_params_table_array['categories_rooms'],
        'map_apartaments' => $decoded_params_table_array['map_apartaments'],
        'crb_gk_plan' => $decoded_params_table_array['crb_gk_plan'],
        'current_liter' => $current_liter,
        'categories_rooms_checked' => $categories_rooms_checked,
        'categories_area_checked' => $categories_area_checked,
    ];

    ob_start();

    get_template_part('template-page/components/gk_table', null, $params_table);

    $page_gk_table = ob_get_clean();

    wp_reset_postdata();

    $response = array(
        'pageGkTable' => $page_gk_table,
        'inputTableParams' =>  http_build_query($params_table),
        'form_apartamens' => $form_apartamens
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_get_table_gk', 'get_table_gk');
add_action('wp_ajax_nopriv_get_table_gk', 'get_table_gk');
