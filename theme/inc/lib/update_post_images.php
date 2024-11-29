<?php
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/lib/update_posts_images_in_db.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';

function update_post_images($data, $post_id)
{
    global $update_posts_map_images,  $wpdb;

    try {
        $product_id = $data['id'];
        $product_gallery = $data['product_gallery'];
        $product_agent_url = 'https://2bishop.ru/files/avatars/agph_23286_5jpeg.jpg';

        $ids_product_gallery = [];

        foreach ($product_gallery as $image) {
            $attachment_id = @upload_image_from_url($image);
            if (!is_wp_error($attachment_id)) {
                $ids_product_gallery[] = $attachment_id;
            } else {
                $error_message = $attachment_id->get_error_message();
                get_message_server_telegram('Ошибка загрузки картинки план' . $product_id, $error_message);
            }

            if (!$attachment_id) {
                get_message_server_telegram('Ошибка ожидания загрузки картинки' . $product_id, 'Тайминг');
            }
        }

        $id_image_agent = @upload_image_from_url($product_agent_url);
        if (is_wp_error($id_image_agent) || !$id_image_agent) {
            $id_image_agent = '';
        }

        $meta_data = [
            '_product-agent-photo' => $id_image_agent,
            '_product-gallery|||0|value' => !empty($ids_product_gallery) ? $ids_product_gallery[0] : ''
        ];

        $update_posts_map_images[$post_id] = $meta_data;

        if ($update_posts_map_images >= 1000) {
            update_posts_images_in_db();
        }
    } catch (Exception $e) {
        get_message_server_telegram('Ошибка при обновлении картинки объявлений: ');
        return new WP_Error('create post', 'Ошибка при обновлении картинки поста: ' . $e->getMessage());
    }
}
