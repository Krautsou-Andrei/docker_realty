<?php
if (!defined("ABSPATH")) {
	exit;
}

/**
 * Enqueue scripts and styles.
 */
function realty_scripts()
{
	wp_enqueue_style('realty-swiper-style', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '1.0.0');
	wp_enqueue_style('realty-style', get_template_directory_uri() . '/assets/css/style.min.css', array(), '1.0.0');

	wp_enqueue_script('realty-get-json', get_template_directory_uri() . '/js/get-json.js', array('jquery'), 'null', true);

	wp_enqueue_script('realty-swiper-script', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), 'null', true);
	wp_enqueue_script('realty-script', get_template_directory_uri() . '/assets/js/app.min.js', array('jquery'), 'null', true);
	wp_enqueue_script('custom-script-lazy-new-buildings', get_template_directory_uri() . '/js/lazy-script-new-buildings.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-lazy-new-gk-buildings', get_template_directory_uri() . '/inc/ajax/load_gk_new_buildings.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-load-documents', get_template_directory_uri() . '/js/load-popup-documents.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-filter-documents', get_template_directory_uri() . '/js/filter-documents.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-pagination-documents', get_template_directory_uri() . '/js/pagination-documents.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-filter-posts-single-page', get_template_directory_uri() . '/js/filter-posts-single-page.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-filter-posts-home-page', get_template_directory_uri() . '/js/filter-posts-home-page.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-get-page-gk-table', get_template_directory_uri() . '/inc/ajax/get_table_gk.js', array('jquery'), '1.0', true);
	wp_enqueue_script('custom-script-get-body-popup-apartament', get_template_directory_uri() . '/inc/ajax/get_body_popup_apartament.js', array('jquery'), '1.0', true);
	wp_localize_script('custom-script-get-body-popup-apartament', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-lazy-new-buildings', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-lazy-new-gk-buildings', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-load-documents', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-filter-documents', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-pagination-documents', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-filter-posts-single-page', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-filter-posts-home-page', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('custom-script-get-page-gk-table', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'realty_scripts');
