<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
  exit;
}

add_action('carbon_fields_register_fields', 'page_ipoteca_fields');
function page_ipoteca_fields()
{
  Container::make('post_meta', 'Настройки страницы')
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'template-page/page-ipoteca.php')
    ->add_tab('Главная', array(
      Field::make('text', 'crb_ipoteca_title', 'Заголовок'),

    ))
    ->add_tab('Шаги для ипотеки', [
      Field::make('text', 'crb_ipoteca_easy_title', 'Заголовок'),
      Field::make('complex', 'crb_ipoteca_easy_field', 'Карточки')
        ->add_fields([
          Field::make('image', 'crb_ipoteca_easy_image', 'Картинка')->set_width(20),
          Field::make('text', 'crb_ipoteca_easy_card_title', 'Заголовок')->set_width(40),
          Field::make('text', 'crb_ipoteca_easy_description', 'Описание')->set_width(40),
        ])
        ->set_max(3)
    ])
    ->add_tab('Частые вопросы', [
      Field::make('text', 'crb_ipoteca_questions_title', 'Заголовок'),
      Field::make('complex', 'crb_ipoteca_questions_field', 'Вопросы')
        ->add_fields([
          Field::make('text', 'crb_ipoteca_questions_card_title', 'Вопрос'),
          Field::make('textarea', 'crb_ipoteca_questions_answer', 'Ответ'),
        ])
    ]);
}
