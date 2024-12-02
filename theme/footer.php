<?php
require_once get_template_directory() . '/inc/enums/template_name.php';

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package realty
 */

$template =  get_page_template_slug();
?>

<?php if ($template !== TEMPLATE_NAME::MAP) { ?>
	<footer class="footer" footer-single-page>
		<div class="footer__container">
			<div class="footer-wrapper">
				<div class="footer__info footer-info">
					<?php $crb_logo_header_id = carbon_get_theme_option('crb_logo_header'); // Получить ID изображения
					$crb_logo_header_mobile_id = carbon_get_theme_option('crb_logo_footer'); // Получить ID изображения

					if (!empty($crb_logo_header_id)) {
						$crb_logo_header_url = wp_get_attachment_image_src($crb_logo_header_id, 'full');
						if ($crb_logo_header_url) {
							echo '<img class="footer-info__logo-mobile"  src="' . $crb_logo_header_url[0] . '" alt="Объектив" width="246" height="52">';
						}
					}

					if (!empty($crb_logo_header_mobile_id)) {
						$crb_logo_header_mobile_url = wp_get_attachment_image_src($crb_logo_header_mobile_id, 'full');
						if ($crb_logo_header_mobile_url) {
							echo '<img class="footer-info__logo" src="' . $crb_logo_header_mobile_url[0] . '" alt="Объектив" width="246" height="52">';
						}
					}
					?>
					<?php
					$crb_organization = carbon_get_theme_option('crb_organization');
					$crb_inn = carbon_get_theme_option('crb_inn');
					$crb_orgnip = carbon_get_theme_option('crb_orgnip');
					$crb_city = carbon_get_theme_option('crb_city');
					$crb_street = carbon_get_theme_option('crb_street');
					$crb_house = carbon_get_theme_option('crb_house');
					$crb_ofice = carbon_get_theme_option('crb_ofice');
					$crb_copy = carbon_get_theme_option('crb_copy');

					$crb_tag_footer_id_1 = carbon_get_theme_option('crb_tag_footer_one'); // Получить ID изображения
					$crb_tag_footer_id_2 = carbon_get_theme_option('crb_tag_footer_two'); // Получить ID изображения
					$crb_button_up = carbon_get_theme_option('crb_button_up'); // Получить ID изображения
					$crb_tag_footer_one_link = carbon_get_theme_option('crb_tag_footer_one_link');
					$crb_tag_footer_two_link = carbon_get_theme_option('crb_tag_footer_two_link');
					$link_target_footer_one = !empty($crb_tag_footer_one_link) ? 'target="_blank"' : '';
					$link_target_footer_two = !empty($crb_tag_footer_two_link) ? 'target="_blank"' : '';

					?>

					<p class="footer-info__location">г. <?php echo $crb_city ?>,<br /> <?php echo $crb_street ?>, <?php echo $crb_house ?>, офис <?php echo $crb_ofice ?></p>
					<div class="footer-info__copy"><?php echo $crb_copy ?> <?php echo $crb_organization ?> <br /> ИНН: <?php echo $crb_inn ?>, ОГРНИП: <?php echo $crb_orgnip ?></div>
				</div>
				<div class="footer__menu footer-menu">
					<div class="footer-menu-wrapper">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'header-menu',
								'menu_id'        => 'primary-menu',

							)
						);
						?>
					</div>
					<ul class="footer-menu__tags footer-tags">
						<li><?php
							if (!empty($crb_tag_footer_id_1)) {
								$crb_tag_footer_1_url = wp_get_attachment_image_src($crb_tag_footer_id_1, 'full');
								if ($crb_tag_footer_1_url) {
									echo '<a ' . $link_target_footer_one . ' href="' . $crb_tag_footer_one_link . '"><img src="' . $crb_tag_footer_1_url[0] . '" alt=""></a>';
								}
							}
							?>
						</li>
						<li>
							<?php if (!empty($crb_tag_footer_id_2)) {
								$crb_tag_footer_2_url = wp_get_attachment_image_src($crb_tag_footer_id_2, 'full');
								if ($crb_tag_footer_2_url) {
									echo '<a ' . $link_target_footer_two . ' href="' . $crb_tag_footer_two_link . '"><img src="' . $crb_tag_footer_2_url[0] . '" alt=""></a>';
								}
							} ?>
						</li>
						<li>
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
						</li>
					</ul>
				</div>
				<div class="footer__conatacts footer-contacts">
					<div class="footer-contacts__phone">
						<?php get_template_part('template-page/components/contact-phone-footer') ?>
					</div>
					<div class="footer-contacts__button">
						<button type="button" class="button button--phone-order" data-type="popup-form-callback">
							<span data-type="popup-form-callback">Заказать звонок</span>
						</button>
					</div>
				</div>
				<p class="footer-info__location-mobile">г. <?php echo $crb_city ?>,<br /> <?php echo $crb_street ?>, <?php echo $crb_house ?>, офис <?php echo $crb_ofice ?></p>
				<div class="footer-info__copy-mobile"><?php echo $crb_copy ?> <?php echo $crb_organization ?> <br /> ИНН: <?php echo $crb_inn ?>, ОГРНИП: <?php echo $crb_orgnip ?></div>
			</div>

		</div>
	</footer>
	<div data-loader class="loader none">
		<div class="loader-image-wrapper">
			<img src=" <?php bloginfo('template_url'); ?>/assets/images/loading.gif" />
			<span>Пожалуйста подождите..</span>
		</div>
	</div>
