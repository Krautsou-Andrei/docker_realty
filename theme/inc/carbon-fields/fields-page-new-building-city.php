<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
  exit;
}

add_action('carbon_fields_register_fields', 'page_new_building_fields_city');
function page_new_building_fields_city()
{
  Container::make('post_meta', 'Настройки страницы')
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'template-page/new-building-city.php')
    ->add_tab(
      'Сортировка жк',
      array(
        Field::make('complex', 'crb_gk', 'Жилой комплекс')
          ->add_fields([
            Field::make('text', 'crb_gk_name_sity', 'Заголовок')->set_width(40),
          ])
      )
    );
}
