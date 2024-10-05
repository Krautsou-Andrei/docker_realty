<?php

function get_json($name)
{
    $json_url = 'https://obj-estate.ru/json-about/?file=' . $name;

    $json_folder_path = get_template_directory() . '/temp/';
    $json_file_path = $json_folder_path . $name . '.json';

    $max_attempts = 10;
    $attempt = 0;

    while ($attempt < $max_attempts) {
        $attempt++;

        $json_content = @file_get_contents($json_url);

        if ($json_content !== false) {

            file_put_contents($json_file_path, $json_content);
            return;
        }

        sleep(3600);
    }
}

function my_custom_task()
{
    $names_files = [
        'about',
        'buildings',
        'buildingtypes',
        'finishings',
        'room',
        'regions',
        'builders',
        'blocks',
        'apartaments'
    ];

    foreach ($names_files as $name) {
        get_json($name);
    }
}
