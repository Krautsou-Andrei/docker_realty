<?php
if (!defined("ABSPATH")) {
  exit;
}


add_action('init', 'realty_custom_post_types');
function realty_custom_post_types()
{
  register_post_type('product', [
    'labels' => [
      'name' => 'Товар',
      'singular_name' => 'Товар',
      'add_new' => 'Добавить товар',
      'add_new_item' => 'Добавить новый товар',
      'new_item' => 'Новый товар',
      'all_items' => 'Все товары',
      'view_items' => 'Посмотреть все товары на сайте',
      'search_items' => 'Искать товар',
      'not_found' => 'Товары на найдены',
      'not_found_in_trash' => "Корзина пуста",
      'menu_name' => 'Каталог',

    ],
    'public' => true,
    'show_ui' =>true,
    'has_position' => true,
    'menu_position' => 25,
    'supports' => ['title'],
    'show_in_rest' => false
  ]);
}
