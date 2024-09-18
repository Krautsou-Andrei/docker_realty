<?php

require_once get_template_directory() . '/inc/enums/default_enum.php';

function check_room($id_room)
{

    $appartaments = [1, 22, 1001, 2, 0, 60, 3, 4, 6, 23, 25, 35, 5, 24, 7];
    $house = [30];

    if (in_array($id_room, $appartaments)) {
        return DEFAULT_ENUM::APARTAMENTS;
    }
    if (in_array($id_room, $house)) {
        return DEFAULT_ENUM::HOUSE;
    }
    return '';
}
