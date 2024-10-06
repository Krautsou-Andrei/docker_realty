<?php
if (!defined("ABSPATH")) {
  exit;
}

// Функция для загрузки постов
function load_posts_new_buildings_handler()
{
  $countSale = 6;
  $paged = $_POST['paged'];
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 4,
    'paged' => $paged
  );

  $query = new WP_Query($args);

  function pluralForm($number, $forms)
  {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
  }

  ob_start();

  if ($query->have_posts()) {
    $postCount = 0;
    while ($query->have_posts()) {
      $query->the_post();

      $product_id = carbon_get_post_meta(get_the_ID(), 'product-id');
      $product_title = carbon_get_post_meta(get_the_ID(), 'product-title');
      $product_subtitle = carbon_get_post_meta(get_the_ID(), 'product-subtitle');
      $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
      $product_label = carbon_get_post_meta(get_the_ID(), 'product-label');
      $product_price = carbon_get_post_meta(get_the_ID(), 'product-price');
      $product_building_type = carbon_get_post_meta(get_the_ID(), 'product-building-type');
      $product_price_meter = carbon_get_post_meta(get_the_ID(), 'product-price-meter');
      $product_description = carbon_get_post_meta(get_the_ID(), 'product-description');
      $product_agent_photo = carbon_get_post_meta(get_the_ID(), 'product-agent-photo');
      $product_agent_phone  = carbon_get_post_meta(get_the_ID(), 'product-agent-phone');
      $product_update_date = carbon_get_post_meta(get_the_ID(), 'product-update-date');
      $product_region = carbon_get_post_meta(get_the_ID(), 'product-region');
      $product_city = carbon_get_post_meta(get_the_ID(), 'product-city');
      $product_sub_locality = carbon_get_post_meta(get_the_ID(), 'product-sub-locality');
      $product_street = carbon_get_post_meta(get_the_ID(), 'product-street');

      $agent_phone = preg_replace('/[^0-9]/', '', $product_agent_phone);
      $format_phone_agent = '+' . substr($agent_phone, 0, 1) . ' ' . substr($agent_phone, 1, 3) . ' ' . substr($agent_phone, 4, 3) . ' - ' . substr($agent_phone, 7, 2) . ' - ...';

      $product_price_format = number_format(round(floatval($product_price)), 0, '.', ' ');
      if ($product_price_meter) {
        $product_price_meter_format = number_format(round(floatval($product_price_meter)), 0, '.', ' ');
      }


      $targetDate = new DateTime($product_update_date);
      $currentDate = new DateTime();

      $interval = $currentDate->diff($targetDate);
      $daysPassed = $interval->days;

      $wordForms = array('день', 'дня', 'дней');
      $result = pluralForm($daysPassed, $wordForms);

      $post_permalink = get_permalink(get_the_ID());

      $is_favorite = in_array($product_id, $postIds);

      echo '
                          <div class="favorites__card">
                          <article class="favorite-card">
                              <div class="favorite-card__wrapper card-preview">
                                <div class="card-preview__gallery preview-gallery" onclick="redirectToURL(\'' . esc_url($post_permalink) . '\')">';
      if (!empty($product_gallery[0])) {
        $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
        echo '<div class="preview-gallery__image">
                                              <div class="preview-gallery__id"><span>ID </span>' . $product_id . '</div>
                                              <img loading="lazy" class="preview" src="' . $image_url[0] . '" alt="" class="">
                                              <div class="preview-gallery-mobile swiper">
                                                <div class="preview-gallery-mobile__wrapper swiper-wrapper">';

        foreach ($product_gallery as $image_id) {
          $image_url = wp_get_attachment_image_src($image_id, 'full');

          echo '<div class="preview-gallery-mobile__slide swiper-slide">
                                                <img class="swiper-lazy" data-src="' . $image_url[0] . '" src="' . get_template_directory_uri() . '/assets/images/1px.png" alt="" class="" width="260" height="260">
                                                <div class="swiper-lazy-preloader"></div>
                                              </div>';
        }

        echo '  </div>
                            <div class="preview-gallery-mobile__pagination">
                              <div class="swiper-pagination">
                              </div>
                            </div>
                          </div>
                        </div>';
      }

      echo '<div class="preview-gallery__gallery">';

      if (!empty($product_gallery[1])) {
        $image_url = wp_get_attachment_image_src($product_gallery[1], 'full');
        $post_permalink = get_permalink(get_the_ID());
        echo '<a href="' . esc_url($post_permalink) . '">
                              <img loading="lazy"  src="' . $image_url[0] . '" alt="" width="122" height="79">
                            </a>';
      }
      if (!empty($product_gallery[2])) {
        $image_url = wp_get_attachment_image_src($product_gallery[2], 'full');
        $post_permalink = get_permalink(get_the_ID());
        echo '<a href="' . esc_url($post_permalink) . '">
                              <img loading="lazy" src="' . $image_url[0] . '" alt="" width="122" height="79">
                            </a>';
      }
      if (!empty($product_gallery[3])) {
        $image_url = wp_get_attachment_image_src($product_gallery[3], 'full');
        $post_permalink = get_permalink(get_the_ID());
        echo '<a href="' . esc_url($post_permalink) . '">
                              <img loading="lazy" src="' . $image_url[0] . '" alt="" width="122" height="79">
                            </a>';
      }
      if (!empty($product_gallery[4])) {
        $image_url = wp_get_attachment_image_src($product_gallery[4], 'full');
        $post_permalink = get_permalink(get_the_ID());
        echo '<a href="' . esc_url($post_permalink) . '">
                              <img loading="lazy" src="' . $image_url[0] . '" alt="" width="122" height="79">
                            </a>';
      }
      if (!empty($product_gallery[5])) {
        $image_url = wp_get_attachment_image_src($product_gallery[5], 'full');
        $post_permalink = get_permalink(get_the_ID());
        echo '<a href="' . esc_url($post_permalink) . '">
                              <img loading="lazy" src="' . $image_url[0] . '" alt="" width="122" height="79">
                            </a>';
      }

      echo '
                          </div>
                        </div>
                        <div class="card-preview__info card-info" onclick="redirectToURL(\'' . esc_url($post_permalink) . '\')">
                          <div class="card-info-title-wrapper">
                            <h2 class="card-info__title title--lg title--favorite-card-preview">' . $product_title . '</h2>
                            <p class="card-info__subtitle">' . $product_subtitle . ' ' . $product_building_type . '</p>';

      if (!empty($product_label)) {
        echo '<p class="card-info__label">' . $product_label . '</p>';
      }

      echo '
                            <p class="card-info__location">' . $product_region . (!empty($product_city) ? ', ' . $product_city : '') . (!empty($product_sub_locality) ? ', ' . $product_sub_locality : '') . (!empty($product_street) ? ', ' . $product_street : '') . '</p>
                            <p class="card-info__price title--lg"><span>от ' . $product_price_format . '</span><span> ₽</span></p>
                            <p class="card-info__price-one-metr">';

      if (!empty($product_price_meter_format)) {
        echo '<span> от' . $product_price_meter_format . '</span> ₽/м²';
      }
      echo '
                            </p>
                          </div>
                          <p class="card-info__description">
                            ' . $product_description . '
                          </p>
                        </div>
                        <div class="card-preview__border"></div>
                        <div class="card-preview__order favorite-order-agent">
                            <div class="order-agent__image">';

      if (!empty($product_agent_photo)) {
        $image_url = wp_get_attachment_image_src($product_agent_photo, 'full');
        echo '<img loading="lazy" src="' . $product_agent_photo . '" alt="" class="order-afent" width="78" height="78">';
      } else {
        echo '<img loading="lazy" src="' .  get_template_directory_uri() . '/assets/images/not_agent_card_preview.svg" alt="" class="order-afent" width="78" height="78">';
      }

      echo '
                            </div>
                          <p class="favorite-order-agent__number"> ID <span>' . $product_id . '</span></p>
                          <div class="favorite-order-agent__button">
                            <a class="button  button--phone-order" href="tel:' . $product_agent_phone . '"><span>' . $format_phone_agent . '</span></a>
                          </div>
                          <div class="favorite-order-agent__callback">
                            <button class="button button--callback" type="button" data-type="popup-form-callback"><span data-type="popup-form-callback">Перезвоните мне</span></button>
                          </div>
                          <div class="favorite-order-agent__favorites">
                                <button class="button button--favorites" type="button" data-favorite-cookies="' . $product_id . '" data-button-favorite data-delete-favorite="' . $is_favorite . '">
                                     <span>';

      if ($is_favorite) {
        echo "удалить";
      } else {
        echo "В избранное";
      }
      echo '  </span>
                                </button>
                          </div>
                          <p class="favorite-order-agent__date">' .  $result . ' дня назад</p>
                        </div>
                      </div>
                    </article>
                  </div>';
      $postCount++;
      if ($postCount % $countSale == 0) {
        get_template_part('template-page/components/info-sale');
      }
    }
  }

  wp_reset_postdata();

  $response = ob_get_clean();

  wp_send_json_success($response);
}

add_action('wp_ajax_load_posts_new_buildings', 'load_posts_new_buildings_handler');
add_action('wp_ajax_nopriv_load_posts_new_buildings', 'load_posts_new_buildings_handler');
