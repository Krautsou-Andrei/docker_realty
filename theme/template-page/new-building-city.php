<?php
/*
Template Name: Страница новостройки город
*/

$current_post_id = get_the_ID(); // ID текущего поста
$current_post = get_post($current_post_id);
$type = 'novostrojki';
$option_radio_region = '';
$option_radio_city = '';


if ($current_post->post_parent) {

    $parent_post = get_post($current_post->post_parent);


    $parent_title = get_the_title($parent_post->ID);

    $option_radio_region = $parent_title;
    $option_radio_city = get_the_title();
} else {
    $option_radio_city = get_the_title();
}

$where = '/' . $type . '/?';

if (isset($option_radio_region) && $option_radio_region != '') {
    $where .= '&region=' . $option_radio_region;
}

if (isset($option_radio_city) && $option_radio_city != '') {
    $where .= '&city=' . $option_radio_city;
}

if ($where == '/novostrojki/?') $where = '/novostrojki/';


header('Location: ' . $where);
