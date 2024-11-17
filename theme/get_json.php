<?php
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/enums/names_files.php';
require_once get_template_directory() . '/inc/enums/names_sities.php';

function get_json($city, $name)
{
    $json_url = 'https://dataout.trendagent.ru/' . $city . '/' . $name . '.json';
    $json_folder_path = get_template_directory() . '/json/' . $city . '/';
    $json_file_path = $json_folder_path . $name . '.json';

    if (!file_exists($json_folder_path)) {
        mkdir($json_folder_path, 0755, true);
    }


    $ch = curl_init($json_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);


    $json_content = curl_exec($ch);


    if (curl_errno($ch)) {
        get_message_server_telegram("Не удалось получить JSON для: $name. Ошибка: ", curl_error($ch));
    } else {

        if ($json_content === false || empty($json_content)) {
            get_message_server_telegram("Получен пустой ответ для: ", $name);
        } else {

            file_put_contents($json_file_path, $json_content);
        }
    }


    curl_close($ch);
}

function my_custom_task()
{

    global $names_files, $names_cities;

    get_message_server_telegram('начало', 'обновления базы данных');

    foreach ($names_cities as $key_city => $city) {
        foreach ($names_files as $name) {
            get_json($key_city, $name);
        }
    }

    get_message_server_telegram('завершение', 'обновления базы данных');
}
