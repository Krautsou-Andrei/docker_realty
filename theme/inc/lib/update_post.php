<?php
function update_post($data, $post_id)
{
    global $wpdb;
    date_default_timezone_set('Europe/Moscow');  

    $product_price = $data['product_price'];
    $product_price_meter = $data['product_price_meter'];
    $product_year_build = $data['product_year_build'];
    $product_finishing = $data['product_finishing'];

    $date_build = '';

    if (!empty($product_year_build)) {
        $date = new DateTime($product_year_build);
        $date_build = $date->format("Y");
    }  

    $data_update_post = [
        '_product-price' => $product_price,
        '_product-price-meter' => $product_price_meter,
        '_product-year-build' => $date_build,
        '_product-finishing' => $product_finishing,       
    ];
    
    // Подготовка SQL-запроса
    $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
    
    $values = [];
    foreach ($data_update_post as $key => $value) {
        $values[] = $wpdb->prepare("(%d, %s, %s)", $post_id, $key, $value);
    }
    
    $sql .= implode(', ', $values);
    $sql .= " ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)";
    
    // Выполнение запроса
    $result = $wpdb->query($sql);
    
    if ($result === false) {
        // Обработка ошибки
        error_log('Ошибка при обновлении метаданных: ' . $wpdb->last_error);
    }
    
    update_post_meta($post_id, 'post_modified', current_time('mysql'));
    update_post_meta($post_id, 'post_modified_gmt', current_time('mysql', 1));
    
}
