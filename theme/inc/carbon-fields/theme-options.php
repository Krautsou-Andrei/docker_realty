<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
  exit;
}

add_action('carbon_fields_register_fields', 'realty_attach_theme_options');
function realty_attach_theme_options()
{
  Container::make('theme_options', __('Theme Options'))
    ->add_tab('Общие настройки', array(
      Field::make('image', 'crb_logo_header', 'Логотип в шапке сайта')->set_width(33),
      Field::make('image', 'crb_logo_footer', 'Логотип в подвале сайта')->set_width(33),
      Field::make('image', 'crb_button_up', 'Картинка для кнопки подняться вверх')->set_width(33),
      Field::make('text', 'crb_title_header', 'Название сайта в шапке'),
      Field::make('text', 'crb_header_location', 'Локация в шапке сайта'),
      Field::make('text', 'crb_phone', 'Номер телефона')->set_help_text('Нужно ввести номер телефона С скобками, пробелами и тире. Т.к. он будет выглядеть на сайте')->set_required(true),
      Field::make('text', 'crb_phone_link', 'Номер телефона для ссылок')->set_help_text('Нужно ввыести номер телефона БЕЗ скобок, пробелов и тире')->set_required(true),
      Field::make('text', 'crb_email', 'Email адрес'),
      Field::make('text', 'crb_link_youtube', 'Ссылка youtube')->set_help_text('Например https://www.youtube.com/'),
      Field::make('text', 'crb_link_instagram', 'Ссылка instagram')->set_help_text('Например https://www.instagram.com/'),
      Field::make('text', 'crb_link_avito', 'Ссылка avito')->set_help_text('Например https://www.avito.ru/'),
    
    ))
    ->add_tab('Данные о компании ', array(
      Field::make('text', 'crb_organization', 'организация'),
      Field::make('text', 'crb_inn', 'ИНН')->set_width(50),
      Field::make('text', 'crb_orgnip', 'ОГРНИП')->set_width(50),
      Field::make('text', 'crb_city', 'Город')->set_width(50),
      Field::make('text', 'crb_street', 'Улица')->set_width(50),
      Field::make('text', 'crb_house', 'Дом')->set_width(50),
      Field::make('text', 'crb_ofice', 'Офис')->set_width(50),
      Field::make('text', 'crb_copy', 'Копирайт'),
      Field::make('image', 'crb_tag_footer_one', 'Лейбл под меню 1')->set_width(50),
      Field::make('image', 'crb_tag_footer_two', 'Лейбл под меню 2')->set_width(50),
      Field::make('text', 'crb_tag_footer_one_link', 'Ссылка для первого лейбла')->set_width(50)->set_help_text('Например https://www.instagram.com/'),
      Field::make('text', 'crb_tag_footer_two_link', 'Ссылка для второго лейбла')->set_width(50)->set_help_text('Например https://www.instagram.com/'),

    ))
    ->add_tab('Реклама в меню каталог ', array(
      Field::make('text', 'crb_aside_title', 'заголовок'),
      Field::make('image', 'crb_aside_image', 'Изображение'),
    ))
    ->add_tab('Телеграм боты', array(     
      Field::make('text', 'crb_telegram_bot_order_token', 'Токен')->set_help_text('Токен телеграм бота для заказов @BotFather')->set_width(50),
      Field::make('text', 'crb_telegram_bot_order_chat_id', 'ID чата в который нужно отправить заказ.')->set_width(50),
    ));
}
