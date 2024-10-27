<?php
$product_plan = carbon_get_post_meta(get_the_ID(), 'crb_gk_plan');

?>

<div class="popup" data-popup="popup-plan" data-close-overlay>
    <div class="popup__wrapper" data-close-overlay>
        <div class="popup__content">
            <div class="popup__container">
                <div class="popup__not-header"></div>
                <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
                <div class="popup__body">
                    <div class="slider-preview-plan-wrapper">
                        <div class="slider-preview-plan__button-prev"></div>
                        <div class="slider-preview-plan swiper">
                            <ul class="slider-preview-plan__wrapper swiper-wrapper">
                                <?php

                                foreach ($product_plan as $plan_id) {
                                    $image_url = wp_get_attachment_image_src($plan_id, 'full');
                                ?>
                                    <li class="slider-preview-plan__slide swiper-slide">
                                        <div class="slide-wrapper swiper-zoom-container">
                                            <img src="<?php echo $image_url[0] ?>" alt="" />
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="slider-preview-plan__button-next"></div>
                    </div>
                    <div class="slider-gallery-container">
                        <div class="slider-popup-gallery-plan swiper">
                            <ul class="slider-popup-gallery-plan__wrapper swiper-wrapper">
                                <?php foreach ($product_plan as $plan_id) {
                                    $image_url = wp_get_attachment_image_src($plan_id, 'full');

                                ?>
                                    <li class="slider-popup-gallery-plan__slide swiper-slide">
                                        <img src="<?php echo  $image_url[0] ?>" alt="" width="82" height="64" />
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>