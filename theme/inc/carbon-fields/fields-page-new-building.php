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
      Field::make('text', 'crb_new_building_title', 'Заголовок')->set_help_text('Город указывать не нужно'),

    ));
}