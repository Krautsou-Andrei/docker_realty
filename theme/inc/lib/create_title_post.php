<?php
require_once get_template_directory() . '/inc/lib/check_room.php';
require_once get_template_directory() . '/inc/enums/default_enum.php';

function create_title_post($id_room, $area, $floor)
{
    return check_room($id_room) . ' ' . DEFAULT_ENUM::FLOOR . ' ' .  $floor . ', ' . DEFAULT_ENUM::AREA . ' ' . $area . ' м2';
}
