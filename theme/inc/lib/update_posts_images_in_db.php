<?php
function update_posts_images_in_db()
{
    global $update_posts_map_images,  $wpdb;
    try {
        foreach ($update_posts_map_images as $update_post) {
            $values_meta = [];

            foreach ($update_post as $post_id => $data_update_meta) {
                foreach ($data_update_meta as $key => $value) {
                    $values_meta[] = $wpdb->prepare("(%d, %s, %s)", $post_id, $key, $value);
                }
            }

            if (!empty($values_meta)) {
                $sql_meta = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
                $sql_meta .= implode(', ', $values_meta);
                $sql_meta .= " ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)";

                $result_meta = $wpdb->query($sql_meta);

                if ($result_meta === false) {
                    error_log('Ошибка при обновлении: ' . $wpdb->last_error);
                }
            }
            $update_posts_map_images = [];
            sleep(5);
        }
    } catch (Exception $e) {
        return new WP_Error('create post', 'Ошибка при обновлении картинки поста: ' . $e->getMessage());
    }
}
