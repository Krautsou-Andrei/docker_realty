<section class="promo">
  <div class="promo__container">
    <?php $crb_promo_title = carbon_get_post_meta(5, 'crb_promo_title') ?>
    <h2 class="promo__title title--xl"><?php echo $crb_promo_title ?></h2>
    <div class="promo__top top">
      <div class="top__radio">
        <?php if (is_front_page()) {
          get_template_part('template-page/components/radio-realty');
        } ?>
        <?php if (!is_front_page()) {
          get_template_part('template-page/components/radio-realty-single-page');
        } ?>
      </div>
      <div class="top__radio-mobile slider-promo-radio swiper">
        <ul class="swiper-wrapper">
          <li class="swiper-slide">Квартиры</li>
          <li class="swiper-slide">Дома</li>
          <li class="swiper-slide">Новостройки</li>
          <li class="swiper-slide">Участки</li>
          <li class="swiper-slide">Коммерция</li>
        </ul>
      </div>
      <a href="/kvartiry/" class="">Весь каталог</a>
    </div>
    <div class="promo__slider">
      <div class="promo-slider__button-prev"></div>
      <div class="promo-slider swiper">
        <ul class="promo-slider__wrapper  swiper-wrapper">
          <?php

          if (is_front_page()) {
            $args = array(
              'post_type' => 'post', // Тип поста (может быть 'post', 'page', 'custom-post-type' и т.д.)
              'posts_per_page' => -1, // Количество постов на странице (-1 для вывода всех постов)
              'posts_per_page' => 12, //Количество выводимых постов
            );
          } else {
            $args = array(
              'post_type' => 'post',
              'posts_per_page' => -1,
              'posts_per_page' => 6,
            );
          }

          $query = new WP_Query($args);

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();

              $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
              $product_region = carbon_get_post_meta(get_the_ID(), 'product-region');
              $product_city = carbon_get_post_meta(get_the_ID(), 'product-city');
              $product_address = carbon_get_post_meta(get_the_ID(), 'product-address');
              $product_description = carbon_get_post_meta(get_the_ID(), 'product-description');
              $product_price =  carbon_get_post_meta(get_the_ID(), 'product-price');
              $product_area = carbon_get_post_meta(get_the_ID(), 'product-area');

              if ($product_area > 0) {
                $product_price_meter = number_format(round(floatval($product_price) / floatval($product_area), 2), 0, '.', ' ');
              }
              $product_price_format = number_format(round(floatval(carbon_get_post_meta(get_the_ID(), 'product-price'))), 0, '.', ' ');


              echo '<li class="promo-slider__slide swiper-slide slide" data-new-buildings>';
              if (!empty($product_gallery[0])) {
                $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
                $post_permalink = get_permalink(get_the_ID());
                echo '<a href="' . esc_url($post_permalink) . '">
                            <div class="slide-image">
                              <img src="' . $image_url[0] . '" alt="" width="380" height="195">
                            </div>';
              }
              echo '
                            <div class="slide__info info">
                              <h3 class="info__title title--lg title--promo-slide">' . $product_city . '</h3>
                              <p class="info__description">' . $product_description . '</p>';

              if (!empty($product_price_meter)) {
                echo '<p class="info__price">от ' . $product_price_meter . ' ₽ за м²</p>';
              } else {
                echo '<p class="info__price">' . $product_price_format . ' ₽</p>';
              }
              echo  '<p class="info__location"><span>' . $product_region . '</span><span>, ' . $product_city . ' ' . $product_address . '</span></p>
                           </div>
                          </a>
                          </li>';
            }
          } ?>
        </ul>
        <div class="promo-slider__button-all promo-slider-button-all">
          <a href="/kvartiry/" class="button "><span>Весь каталог</span></a>
        </div>
      </div>
      <div class="promo-slider__button-next"></div>
    </div>
  </div>
</section>
