<?php
function create_category($category_name, $category_slug = '', $parent_id = 0)
{

    global $category_cache;
    // Проверяем, существует ли категория с таким же именем
    $name = strval($category_name);
    $slug = strval($category_slug);
    $term_id = $category_cache[$name] ?? false;
  
    if (!$term_id) {
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
            $category_cache[$name] = $result['term_id'];
            return $result['term_id']; // Возвращаем ID созданной категории
        }
    } else {     
        return $term_id; // Возвращаем ID существующей категории
    }
}
