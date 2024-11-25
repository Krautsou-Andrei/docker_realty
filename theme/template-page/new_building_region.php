<?php
/*
Template Name: Страница новостройки регион
*/

$current_post_id = get_the_ID(); // ID текущего поста
$current_post = get_post($current_post_id);
$type = 'novostrojki';
$option_radio_region = get_the_title();


$where = '/' . $type . '/?';

if (isset($option_radio_region) && $option_radio_region != '') {
    $where .= '&region=' . $option_radio_region;
}

if ($where == '/novostrojki/?') $where = '/novostrojki/';


header('Location: ' . $where);
