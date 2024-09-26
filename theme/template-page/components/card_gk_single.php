<?php

$crb_gk_name = $args['crb_gk_name'];
$crb_gk_plan = $args['crb_gk_plan'];
$crb_gk_gallery = $args['crb_gk_gallery'];
$crb_gk_description =  $args['crb_gk_description'];
$crb_gk_city =  $args['crb_gk_city'];
$crb_gk_address = $args['crb_gk_address'];
$crb_gk_latitude =  $args['crb_gk_latitude'];
$crb_gk_longitude = $args['crb_gk_longitude'];
$update_page = $args['update_page'];
$min_price = $args['min_price'];
$min_price_meter = $args['min_price_meter'];
$finishing = $args['finishing'];
$literal = $args['literal'];
$categories_area = $args['categories_area'];
$categories_rooms = $args['categories_rooms'];
$map_apartaments = $args['map_apartaments'];

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

$all_finishing = implode(', ', $finishing);

$params_table = [
    'literal' => $literal,
    'categories_area' => $categories_area,
    'map_apartaments' => $map_apartaments,
    'crb_gk_plan' => $crb_gk_plan,
];

?>
<script>

</script>
<section class="single-gk-card">
    <div class="single-gk-card__container">
        <div class="single-gk-card-wrapper">
            <div class="single-gk-card__info">
                <div class="single-gk-card__title">
                    <button class="button-back" type="button" aria-label="Назад"></button>
                    <h1 class="title--xl title--catalog title--singe-page"><?php echo $crb_gk_name ?></h1>
                </div>
                <p class="single-gk-card__subtitle">
                    <?php echo $crb_gk_city ?>, <?php echo $crb_gk_address ?>
                    <a href="#single-map">Показать на карте</a>
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
                                                <img class="swiper-lazy" data-src="<?php echo $image_url[0] ?>" src="<?php bloginfo('template_url'); ?>'/assets/images/1px.png" alt="" data-type="popup-gallery" />
                                                <div class="swiper-lazy-preloader"></div>
                                            </div>

                                    <? }
                                    } ?>
                                </div>
                            </div>
                            <div class="custom-scrollbar"></div>
                            <div class="product__gallery">
                                <div data-type="popup-gallery">
                                    <img src="<?php echo $image_preview_url_two[0] ?>" alt="" width="226" height="166" />
                                </div>
                                <div data-type="popup-gallery">
                                    <img src="<?php echo $image_preview_url_three[0] ?>" alt="" width="226" height="166" />
                                    <span data-type="popup-gallery"><?php echo count($crb_gk_gallery) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="product-single-slide-gallery swiper">
                            <div class="product-single-slide-gallery__wrapper swiper-wrapper">
                                <?php if (!empty($crb_gk_gallery)) {
                                    foreach ($crb_gk_gallery as $image) {
                                        $image_url = wp_get_attachment_image_src($image, 'full');

                                ?>
                                        <div class="product-single-slide-gallery__slide swiper-slide">
                                            <img src="<?php echo $image_url[0] ?>" alt="" />
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
                    <div class="single-gk-card__order">
                        <article class="agent-order" data-agent-order>
                            <p class="agent-order__date">Информация обновлена <?php echo $update_page ?></p>
                            <?php if (!empty($min_price)) { ?>
                                <h2 class="agent-order__price title--xl title--product-agent">от <?php echo number_format(round(floatval($min_price)), 0, '.', ' ') ?> ₽</h2>
                                <div class="agent-order__label">Хорошая цена!</div>
                            <?php } ?>
                            <div class="agent-order__info">
                                <?php if (!empty($all_finishing)) { ?>
                                    <div class="agent-order__price-one-metr agent-price-one-mert">
                                        <span class="agent-conditions__title">Отделка</span>
                                        <span class="agent-conditions__space"></span>
                                        <span class="agent-conditions__price"><?php echo $all_finishing ?></span>
                                    </div>
                                <?php  } ?>
                                <?php if (!empty($min_price_meter)) { ?>
                                    <div class="agent-order__price-one-metr agent-price-one-mert">
                                        <span class="agent-conditions__title">Цена за метр</span>
                                        <span class="agent-conditions__space"></span>
                                        <span class="agent-conditions__price">от <?php echo number_format(round(floatval($min_price_meter)), 0, '.', ' ') ?> ₽/м² </span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="button-wrapper">
                                <div class="agent-order__button">
                                    <a class="button button--phone-order" href="tel:+79104898888"><span> +7 910 489-88-...</span></a>
                                    <button class="button--favorites-mobile" type="button" data-favorite-cookies="'64" data-button-favorite-mobile data-delete-favorite="1"><span></span></button>
                                </div>
                                <div class="agent-order__callback">
                                    <button class="button button--callback" type="button" data-type="popup-form-callback"><span data-type="popup-form-callback">Перезвоните мне</span></button>
                                </div>
                            </div>
                        </article>

                    </div>
                </div>
                <div class="product__gk-filter">
                    <section class="page-gk-filter">
                        <form class="page-gk-filter__form" action="">
                            <?php if (!empty($categories_rooms)) { ?>
                                <div class="gk-filter-rooms">
                                    <div class="gk-filter-rooms__title">Количество комнат</div>
                                    <?php foreach ($categories_rooms as $room) { ?>
                                        <label>
                                            <input type="checkbox" />
                                            <span><?php echo $room->name ?></span>
                                        </label>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <?php if (!empty($categories_area)) { ?>
                                <div class="gk-filter-area">
                                    <div class="gk-filter-area__title">Общая площадь</div>
                                    <div class="gk-filter-area__wrapper">
                                        <?php foreach ($categories_area as $area) { ?>
                                            <label>
                                                <input type="checkbox" />
                                                <span><?php echo intval($area->name) ?> м2</span>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php  } ?>

                        </form>
                    </section>

                </div>
                <div class="product__more">
                    <?php get_template_part('template-page/components/gk_table', null, $params_table) ?>
                </div>
            </div>
        </div>
    </div>
</section>