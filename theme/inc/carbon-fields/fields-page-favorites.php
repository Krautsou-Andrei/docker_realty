<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
  exit;
}

add_action('carbon_fields_register_fields', 'page_favorites_fields');
function page_favorites_fields()
{
  Container::make('post_meta', 'Настройки страницы')
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'template-page/favorites-page.php')
    ->add_tab('Главная', array(
      Field::make('text', 'crb_favorites_title', 'Заголовок'),

    ));
}
