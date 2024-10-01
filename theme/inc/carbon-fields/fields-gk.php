<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
    exit;
}

add_action('carbon_fields_register_fields', 'page_new_building_fields_gk');
function page_new_building_fields_gk()
{
    Container::make('post_meta', 'Настройки страницы')
        ->where('post_type', '=', 'page')
        ->where('post_template', '=', 'template-page/new-building-page-gk.php')
        ->add_tab('Главная', array(
            Field::make('text', 'crb_new_building_title_gk', 'Заголовок')->set_help_text('Город указывать не нужно'),
            Field::make('text', 'crb_gk_id', 'Индекс объявления')->set_help_text('12328600010')->set_width(50)->set_required(true),

        ))->add_tab('Основные', [
            Field::make('text', 'crb_gk_name', 'Заголовок')->set_width(100),
            Field::make('media_gallery', 'crb_gk_plan', 'План зайстройки')->set_width(25),
            Field::make('media_gallery', 'crb_gk_gallery', 'Галерея')->set_width(75),
            Field::make('textarea', 'crb_gk_description', 'Описание')->set_width(100),
            Field::make('text', 'crb_gk_city', 'Город')->set_help_text('Новороссийск')->set_width(50),
            Field::make('text', 'crb_gk_address', 'Адрес')->set_help_text('пр-кт Дзержинского')->set_width(50),
            Field::make('text', 'crb_gk_latitude', 'Ширина')->set_help_text('44.75047100002018')->set_width(50),
            Field::make('text', 'crb_gk_longitude', 'Долгота')->set_help_text('37.730149')->set_width(50),
            Field::make('text', 'crb_gk_min_price', 'Минимальная цена')->set_help_text('112 000')->set_width(50),
            Field::make('text', 'crb_gk_min_price_meter', 'Минимальная цена за квадратный метр')->set_help_text('112 000')->set_width(50),
            Field::make('text', 'crb_gk_min_area', 'Минимальная площадь')->set_help_text('20')->set_width(50),
            Field::make('text', 'crb_gk_max_area', 'Максимальная площадь')->set_help_text('50')->set_width(50),
            Field::make('text', 'crb_gk_min_rooms', 'Минимальное количество комнат в ЖК')->set_help_text('1')->set_width(50),
            Field::make('text', 'crb_gk_max_rooms', 'Максимальное количество комнат в ЖК')->set_help_text('5')->set_width(50),
            Field::make('checkbox', 'crb_gk_is_studio', 'Есть студии')->set_option_value('yes')->set_help_text('установить галочку если есть студия')->set_width(50),
            Field::make('checkbox', 'crb_gk_is_house', 'Есть коттеджи')->set_option_value('yes')->set_help_text('установить галочку если есть коттеджи')->set_width(50),
        ]);
}
