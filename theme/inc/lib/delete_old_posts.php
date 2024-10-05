<?php
function delete_old_posts()
{
    $date_query = new DateTime();
    $date_query->modify('-5 day');
    $timestamp = $date_query->getTimestamp();

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 100,
        'date_query'     => [
            [
                'column' => 'post_modified',
                'before' => date('Y-m-d H:i:s', $timestamp),
            ],
        ],
        'fields' => 'ids',
    ];

    $post_ids = new WP_Query($args);

    while ($post_ids->have_posts()) {
        foreach ($post_ids->posts as $post_id) {
            wp_delete_post($post_id, true);
        }

        $args['offset'] = $post_ids->post_count;
        $post_ids = new WP_Query($args);
    }

    wp_reset_postdata();
}
