<section class="promo" data-promo>
  <div class="promo__container">
    <?php $crb_promo_title = carbon_get_post_meta(5, 'crb_promo_title') ?>
    <h2 class="promo__title title--xl">
        <?php if (is_front_page()) {
          echo $crb_promo_title;
        } else {
           echo 'Похожие объявления'; }
        ?>
       </h2>
    <div class="promo__top top" data-filter-promo>
      <div class="top__radio">
        <?php if (is_front_page()) {
          get_template_part('template-page/components/radio-realty');
        } ?>
        <?php if (!is_front_page()) {
          get_template_part('template-page/components/radio-realty-single-page');
        } ?>
      </div>
      <div class="top__radio-mobile slider-promo-radio swiper">
        <?php if (is_front_page()) {
            get_template_part('template-page/components/radio-realty-mobile');
        } ?> 
      </div>
      <a href="/kvartiry/" class="">Весь каталог</a>
    </div>
    <div class="promo__slider">
      <div class="promo-slider__button-prev"></div>
      <div class="promo-slider swiper">
        <ul class="promo-slider__wrapper  swiper-wrapper" data-promo-cards id="put-posts-filter-single-page">
            <?php 
                if (is_front_page()) {
                    get_template_part('template-page/components/promo-gallery');
                } 
                if (!is_front_page()) {
                    get_template_part('template-page/components/promo-gallery-single-page');
                }
            ?> 
        </ul>
        <div class="promo-slider__button-all promo-slider-button-all">
          <a href="/kvartiry/" class="button "><span>Весь каталог</span></a>
        </div>
      </div>
      <div class="promo-slider__button-next"></div>
    </div>
  </div>
</section>
