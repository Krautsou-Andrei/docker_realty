<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package realty
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<script>
		// async function send() {
		//     console.log("send");
		//     try {
		//         // Получаем JSON с удаленного сервера
		//         const response = await fetch('https://dataout.trendagent.ru/krasnodar/buildings.json');
		//         const jsonData = await response.json();

		//         // Преобразуем JSON в строку
		//         const jsonString = JSON.stringify(jsonData, null, 2); // Форматирование для читаемости

		//         // Создаем Blob из строки JSON
		//         const blob = new Blob([jsonString], { type: 'application/json' });

		//         // Создаем URL для Blob
		//         const url = URL.createObjectURL(blob);

		//         // Создаем ссылку для скачивания
		//         const a = document.createElement('a');
		//         a.href = url;
		//         a.download = 'buildings.json'; // Имя файла
		//         document.body.appendChild(a);
		//         a.click(); // Симулируем клик для скачивания
		//         document.body.removeChild(a); // Удаляем ссылку

		//         // Освобождаем URL
		//         URL.revokeObjectURL(url);
		//     } catch (error) {
		//         console.error('Ошибка:', error);
		//     }
		// }
		// send();
	</script>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'realty'); ?></a>
	</div>
	<div class="wrapper">
		<header class="header" data-header>
			<div class="header__container top" data-header-top>
				<div class="header__info">
					<div class="header__text">
						<?php $crb_title_header = carbon_get_theme_option('crb_title_header'); //Получить title сайта
						$crb_header_location = carbon_get_theme_option('crb_header_location'); //Получить локацию в header
						?>
						<p class="header__text-description"><?php echo $crb_title_header ?></p>
						<div class="label-option-radio-wrapper label label-city" id="filter-city" data-checked>
							<div class="option-radio">
								<span class="option-radio__label" data-checked-view data-default-value="Новороссийск" style="cursor: default;">Новороссийск</span>
							</div>
						</div>
					</div>
					<div class=" header__menu-burger menu-burger" data-menu-burger>
						<button class="menu-burger__button icon-menu" type="button" aria-expanded="false" aria-label="button burger" data-menu-burger-button>
							<span></span>
						</button>
						<nav class="menu-burger__wrapper" data-menu-burger-content>
							<div class="menu-burger-top" data-menu-list>
								<?php
								function add_data_attributes_to_menu_items($item_output, $item, $depth, $args)
								{
									// Добавьте здесь свои data-атрибуты и их значения
									$data_attributes = array(
										'data-close-burger-link',
									);

									foreach ($data_attributes as $attribute) {
										$item_output = str_replace('<a', '<a ' . $attribute, $item_output);
									}

									return $item_output;
								}
								add_filter('walker_nav_menu_start_el', 'add_data_attributes_to_menu_items', 10, 4);

								wp_nav_menu(
									array(
										'theme_location' => 'header-menu',
										'menu_id'        => 'primary-menu',
										'menu_class'     => 'menu-burger__list',
										'walker'         => new Custom_Menu_Walker(),
									)
								);

								class Custom_Menu_Walker extends Walker_Nav_Menu
								{
									private $menu_items = array();
									private $inserted = false;

									public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
									{
										array_push($this->menu_items, $item);

										if (!$this->inserted && count($this->menu_items) === 3) {
											$output .= '<ul class="catalog-menu-burger menu--hidden" data-catalog-menu-burger>';
											$output .= wp_nav_menu(
												array(
													'theme_location' => 'catalog-menu',
													'menu_id'        => 'sub-menu-catalog',
													'menu_class'     => 'sub-menu-catalog',
													'echo'           => false,
												)
											);
											$output .= '</ul>';
											$this->inserted = true;
										}

										$classes = empty($item->classes) ? array() : (array) $item->classes;
										$classes[] = 'menu-item-' . $item->ID;

										// Убираем ссылку у второго элемента и добавляем атрибут
										if (count($this->menu_items) === 2) {
											$output .= '<li class="' . implode(' ', $classes) . '" data-button-catalog-burger>';
											$output .= $item->title;
										}

										if (count($this->menu_items) === 2) {
											// Пропускаем второй элемент
											return;
										}

										$output .= $args->after;

										parent::start_el($output, $item, $depth, $args, $id);
									}
								}
								?>
								<div class="menu-burger__filter"><?php get_template_part('template-page/components/burger-menu-filter') ?></div>

							</div>
							<div class="menu-burger__footer burger-footer">
								<div class="burger-footer__contact">
									<?php get_template_part('template-page/components/contact-phone-footer') ?>
								</div>
								<div class="burger-footer__tags">
									<?php
									$crb_tag_footer_id_1 = carbon_get_theme_option('crb_tag_footer_one'); // Получить ID изображения
									$crb_tag_footer_id_2 = carbon_get_theme_option('crb_tag_footer_two'); // Получить ID изображения
									$crb_tag_footer_one_link = carbon_get_theme_option('crb_tag_footer_one_link');
									$crb_tag_footer_two_link = carbon_get_theme_option('crb_tag_footer_two_link');
									$link_target_footer_one = !empty($crb_tag_footer_one_link) ? 'target="_blank"' : '';
									$link_target_footer_two = !empty($crb_tag_footer_two_link) ? 'target="_blank"' : '';

									if (!empty($crb_tag_footer_id_1)) {
										$crb_tag_footer_1_url = wp_get_attachment_image_src($crb_tag_footer_id_1, 'full');
										if ($crb_tag_footer_1_url) {
											echo '<a ' . $link_target_footer_one . ' href="' . $crb_tag_footer_one_link . '"><img src="' . $crb_tag_footer_1_url[0] . '" alt=""></a>';
										}
									}
									?>
									<?php if (!empty($crb_tag_footer_id_2)) {
										$crb_tag_footer_2_url = wp_get_attachment_image_src($crb_tag_footer_id_2, 'full');
										if ($crb_tag_footer_2_url) {
											echo '<a ' . $link_target_footer_two . ' href="' . $crb_tag_footer_two_link . '"><img src="' . $crb_tag_footer_2_url[0] . '" alt=""></a>';
										}
									} ?>
									<div class="menu-burger__rating">
										<div class="rating-wrapper">
											<div class="rating">
												<span class="rating__number">4,7</span>
												<ul class="rating__stars">
													<li style="background: url(<?php bloginfo('template_url'); ?>/assets/images/star.svg) no-repeat;"></li>
													<li style="background: url(<?php bloginfo('template_url'); ?>/assets/images/star.svg) no-repeat;"></li>
													<li style="background: url(<?php bloginfo('template_url'); ?>/assets/images/star.svg) no-repeat;"></li>
													<li style="background: url(<?php bloginfo('template_url'); ?>/assets/images/star.svg) no-repeat;"></li>
													<li style="background: url(<?php bloginfo('template_url'); ?>/assets/images/star.svg) no-repeat;"></li>
												</ul>
											</div>
											<span class="rating__description">Наш рейтинг</span>
										</div>
									</div>
								</div>
							</div>
						</nav>
					</div>
					<a href="/" class="header__logo logo">
						<?php $crb_logo_header_id = carbon_get_theme_option('crb_logo_header'); // Получить ID изображения
						$crb_logo_header_mobile_id = carbon_get_theme_option('crb_logo_footer'); // Получить ID изображения

						if (!empty($crb_logo_header_id)) {
							$crb_logo_header_url = wp_get_attachment_image_src($crb_logo_header_id, 'full');
							$logo_header_alt = get_post_meta($crb_logo_header_id, '_wp_attachment_image_alt', true);
							if ($crb_logo_header_url) {
								echo '<img src="' . $crb_logo_header_url[0] . '" alt="' . $logo_header_alt . '" width="143" height="72">';
							}
						}

						if (!empty($crb_logo_header_mobile_id)) {
							$crb_logo_header_mobile_url = wp_get_attachment_image_src($crb_logo_header_mobile_id, 'full');
							$logo_header_mobile_alt = get_post_meta($crb_logo_header_id, '_wp_attachment_image_alt', true);
							if ($crb_logo_header_mobile_url) {
								echo '<img src="' . $crb_logo_header_mobile_url[0] . '" alt="' .	$logo_header_mobile_alt . '"  width="133" height="28">';
							}
						}
						?>
					</a>
					<div class="header__contacts">
						<div class="contact-phone">
							<div class="contact-phone__phone">
								<?php $crb_phone = carbon_get_theme_option('crb_phone'); // Получить телефон
								$crb_phone_link = carbon_get_theme_option('crb_phone_link'); // Получить телефон для ссылки
								$crb_email = carbon_get_theme_option('crb_email'); // Получить email
								$whatsaap_phone = str_replace("+", "", $crb_phone_link);

								if (!empty($crb_phone)) {
									echo	'<a href="tel:' . $crb_phone_link . '">' . $crb_phone . '</a>';
								}

								?>
								<div class="wrapper-social">
									<a href="https://wa.me/<?php echo $whatsaap_phone ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/assets/images/footer/whatsapp.svg" alt=""></a>
									<a href="https://t.me/<?php echo $crb_phone_link ?>" target="_blank"><img src="<?php bloginfo('template_url'); ?>/assets/images/footer/telegram.svg" alt=""></a>
								</div>
							</div>
							<a href="mailto:<?php echo $crb_email ?>"><?php echo $crb_email ?></a>
						</div>
					</div>
					<div class="header__contacts-burger">
						<?php
						$current_url = home_url($_SERVER['REQUEST_URI']);
						$domain = parse_url($current_url, PHP_URL_HOST);
						$port = $_SERVER['SERVER_PORT'] !== '80' ? ':' . $_SERVER['SERVER_PORT'] : '';

						$favorites_url = 'http://' . $domain . $port . '/favorites/';
						?>
						<a class="favorite" href="<?php echo esc_url($favorites_url); ?>">
							<img src="<?php bloginfo('template_url'); ?>/assets/images/fav.svg" alt="" width="16" height="16">
						</a>
						<a class="phone" href="tel:<?php echo	$crb_phone_link; ?>">
							<img src="<?php bloginfo('template_url'); ?>/assets/images/phone_burger.svg" alt="" width="16" height="16">
						</a>
					</div>
				</div>
			</div>
			<div class="header__border"></div>
			<div class="header__container">
				<nav class="header__menu menu" data-menu-links>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'header-menu',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'menu menu__list',
						)
					);
					?>
				</nav>
			</div>
			<div class="header__border header-border-bottom"></div>
			<div class="header__catalog-menu" data-catalog-menu>
				<div class="catalog-menu__container">
					<ul class="catalog-menu">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'catalog-menu',
								'menu_id'        => 'catalog-menu',
								'menu_class'     => 'catalog-menu',
							)
						);
						?>
					</ul>
					<figure class="catalog-label">
						<?php
						$crb_aside_title = carbon_get_theme_option('crb_aside_title');
						$crb_aside_image = carbon_get_theme_option('crb_aside_image');

						if (!empty($crb_aside_image)) {
							$crb_aside_image_url = wp_get_attachment_image_src($crb_aside_image, 'full');

							if ($crb_aside_image_url) {
								echo '  <img src="' . $crb_aside_image_url[0] . '" alt=""  width="288" height="172">
								        <figcaption>' . $crb_aside_title . '</figcaption>';
							}
						}
						?>

					</figure>
				</div>
				<div class="header__border header-border-bottom"></div>
			</div>
		</header>