<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
    exit;
}

add_action('carbon_fields_register_fields', 'page_new_building_fields');
function page_new_building_fields()
{
    Container::make('post_meta', 'Настройки страницы')
        ->where('post_type', '=', 'page')
        ->where('post_template', '=', 'template-page/new-building-page.php')
        ->add_tab('Главная', array(
            Field::make('checkbox', 'crb_gk_is_not_all_view', 'Не показывать распроданные ЖК')->set_option_value('yes')->set_help_text('установить галочку если нужно скрыть ЖК')->set_width(33),
        ));
}
