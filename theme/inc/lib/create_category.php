<?php
function create_category($category_name, $category_slug = '', $parent_id = 0)
{
    // Проверяем, существует ли категория с таким же именем
    if (!term_exists($category_name, 'category')) {
        // Создаем новую категорию
        $result = wp_insert_term(
            $category_name, // Название категории
            'category',     // Таксономия
            [
                'slug' => $category_slug, // Слаг (необязательно)
                'parent' => $parent_id     // ID родительской категории (необязательно)
            ]
        );

        // Проверка на ошибки
        if (is_wp_error($result)) {
            return $result->get_error_message();
        } else {
            return 'Категория успешно создана!';
        }
    } else {
        return 'Категория с таким именем уже существует.';
    }
}
