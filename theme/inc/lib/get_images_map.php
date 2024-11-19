<?php
function get_images_map()
{
    $attachments = get_posts(array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => -1,
    ));

    $images_map = [];

    if ($attachments) {
        foreach ($attachments as $attachment) {
            $file_name = basename(get_attached_file($attachment->ID));
            $images_map[$file_name] = $attachment->ID;
        }
    }

    return $images_map;
}
