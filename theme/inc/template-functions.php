<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package realty
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function realty_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'realty_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function realty_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'realty_pingback_header');
function num_word($value, $words, $show = true)
{
	$num = $value % 100;
	if ($num > 19) {
		$num = $num % 10;
	}
	$out = ($show) ?  '<span>' . $value . '</span>' . ' ' : '';
	switch ($num) {
		case 1:
			$out .= $words[0];
			break;
		case 2:
		case 3:
		case 4:
			$out .= $words[1];
			break;
		default:
			$out .= $words[2];
			break;
	}
	return $out;
}

add_filter('intermediate_image_sizes', 'remove_default_image_sizes');

function remove_default_image_sizes($sizes)
{
	// Удаляем стандартные размеры миниатюр
	$sizes = array_diff($sizes, array('medium', 'large'));

	return $sizes;
}
