<?php

/**
 * realty functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package realty
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function realty_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on realty, use a find and replace
		* to change 'realty' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('realty', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'header-menu' => esc_html__('Primary', 'realty'),
			'catalog-menu' => esc_html__('Catalog', 'realty'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'realty_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'realty_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function realty_content_width()
{
	$GLOBALS['content_width'] = apply_filters('realty_content_width', 640);
}
add_action('after_setup_theme', 'realty_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function realty_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'realty'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'realty'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'realty_widgets_init');



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

require get_template_directory() . '/../../mlsfiles/inc/custom-pages.php';
require get_template_directory() . '/inc/template-enqueue.php';
require get_template_directory() . '/inc/carbon-fields/index.php';
require get_template_directory() . '/inc/load-lazy-new-buildings.php';
require get_template_directory() . '/inc/load-popup-employees-documents.php';
require get_template_directory() . '/inc/filter-documents.php';
require get_template_directory() . '/inc/pagination-documents.php';
require get_template_directory() . '/inc/filter-post-single-page.php';
require get_template_directory() . '/inc/filter-post-home-page.php';

// require get_template_directory() . '/inc/template-hooks.php';

// global $queue_custom_product;
// $queue_custom_product = new QueueCustomProduct();

function my_after_xml_import_function()
{
	$args = array(
		'post_type'   => 'post', // Тип поста (например, 'post', 'page' и т.д.)
		'numberposts' => -1, // Количество постов для получения (-1 для получения всех)
		'post_status' => 'any', // Статус поста (например, 'publish', 'draft', 'private' и т.д.)
	);

	$posts = get_posts($args);

	if ($posts) {
		foreach ($posts as $post) {
			$post_id = $post->ID;

			delete_old_gallery_after_import($post_id);
			sync_new_gallery_after_import($post_id);

			delete_post_meta($post_id, '_product-gallery');
		}
	}
}

function delete_old_gallery_after_import($post_id)
{
	$existing_gallery = get_post_meta($post_id, '_product-gallery', true);
	$existing_gallery_old = carbon_get_post_meta($post_id, 'product-gallery');

	if (is_array($existing_gallery)) {
		$length = count($existing_gallery);
		echo "Длина массива: " . $length;
	} else {
		echo "Переменная не является массивом";
	}


	if (!empty($existing_gallery_old) && !empty($existing_gallery)) {
		foreach ($existing_gallery_old as $image_id) {

			// Удаляем изображение из базы данных
			$deleted = wp_delete_attachment($image_id, true);
		}
	}
}

function sync_new_gallery_after_import($post_id)
{
	$existing_gallery = get_post_meta($post_id, '_product-gallery', true);
	$gallery_ids = [];

	if (!empty($existing_gallery)) {
		foreach ($existing_gallery as $slide) {
			if (!empty($slide)) {
				foreach ($slide as $image_url) {

					$tmp = download_url($image_url);

					if (!is_wp_error($tmp)) {
						// Обработка ошибки при загрузке изображения

						$file_array = array(
							'name'     => basename($image_url),
							'tmp_name' => $tmp
						);

						$image_id = media_handle_sideload($file_array);
						$existing_image_url = wp_get_attachment_url($image_id);
						$existing_image_id = attachment_url_to_postid($existing_image_url);

						if (!is_wp_error($image_id)) {
							// Изображение загружено успешно, возвращаем его ID
							$gallery_ids[] = $image_id;
						} else {
							// Обработка ошибки при загрузке изображения
							echo 'Ошибка при загрузке изображения: ' . $image_id->get_error_message();
						}
					} else {
						echo 'Ошибка при загрузке изображения: ' . $tmp->get_error_message();
						continue;
					}
				}
			}
		}
		carbon_set_post_meta($post_id, 'product-gallery', $gallery_ids);
	}
}

// Класс очереди
// class QueueCustomProduct
// {

// 	private $posts = [];

// 	public function add($post)
// 	{
// 		$this->posts[] = $post;
// 	}

// 	public function getNext()
// 	{
// 		return array_shift($this->posts);
// 	}

// 	public function isEmpty()
// 	{
// 		return empty($this->posts);
// 	}

// 	public function complete($post)
// 	{
// 		foreach ($this->posts as $key => $item) {
// 			if ($item->ID == $post->ID) {
// 				unset($this->posts[$key]);
// 				break;
// 			}
// 		}
// 	}
// }

/**
 * Добавление кнопки на панель управления
 */

function my_custom_page_callback()
{
}

add_action('admin_init', 'my_custom_page_callback');

function add_custom_button_to_admin_bar()
{
	add_menu_page(
		'Обновить после импорта',     // Заголовок кнопки
		'Обновить после импорта',     // Текст кнопки в меню
		'manage_options', // Роль пользователя, которой требуется доступ к кнопке
		'my-custom-page', // Уникальный идентификатор страницы, на которую будет перенаправлен пользователь при нажатии на кнопку
		'my_custom_button_callback',               // Функция обратного вызова
		'dashicons-update', // Иконка для кнопки (можно использовать CSS-класс или URL к изображению)
		99                // Позиция кнопки в меню (число)
	);
}
add_action('admin_menu', 'add_custom_button_to_admin_bar');

// function fetch_json_and_send_email() {
//     $url = 'https://dataout.trendagent.ru/krasnodar/buildingtypes.json';

//     $response = wp_remote_get($url);

//     if (is_wp_error($response)) {
//         return; 
//     }

//     $json_data = wp_remote_retrieve_body($response);
//     $data = json_decode($json_data, true);
    
//     // Путь к файлу
//     $upload_dir = wp_upload_dir();
//     $file_path = $upload_dir['path'] . '/buildingtypes.json';

//     // Сохраняем JSON в файл
//     file_put_contents($file_path, $json_data);

//     $to = 'andreysv2006@yandex.by';
//     $subject = 'Полученные данные JSON';
//     $message = 'Прикреплен файл с данными JSON.';

//     // Отправляем письмо с вложением
//     wp_mail($to, $subject, $message, [], [$file_path]);

//     // Удаляем файл после отправки (по желанию)
//     unlink($file_path);
// }

// add_action('init', 'fetch_json_and_send_email');






