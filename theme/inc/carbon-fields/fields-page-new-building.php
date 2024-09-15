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

    ))->add_tab('Жилые комплексы', [
      Field::make('complex', 'crb_gk', 'Жилые комплексы')
        ->add_fields([
          Field::make('text', 'crb_gk_name', 'Заголовок')->set_width(100),
          Field::make('image', 'crb_gk_plan', 'План зайстройки')->set_width(25),
          Field::make('media_gallery', 'crb_gk_gallery', 'Галерея')->set_width(75),
          Field::make('textarea', 'crb_gk_description', 'Описание')->set_width(100),
          Field::make('text', 'crb_gk_address', 'Адрес')->set_help_text('пр-кт Дзержинского')->set_width(100),
          Field::make('text', 'crb_gk_latitude', 'Ширина')->set_help_text('44.75047100002018')->set_width(50),
          Field::make('text', 'crb_gk_longitude', 'Долгота')->set_help_text('37.730149')->set_width(50),
        ])
        ->set_max(3)
    ]);
}
