<?php
require_once get_template_directory() . '/inc/lib/get_image_url.php';

$id_page_gk = get_the_ID();

$crb_gk_name = carbon_get_post_meta($id_page_gk, 'crb_gk_name');
$crb_gk_plan = carbon_get_post_meta($id_page_gk, 'crb_gk_plan');
$crb_gk_gallery = carbon_get_post_meta($id_page_gk, 'crb_gk_gallery');
$crb_gk_description =  carbon_get_post_meta($id_page_gk, 'crb_gk_description');
$crb_gk_city =  carbon_get_post_meta($id_page_gk, 'crb_gk_city');
$crb_gk_address = carbon_get_post_meta($id_page_gk, 'crb_gk_address');
$crb_gk_latitude =   carbon_get_post_meta($id_page_gk, 'crb_gk_latitude');
$crb_gk_longitude = carbon_get_post_meta($id_page_gk, 'crb_gk_longitude');

$image_preview_url = '';
$image_preview_url_two = '';
$image_preview_url_three = '';

if (!empty($crb_gk_gallery[0])) {
    $image_preview_url  = wp_get_attachment_image_src($crb_gk_gallery[0], 'full');
}
if (!empty($crb_gk_gallery[1])) {
    $image_preview_url_two  = wp_get_attachment_image_src($crb_gk_gallery[1], 'full');
}
if (!empty($crb_gk_gallery[2])) {
    $image_preview_url_three  = wp_get_attachment_image_src($crb_gk_gallery[2], 'full');
}

?>

<section class="single-gk-card">
    <input hidden type="text" value="" data-input-table-params />
    <div class="single-gk-card__container" data-container>
        <div class="single-gk-card-wrapper">
            <div class="single-gk-card__info">
                <div class="single-gk-card__title">
                    <div class="single-gk-card__back-button button--back">
                        <?php $referer = wp_get_referer() ?>
                        <a class="" href="<?php echo esc_url($referer) ?>">
                            <img src="<?php bloginfo('template_url'); ?>/assets/images/back.svg" alt="" />
                        </a>
                    </div>
                    <h1 class="title--xl title--catalog title--singe-page"><?php echo $crb_gk_name ?></h1>
                </div>
                <p class="single-gk-card__subtitle">
                    <?php echo $crb_gk_city ?>, <?php echo $crb_gk_address ?>
                </p>
                <div class="product-wrapper">
                    <div class="single-gk-card__product product">
                        <div class="product__image-wrapper">
                            <div class="product__image" data-type="popup-gallery">
                                <div class="product-image-wrapper" data-type="popup-gallery">
                                    <img class="product-image-wrapper__preview" src="<?php echo $image_preview_url[0] ?>" alt="" data-type="popup-gallery" />
                                </div>
                            </div>
                            <div class="product-single-slider swiper">
                                <div class="product-single-slider__wrapper swiper-wrapper">
                                    <?php if (!empty($crb_gk_gallery)) {
                                        foreach ($crb_gk_gallery as $image) {
                                            $image_url = wp_get_attachment_image_src($image, 'full');

                                    ?>
                                            <div class="product-single-slider__slide swiper-slide" data-type="popup-gallery">
                                                <img class="swiper-lazy" data-src="<?php echo get_image_url($image_url) ?>" src="<?php bloginfo('template_url'); ?>/assets/images/1px.png" alt="" data-type="popup-gallery" />
                                                <div class="swiper-lazy-preloader"></div>
                                            </div>

                                    <? }
                                    } ?>
                                </div>
                            </div>
                            <div class="custom-scrollbar"></div>
                            <?php if (!empty($image_preview_url_two)) { ?>
                                <div class="product__gallery">
                                    <div data-type="popup-gallery">
                                        <img src="<?php echo get_image_url($image_preview_url_two)  ?>" alt="" width="226" height="166" />
                                    </div>
                                    <?php if (!empty($image_preview_url_three)) { ?>
                                        <div data-type="popup-gallery">
                                            <img src="<?php echo get_image_url($image_preview_url_three) ?>" alt="" width="226" height="166" />
                                            <span data-type="popup-gallery"><?php echo count($crb_gk_gallery) ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="product-single-slide-gallery swiper">
                            <div class="product-single-slide-gallery__wrapper swiper-wrapper">
                                <?php if (!empty($crb_gk_gallery)) {
                                    foreach ($crb_gk_gallery as $image) {
                                        $image_url = wp_get_attachment_image_src($image, 'full');

                                ?>
                                        <div class="product-single-slide-gallery__slide swiper-slide">
                                            <img src="<?php echo get_image_url($image_url) ?>" alt="" />
                                        </div>
                                <? }
                                } ?>

                            </div>
                        </div>
                        <div class="product__description">
                            <div class="second-product-description" data-more-text>
                                <?php echo $crb_gk_description ?>
                            </div>
                        </div>
                        <div class="product__button-more">
                            <button class="show-more" type="button" data-more><span>Узнать больше</span></button>
                        </div>
                    </div>
                    <?php get_template_part('template-page/components/card_agent') ?>
                </div>

                <div class="" data-container-table>

                </div>
            </div>
        </div>
    </div>
</section>