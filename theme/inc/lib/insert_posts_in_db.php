<?php
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';

function insert_posts_in_db()
{
    global $wpdb, $create_posts_map;

    try {

        $values_posts = [];

        foreach ($create_posts_map as $post_data) {
            $values_posts[] = $wpdb->prepare(
                "(%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %d)",
                $post_data[0]['post_author'],
                $post_data[0]['post_date'],
                $post_data[0]['post_date_gmt'],
                $post_data[0]['post_title'],
                $post_data[0]['post_status'],
                $post_data[0]['comment_status'],
                $post_data[0]['ping_status'],
                $post_data[0]['post_name'],
                $post_data[0]['post_modified'],
                $post_data[0]['post_modified_gmt'],
                $post_data[0]['guid'],
                $post_data[0]['menu_order'],
                $post_data[0]['post_type'],
                $post_data[0]['comment_count']
            );
        }

        $sql_posts = "INSERT INTO {$wpdb->posts} (post_author, post_date, post_date_gmt, post_title, post_status, comment_status, ping_status, post_name,  post_modified, post_modified_gmt, guid, menu_order, post_type, comment_count) 
                  VALUES " . implode(', ', $values_posts);
        $wpdb->query($sql_posts);

        $values_meta = [];

        foreach ($create_posts_map as $post_id => $data_create_meta) {
            foreach ($data_create_meta[1] as $key => $value) {
                $values_meta[] = $wpdb->prepare("(%d, %s, %s)", $post_id, $key, maybe_serialize($value));
            }
        }

        if (!empty($values_meta)) {
            $sql_meta = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES " . implode(', ', $values_meta);
            $result = $wpdb->query($sql_meta);

            if ($result === false) {
                error_log('Ошибка при вставке записей в wp_postmeta: ' . $wpdb->last_error);
            }
        }

        $values_term_relationships = [];
        $map_taxonomy_count = [];

        foreach ($create_posts_map as $post_id => $taxonomies) {
            foreach ($taxonomies[2] as $term_taxonomy_id) {
                $map_taxonomy_count[$term_taxonomy_id] = isset($map_taxonomy_count[$term_taxonomy_id]) ? $map_taxonomy_count[$term_taxonomy_id] + 1 : 1;

                $values_term_relationships[] = [
                    'object_id' => $post_id,
                    'term_taxonomy_id' => $term_taxonomy_id,
                    'term_order' => 0
                ];
            }
        }

        if (!empty($values_term_relationships)) {
            $term_values = [];
            foreach ($values_term_relationships as $data) {
                $term_values[] = $wpdb->prepare("(%d, %d, %d)", $data['object_id'], $data['term_taxonomy_id'], $data['term_order']);
            }
            $sql_terms = "INSERT INTO {$wpdb->term_relationships} (object_id, term_taxonomy_id, term_order ) VALUES " . implode(', ', $term_values);
            $wpdb->query($sql_terms);
        }

        $update_cases = [];
        $term_taxonomy_ids = [];

        foreach ($map_taxonomy_count as $term_taxonomy_id => $count) {
            $update_cases[] = $wpdb->prepare("WHEN term_taxonomy_id = %d THEN count + %d", $term_taxonomy_id, $count);
            $term_taxonomy_ids[] = $term_taxonomy_id;
        }

        $case_statement = implode(' ', $update_cases);

        $term_taxonomy_ids_list = implode(',', array_map('intval', $term_taxonomy_ids));

        if (!empty($case_statement)) {
            $sql = "
                UPDATE {$wpdb->term_taxonomy}
                SET count = CASE
                    $case_statement
                END
                WHERE term_taxonomy_id IN ($term_taxonomy_ids_list)";

            $wpdb->query($sql);
        }

        $create_posts_map = [];
        sleep(5);
    } catch (Exception $e) {
        get_message_server_telegram('Ошибка при вставке постов в базу данных поста из catch:');
        return new WP_Error('create post', 'Ошибка при создании поста: ' . $e->getMessage());
    }
}
