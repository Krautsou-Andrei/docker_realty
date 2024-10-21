<?php
require_once get_template_directory() . '/inc/lib/get_apartaments_on_floor.php';

function get_table_gk()
{
    $params_table_query = $_POST['params_table'];
    $current_liter = $_POST['current_liter'];
    $form_apartamens = $_POST['form_apartamens'];
    $form_area = $_POST['form_area'];


    if (empty($params_table_query)) {
        echo json_encode(['error' => 'Параметр params_table пуст']);
        exit;
    }

    // Пробуем декодировать JSON
    $decoded_params_table_array = json_decode($params_table_query, true);

    // Проверяем, если это null и есть ошибка декодирования
    if ($decoded_params_table_array === null && json_last_error() !== JSON_ERROR_NONE) {
        // Удаляем экранирование
        $params_table_query = stripslashes($params_table_query);

        // Пробуем декодировать снова
        $decoded_params_table_array = json_decode($params_table_query, true);

        // Проверяем на ошибки снова
        if ($decoded_params_table_array === null) {
            echo json_encode(['error' => 'Ошибка декодирования JSON: ' . json_last_error_msg()]);
            exit;
        }
    }

    $categories_rooms_checked = [];
    $categories_area_checked = [];

    foreach ($form_apartamens as $field) {
        if ($field['name'] == 'gk-apartament-rooms') {

            $categories_rooms_checked[] = $field['value'];
        }
    }

    foreach ($form_area as $area) {
        $categories_area_checked[] = intval($area['value']);
    }

    $map_apartaments_init = $decoded_params_table_array['map_apartaments'];
    $map_apartaments = $decoded_params_table_array['map_apartaments'];

    $current_area = [];

    foreach ($map_apartaments[$current_liter]['floors'] as $key => $floor) {

        $filtered = array_filter($map_apartaments[$current_liter]['floors'][$key], function ($item) use ($categories_rooms_checked, $categories_area_checked) {
            if ((in_array($item['rooms'], $categories_rooms_checked) || empty($categories_rooms_checked)) && ((ceil($item['area']) >= $categories_area_checked[0] && ceil($item['area']) <= $categories_area_checked[1]) || empty($categories_area_checked))) {
                return $item;
            }
        });

        $map_apartaments[$current_liter]['floors'][$key] = array_values($filtered);

        if (empty($map_apartaments[$current_liter]['floors'][$key])) {
            unset($map_apartaments[$current_liter]['floors'][$key]);
        }

        foreach ($map_apartaments_init[$current_liter]['floors'][$key] as $apartament) {
            if (in_array($apartament['rooms'], $categories_rooms_checked)) {
                $current_area[] = ceil($apartament['area']);
            }
        }
    }

    $minArea = $categories_area_checked[0];
    $maxArea = $categories_area_checked[1];

    $filteredArea = array_filter($current_area, function ($value) use ($minArea, $maxArea) {
        return $value >= $minArea && $value <= $maxArea;
    });

    if (empty($filteredArea) && !empty($current_area) && !empty($categories_area_checked)) {
        foreach ($map_apartaments_init[$current_liter]['floors'] as $key => $floor) {
            $filtered = array_filter($map_apartaments_init[$current_liter]['floors'][$key], function ($item) use ($categories_rooms_checked) {
                return (in_array($item['rooms'], $categories_rooms_checked) || empty($categories_rooms_checked));
            });

            $map_apartaments[$current_liter]['floors'][$key] = array_values($filtered);
        }
    }

    $map_apartaments[$current_liter]['area'] = array_filter($map_apartaments[$current_liter]['area'], function ($item) use ($current_area) {
        if (in_array($item['name'], $current_area) || empty($current_area)) {
            return $item;
        }
    });

    krsort($map_apartaments[$current_liter]['floors']);

    $floor_apartaments = get_apartaments_on_floor($map_apartaments_init, $current_liter);

    $params_table = [
        'id_page_gk' => $decoded_params_table_array['id_page_gk'],
        'literal' => $decoded_params_table_array['literal'],
        'categories_area' => $decoded_params_table_array['categories_area'],
        'categories_rooms' => $decoded_params_table_array['categories_rooms'],
        'map_apartaments' => $map_apartaments,
        'crb_gk_plan' => $decoded_params_table_array['crb_gk_plan'],
        'current_liter' => $current_liter,
        'categories_rooms_checked' => $categories_rooms_checked,
        'categories_area_checked' => $categories_area_checked,
        'floor_apartaments' => $floor_apartaments,
    ];

    $params_table_init = [
        'id_page_gk' => $decoded_params_table_array['id_page_gk'],
        'literal' => $decoded_params_table_array['literal'],
        'categories_area' => $decoded_params_table_array['categories_area'],
        'categories_rooms' => $decoded_params_table_array['categories_rooms'],
        'map_apartaments' => $map_apartaments_init,
        'crb_gk_plan' => $decoded_params_table_array['crb_gk_plan'],
        'current_liter' => $current_liter,
        'categories_rooms_checked' => $categories_rooms_checked,
        'categories_area_checked' => $categories_area_checked,
        'floor_apartaments' => $floor_apartaments
    ];

    ob_start();

    get_template_part('template-page/components/gk_table', null, $params_table);

    $page_gk_table = ob_get_clean();

    wp_reset_postdata();

    $response = array(
        'pageGkTable' => $page_gk_table,
        'inputTableParams' =>  json_encode($params_table_init),
        'form_apartamens' => $form_apartamens,
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_get_table_gk', 'get_table_gk');
add_action('wp_ajax_nopriv_get_table_gk', 'get_table_gk');
