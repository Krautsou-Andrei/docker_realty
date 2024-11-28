<?php
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';

function update_posts_in_db()
{
    global $wpdb, $update_posts_map;

    try {
        $post_ids = array_keys($update_posts_map);

        $sql_meta = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
        $values_meta = [];

        foreach ($update_posts_map as $post_id => $data_update_meta) {
            foreach ($data_update_meta[0] as $key => $value) {
                $values_meta[] = $wpdb->prepare("(%d, %s, %s)", $post_id, $key, $value);
            }
        }

        $sql_post = "UPDATE {$wpdb->posts} SET post_modified = CASE ID ";
        foreach ($update_posts_map as $post_id => $data_update_post) {
            $sql_post .= $wpdb->prepare("WHEN %d THEN %s ", $post_id, $data_update_post[1]['post_modified']);
        }
        $sql_post .= "END, post_modified_gmt = CASE ID ";
        foreach ($update_posts_map as $post_id => $data_update_post) {
            $sql_post .= $wpdb->prepare("WHEN %d THEN %s ", $post_id, $data_update_post[1]['post_modified_gmt']);
        }
        $sql_post .= "END WHERE ID IN (" . implode(',', array_map('intval', $post_ids)) . ")";

        $sql_meta .= implode(', ', $values_meta);
        $sql_meta .= " ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)";

        $result_meta = $wpdb->query($sql_meta);
        $result_posts = $wpdb->query($sql_post);

        if ($result_meta === false || $result_posts === false) {
            error_log('Ошибка при обновлении: ' . $wpdb->last_error);
        }

        $update_posts_map = [];
        sleep(5);
    } catch (Exception $e) {
        get_message_server_telegram('Ошибка при обновлении постов в базе данных поста из catch:');
        return new WP_Error('create post', 'Ошибка при обновлении поста: ' . $e->getMessage());
    }
}
