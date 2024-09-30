<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined("ABSPATH")) {
  exit;
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-includes/post.php';

$post_id = isset($_GET['post']) ? $_GET['post'] : null;

add_action('carbon_fields_register_fields', 'product_fields');
function product_fields()
{
  Container::make('post_meta', 'Информация о товаре')
    ->where('post_type', '=', 'post')
    ->add_tab('Общие', [
      Field::make('checkbox', 'product_compare_content', 'Фильтр похожие объявления')->set_option_value('yes')->set_width(33),
      Field::make('checkbox', 'product_more_content', 'Фильтр могут подойти')->set_option_value('yes')->set_width(33),
      Field::make('checkbox', 'product_type_aparts', 'Квартира или студия')->set_option_value('yes')->set_help_text('Если квартира является студией, поставить галочку')->set_width(33),
      Field::make('text', 'product-id', 'Индекс объявления')->set_help_text('12328600010')->set_width(50)->set_required(true),
      Field::make('media_gallery', 'product-gallery', 'Галерея')->set_width(75),
      Field::make('textarea', 'product-description', 'Описание'),
    ])
    ->add_tab('Основные значения', [
      Field::make('text', 'product-price', 'Цена')->set_help_text('Цены в рублях, только цифры')->set_width(50)->set_required(true),
      Field::make('text', 'product-price-meter', 'Цена за метр кв.')->set_help_text('Цены в рублях, только цифры')->set_width(50)->set_required(true),
      Field::make('text', 'product-price-mortgage', 'В ипотеку')->set_help_text('Цены в рублях, только цифры')->set_width(50),
      Field::make('text', 'product-year-build', 'Год постройки')->set_help_text('только цифры')->set_width(50),
      Field::make('text', 'product-info-building', 'Дом')->set_help_text('Дом сдан или нет')->set_width(50),
      Field::make('text', 'product-renovation', 'Отделка')->set_width(50),
      Field::make('text', 'product-mortgage', 'Ипотека')->set_help_text('1 - наличие, 0 - отсутсвие')->set_width(50),
      Field::make('text', 'product-label', 'Лейбл')->set_help_text('Только у нас и т.д.')->set_width(50),
      Field::make('text', 'product-rooms', 'Количество комнат')->set_help_text('Указать количество комнат')->set_width(50)->set_required(true),

      Field::make('text', 'product-area-kitchen', 'Площадь кухни')->set_help_text('Указать площадь кухни')->set_width(50)->set_required(true),
      Field::make('text', 'product-area-total-rooms', 'Жилая площадь')->set_help_text('Указать площадь жилую')->set_width(50)->set_required(true),

      Field::make('text', 'product-area', 'Площадь квартиры')->set_help_text('Указать площадь квартиры')->set_width(50)->set_required(true),
      Field::make('text', 'product-stage', 'Этаж')->set_help_text('Указать этаж, например 9')->set_width(50)->set_required(true),
      Field::make('text', 'product-stages', 'Всего этажей')->set_help_text('Указать этаж, например 16')->set_width(50)->set_required(true),
      Field::make('text', 'product-apartamens-number', 'Номер квартиры')->set_help_text('Указать номер квартиры')->set_width(50)->set_required(true),
      Field::make('text', 'product-apartamens-wc', 'Количество туалетов')->set_help_text('Указать количество туалетов')->set_width(50),
    ])
    ->add_tab('О доме', [
      Field::make('text', 'product-developer', 'Застройщик')->set_width(50),
      Field::make('text', 'product-building-type', 'Тип дома')->set_width(50),
      Field::make('text', 'product-facade', 'Фасад')->set_width(50),
      Field::make('text', 'product-finishing', 'Отделка')->set_width(50),
      Field::make('text', 'product-parking-type', 'Парковка')->set_width(50),
      Field::make('text', 'product-payment', 'Оплата')->set_width(50),
      Field::make('text', 'product-contract', 'Договор')->set_width(50),
      Field::make('text', 'product-builder-liter', 'Литера дома')->set_width(50),
    ])
    ->add_tab('Агент', [
      Field::make('text', 'product-agent-phone', 'Телефон')->set_help_text('телефон без знака "+". Например: 79897626258')->set_width(50),
      Field::make('text', 'product-agent-category', 'Категория')->set_width(50),
      Field::make('text', 'product-agent-organization', 'Организация')->set_width(50),
      Field::make('text', 'product-agent-name', 'Имя и фамилия')->set_help_text('Ирина Олеговна')->set_width(50),
      Field::make('text', 'product-agent-email', 'Email')->set_width(50),
      Field::make('image', 'product-agent-photo', 'Фото')->set_width(50),
    ])
    ->add_tab('Локация', [
      Field::make('text', 'product-city', 'Город')->set_width(100)->set_required(true),
      Field::make('text', 'product-sub-locality', 'Район')->set_width(50),
      Field::make('text', 'product-street', 'Улица')->set_width(50),
      Field::make('text', 'product-latitude', 'Ширина')->set_help_text('44.75047100002018')->set_width(50),
      Field::make('text', 'product-longitude', 'Долгота')->set_help_text('37.730149')->set_width(50),

    ]);
}