<?php  } ?>
</div>
<div class="gloabl-popup" style="font-size: 0;">
	<?php if ($template !== TEMPLATE_NAME::MAP) { ?>
		<div class="personal-popup">
			<?php get_template_part('template-page/popup/popup-personal-info') ?>
		</div>
		<div class="popup-form-ipoteca">
			<?php get_template_part('template-page/popup/popup-form') ?>
		</div>
		<div class="popup-success">
			<?php get_template_part('template-page/popup/popup-success') ?>
		</div>
	<?php } ?>
	<div class="popup-filter">
		<?php get_template_part('template-page/popup/popup-filter') ?>
	</div>
	<?php if ($template !== TEMPLATE_NAME::MAP) { ?>
		<div class="popup-form-callback">
			<?php get_template_part('template-page/popup/popup-callback') ?>
		</div>
		<div class="popup-gallery-documents">
			<?php get_template_part('template-page/popup/popup-gallery-documents') ?>
		</div>
		<div class="popup-employees-documents">
			<?php get_template_part('template-page/popup/popup-employee-documents') ?>
		</div>
		<button id='success' type="button" aria-hidden="true"></button>
	<?php } ?>
</div>

<script>
	function showFullNumber(event) {

		event.preventDefault();
		event.stopPropagation();


		const phoneLink = event.currentTarget;
		const phoneSpan = phoneLink.querySelector('span');
		const numberText = phoneSpan.textContent;
		const phoneNumber = phoneLink.href;
		const formattedNumber = phoneNumber.replace(/^tel:\+(\d)(\d{3})(\d{3})(\d{2})(\d{2})$/, '+$1 $2 $3-$4-$5');

		if (numberText === formattedNumber) {
			window.location.href = phoneLink.href
		} else {
			phoneSpan.textContent = formattedNumber;
		}

	}
</script>
<?php
if (!empty($crb_button_up)) {
	$crb_button_up_url = wp_get_attachment_image_src($crb_button_up, 'full');
	if ($crb_button_up_url) {
		echo '
                <div class="button-Up">
                    <button class="scrollToTopButton hide-button-up" data-button-up><img src="' . $crb_button_up_url[0] . '" alt="" width="40" height="40" /></button>
                </div>';
	}
}
?>


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
	(function(m, e, t, r, i, k, a) {
		m[i] = m[i] || function() {
			(m[i].a = m[i].a || []).push(arguments)
		};
		m[i].l = 1 * new Date();
		for (var j = 0; j < document.scripts.length; j++) {
			if (document.scripts[j].src === r) {
				return;
			}
		}
		k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
	})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(96676382, "init", {
		clickmap: true,
		trackLinks: true,
		accurateTrackBounce: true,
		webvisor: true
	});
</script>
<!-- <noscript>
	<div><img src="https://mc.yandex.ru/watch/96676382" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript> -->
<!-- /Yandex.Metrika counter -->

<?php wp_footer(); ?>

</body>

</html>