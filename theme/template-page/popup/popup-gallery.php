<?php
require_once get_template_directory() . '/inc/lib/get_image_url.php';
require_once get_template_directory() . '/inc/enums/template_name.php';

$template =  get_page_template_slug();
$is_single_page = $template !== '';

?>

<div class="popup" data-popup="popup-gallery" data-close-overlay>
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
  <div class="popup__wrapper" data-close-overlay>
    <div class="popup__content">
      <div class="popup__container">
        <div class="popup-gallery__header">
          <div class=""> </div>
          <div class="popup-gallery__info">
            <?php
            $product_id = carbon_get_post_meta(get_the_ID(), 'product-id');
            $product_agent_phone = carbon_get_post_meta(get_the_ID(), 'product-agent-phone');
            $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
            $product_new_building = carbon_get_post_meta(get_the_ID(), 'product-new-building');
            $product_video = "";
            // if($product_new_building != 1){
            //  $product_video = array_pop ($product_gallery);   
            // };



            $product_price = carbon_get_post_meta(get_the_ID(), 'product-price');
            $agent_phone = preg_replace('/[^0-9]/', '', $product_agent_phone);
            $format_phone_agent = '+' . substr($agent_phone, 0, 1) . ' ' . substr($agent_phone, 1, 3) . ' ' . substr($agent_phone, 4, 3) . ' - ' . substr($agent_phone, 7, 2) . ' - ...';

            ?>
            <span class="number-object">Объект № <?php echo $product_id ?></span>
            <div class="popup-gallery__phone">
              <a onclick="showFullNumber(event)" data-phone-popup href="tel:<?php echo $product_agent_phone ?>" class="button">
                <span><?php echo $format_phone_agent ?> </span>
              </a>
            </div>
          </div>
        </div>
        <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
        <div class="popup__body">
          <div class="slider-preview-wrapper">
            <div class="slider-preview__button-prev"></div>
            <div class="slider-preview swiper">

              <ul class="slider-preview__wrapper swiper-wrapper">
                <?php
                if ($product_video != '0' && $product_video != '')
                  echo ' 
                        <li class="slider-preview__slide swiper-slide">
                        <div class="slide-wrapper" id="youtube-slide" data-video-url="' . $product_video_src . '">
                        <div id="player"></div>
                        </div>
                        </li>';

                ?>

                <?php
                foreach ($product_gallery as $image_id) {
                  $is_url = filter_var($image_id, FILTER_VALIDATE_URL);
                  $image_url;
                  if ($is_url) {
                    $image_url = $image_id;
                  } else {
                    $image_url = wp_get_attachment_image_src($image_id, 'full');
                  }


                  echo '
                        <li class="slider-preview__slide swiper-slide">
                        <div class="slide-wrapper swiper-zoom-container">
                        <img src="' . get_image_url($image_url, $is_url) . '" alt="">
                        </div>
                        </li>';
                }
                ?>
              </ul>

            </div>
            <div class="slider-preview__button-next"></div>
          </div>
          <div class="slider-gallery-container">
            <div class="slider-popup-gallery swiper">
              <ul class="slider-popup-gallery__wrapper swiper-wrapper">
                <?php
                if ($product_video != '0' && $product_video != '')
                  echo '
              <li class="slider-popup-gallery__slide swiper-slide">
                <img id="youtube-slide-preview" src="" alt="" width="82" height="64">
              </li>
			  ';
                ?>

                <?php
                foreach ($product_gallery as $image_id) {
                  $is_url = filter_var($image_id, FILTER_VALIDATE_URL);
                  $image_url;
                  if ($is_url) {
                    $image_url = $image_id;
                  } else {
                    $image_url = wp_get_attachment_image_src($image_id, 'full');
                  }

                  echo '<li class="slider-popup-gallery__slide swiper-slide">
                        <img src="' . get_image_url($image_url, $is_url) . '" alt="" width="82" height="64">
                      </li>';
                }
                ?>
              </ul>
            </div>
          </div>
          <div class="popup-gallery__info-mobile">
            <span class="info-mobile-wrapper">
              <span class="number-object">Объект № <?php echo $product_id ?></span>
              <span class="price-object"><?php echo $product_price ?> ₽</span>
            </span>
            <div class="popup-gallery__phone">
              <a onclick="showFullNumber(event)" href="tel:<?php echo $product_agent_phone ?>" class="button">
                <img src="<?php bloginfo('template_url'); ?>/assets/images/phone.svg" alt="" width="16" height="16">
                <span><?php echo $format_phone_agent ?> </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>