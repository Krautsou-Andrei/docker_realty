<?php
function create_category($category_name, $category_slug = '', $parent_id = 0)
{
    // Проверяем, существует ли категория с таким же именем
    $name = strval($category_name);
    $slug = strval($category_slug);
    $term = term_exists($name, 'category');

    if (!$term) {
        // Создаем новую категорию
        $result = wp_insert_term(
            $name, // Название категории
            'category',     // Таксономия
            [
                'slug' => $slug, // Слаг (необязательно)
                'parent' => $parent_id     // ID родительской категории (необязательно)
            ]
        );

        // Проверка на ошибки
        if (is_wp_error($result)) {
            return $result->get_error_message();
        } else {
            return $result['term_id']; // Возвращаем ID созданной категории
        }
    } else {
        return $term['term_id']; // Возвращаем ID существующей категории
    }
}
