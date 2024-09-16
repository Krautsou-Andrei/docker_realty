<?php
if (!defined("ABSPATH")){
  exit;
}

add_action('after_setup_theme', 'crb_load_realty');
function crb_load_realty(){
	require_once (__DIR__ . '/../../vendor/autoload.php');
	\Carbon_Fields\Carbon_Fields::boot();
}

require __DIR__ . '/fields-gk.php';
require __DIR__ . '/theme-options.php';
require __DIR__ . '/fields-product.php';
require __DIR__ . '/fields-page-ipoteca.php';
require __DIR__ . '/fields-page-home.php';
require __DIR__ . '/fields-page-favorites.php';
// require __DIR__ . '/fields-page-new-building.php';

