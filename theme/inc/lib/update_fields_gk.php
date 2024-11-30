<?php
require_once get_template_directory() . '/inc/lib/is_house.php';
require_once get_template_directory() . '/inc/lib/get_message_server.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';
require_once get_template_directory() . '/inc/lib/upload_image_from_url.php';

function update_fields_gk($post_id, $block, $name_city, $is_old = false)
{
    try {
        $ids_gallery_plan = [];
        $upload_errors = [];

        if (!empty($block->plan) && !$is_old) {
            foreach ($block->plan as $render) {
                if (!empty($render)) {
                    $attachment_id = @upload_image_from_url($render);
                    if (!is_wp_error($attachment_id)) {
                        $ids_gallery_plan[] = $attachment_id;
                    } else {
                        $upload_errors[] = $render;
                        $error_message = $attachment_id->get_error_message();
                        get_message_server_telegram('Ошибка загрузки картинки план' . $block->name, $error_message);
                    }

                    if (!$attachment_id) {
                        get_message_server_telegram('Ошибка ожидания загрузки картинки' . $block->name, $error_message);
                    }
                }
            }
        }
        $ids_gallery = [];

        if (!empty($block->renderer)  && !$is_old) {
            foreach ($block->renderer as $render) {
                if (!empty($render)) {
                    $attachment_id = @upload_image_from_url($render);
                    if (!is_wp_error($attachment_id)) {
                        $ids_gallery[] = $attachment_id;
                    } else {
                        $upload_errors[] = $render;
                        $error_message = $attachment_id->get_error_message();
                        get_message_server_telegram('Ошибка загрузки картинки ' . $block->name, $error_message);
                    }

                    if (!$attachment_id) {
                        get_message_server_telegram('Ошибка ожидания загрузки картинки' . $block->name, $error_message);
                    }
                }
            }
        }

        if (!$is_old) {
            global $wpdb;

            carbon_set_post_meta($post_id, 'crb_gk_plan', $ids_gallery_plan);

            $description = $block->description;
            $description = preg_replace('/<a.*?>(.*?)<\/a>/', '', $description);
            $description = preg_replace('/<p.*?>(.*?)<\/p>/', '$1<br>', $description);

            $meta_data = [
                '_crb_gk_id'              => $block->_id,
                '_crb_gk_name'            => $block->name,
                '_crb_gk_description'     => $description,
                '_crb_gk_city'            =>  $name_city,
                '_crb_gk_address'         => !empty($block->address[0]) ? $block->address[0] : '',
                '_crb_gk_latitude'        => !empty($block->geometry->coordinates[0]) ? $block->geometry->coordinates[0] : '',
                '_crb_gk_longitude'       => !empty($block->geometry->coordinates[1]) ? $block->geometry->coordinates[1] : '',               
                '_crb_gk_is_not_view'     => '',              
                '_crb_gk_min_price'       => '',
                '_crb_gk_min_price_meter' => '',
                '_crb_gk_max_price'       => '',
                '_crb_gk_max_price_meter' => '',
                '_crb_gk_min_area'        => '',
                '_crb_gk_max_area'        => '',
                '_crb_gk_min_rooms'       => '',
                '_crb_gk_max_rooms'       => '',
                '_crb_gk_is_studio'       => '',
                '_crb_gk_is_house'        => '',
                '_crb_gk_rooms'           => ''

            ];

            $values = [];
            $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

            foreach ($meta_data as $key => $value) {
                $values[] = $wpdb->prepare("(%d, %s, %s)", $post_id, $key, $value);
            }

            $sql .= implode(', ', $values);

            $result = $wpdb->query($sql);

            if ($result === false) {
                echo "Ошибка при вставке: " . $wpdb->last_error;
            }
        }

        if (is_house($block->name)) {
            carbon_set_post_meta($post_id, 'crb_gk_is_house', 'yes');
        }

        $updated_page = array(
            'ID'         => $post_id,
            'post_title' => $block->name,
        );
        wp_update_post($updated_page);

        if (!empty($upload_errors)) {
            get_message_server_telegram('Незагруженные картинки ' . $block->name, implode(', ', $upload_errors));
        }
    } catch (Exception $e) {

        get_message_server_telegram('Ошибка создания страницы ' . $block->name, $e->getMessage());
    }
}
