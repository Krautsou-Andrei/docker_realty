<?php
/*
Template Name: Страница каталога
*/

get_header();
?>
<main class="page">
  <div class="main-catalog">
    <div class=" main-catalog__filter">
      <section class="filter-catalog">
        <div class="filter-catalog__container">
          <div class="filter-catalog-mobile">
            <div class="filter-catalog-mobile__button">
              <?php $referer = wp_get_referer() ?>
              <a class="button-catalog-filter" href="<?php echo esc_url($referer) ?>">
                <img src=" <?php bloginfo('template_url'); ?>/assets/images/back.svg" alt="">
                <span>Назад </span>
              </a>
            </div>
            <div class="filter-catalog-mobile__button">
              <button class="button-catalog-filter" data-type="popup-filter">
                <img src="<?php bloginfo('template_url'); ?>/assets/images/filter.svg" alt="" data-type="popup-filter">
                <span data-type="popup-filter">Фильтры </span>
              </button>
            </div>
          </div>
          <!--<?php get_template_part('template-page/components/filter-catalog') ?>-->
        </div>
      </section>
    </div>
    <!-- <div class="main-catalog__breadcrumbs"> -->
    <?php

    if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<div class="main-catalog__breadcrumbs">
                      <section class="breadcrumbs">
                        <div class="breadcrumbs__container">
                         ', '
                         </div>
                      </section>
                    </div>');
    }
    ?>
    <!-- </div> -->
    <div class="main-catalog__cards-preview">
      <section class="catalog">
        <div class="catalog__container">
          <!-- Подумать, как показывать по условию -->
          <h2 class="catalog__title title--xl title--catalog">
            Квартиры на вторичке в Новороссийске
          </h2>
          <p class="catalog__subtitle">Найдено <span><?php echo wp_count_posts()->publish ?></span> объявлений</p>
          <div id='content-container' class="catalog-wrapper">

            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;

            $args = array(
              'post_type' => 'post', // Тип поста (может быть 'post', 'page', 'custom-post-type' и т.д.)
              'posts_per_page' => 4, // Количество постов на странице (-1 для вывода всех постов)
              'paged' => $paged
            );

            function pluralForm($number, $forms)
            {
              $cases = array(2, 0, 1, 1, 1, 2);
              return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
              $postCount = 0;
              while ($query->have_posts()) {
                $query->the_post();

                $product_id = carbon_get_post_meta(get_the_ID(), 'product-id');
                $product_category = carbon_get_post_meta(get_the_ID(), 'product-category');
                $product_title = carbon_get_post_meta(get_the_ID(), 'product-title');
                $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
                $product_rooms = carbon_get_post_meta(get_the_ID(), 'product-rooms');
                $product_area = carbon_get_post_meta(get_the_ID(), 'product-area');
                $product_floor = carbon_get_post_meta(get_the_ID(), 'product-floor');
                $product_floor_total = carbon_get_post_meta(get_the_ID(), 'product-floor-total');
                $product_label = carbon_get_post_meta(get_the_ID(), 'product-label');
                $product_price = carbon_get_post_meta(get_the_ID(), 'product-price');
                $product_description = carbon_get_post_meta(get_the_ID(), 'product-description');
                $product_agent_photo = carbon_get_post_meta(get_the_ID(), 'product-agent-photo');
                $product_agent_phone  = carbon_get_post_meta(get_the_ID(), 'product-agent-phone');
                $product_update_date = carbon_get_post_meta(get_the_ID(), 'product-update-date');

                $agent_phone = preg_replace('/[^0-9]/', '', $product_agent_phone);
                $format_phone_agent = '+' . substr($agent_phone, 0, 1) . ' ' . substr($agent_phone, 1, 3) . ' ' . substr($agent_phone, 4, 3) . ' - ' . substr($agent_phone, 7, 2) . ' - ...';

                if ($product_area > 0) {
                  $product_price_meter = number_format(round(floatval($product_price) / floatval($product_area), 2), 0, '.', ' ');
                }

                $product_price_format = number_format(round(floatval(carbon_get_post_meta(get_the_ID(), 'product-price'))), 0, '.', ' ');

                $targetDate = new DateTime($product_update_date);
                $currentDate = new DateTime();

                $interval = $currentDate->diff($targetDate);
                $daysPassed = $interval->days;

                $wordForms = array('день', 'дня', 'дней');
                $result = pluralForm($daysPassed, $wordForms);

                $post_permalink = get_permalink(get_the_ID());

                echo '
                      <div class="catalog__card">
                      <article class="product-card" onclick="redirectToURL(\'' . esc_url($post_permalink) . '\')">
                          <div class="product-card__wrapper card-preview">
                            <div class="card-preview__gallery preview-gallery">';
                if (!empty($product_gallery[0])) {
                  $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
                  echo '<div class="preview-gallery__image">
                          <img class="preview" src="' . $image_url[0] . '" alt="" class="">
                          <div class="preview-gallery-mobile swiper">
                            <div class="preview-gallery-mobile__wrapper swiper-wrapper">';

                  foreach ($product_gallery as $image_id) {
                    $image_url = wp_get_attachment_image_src($image_id, 'full');

                    echo '<div class="preview-gallery-mobile__slide swiper-slide">
                            <img src="' . $image_url[0] . '" alt="" class="" width="260" height="260">
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
                          <img  src="' . $image_url[0] . '" alt="" width="122" height="79">
                        </a>';
                }
                if (!empty($product_gallery[2])) {
                  $image_url = wp_get_attachment_image_src($product_gallery[2], 'full');
                  $post_permalink = get_permalink(get_the_ID());
                  echo '<a href="' . esc_url($post_permalink) . '">
                          <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                        </a>';
                }
                if (!empty($product_gallery[3])) {
                  $image_url = wp_get_attachment_image_src($product_gallery[3], 'full');
                  $post_permalink = get_permalink(get_the_ID());
                  echo '<a href="' . esc_url($post_permalink) . '">
                          <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                        </a>';
                }
                if (!empty($product_gallery[4])) {
                  $image_url = wp_get_attachment_image_src($product_gallery[4], 'full');
                  $post_permalink = get_permalink(get_the_ID());
                  echo '<a href="' . esc_url($post_permalink) . '">
                          <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                        </a>';
                }
                if (!empty($product_gallery[5])) {
                  $image_url = wp_get_attachment_image_src($product_gallery[5], 'full');
                  $post_permalink = get_permalink(get_the_ID());
                  echo '<a href="' . esc_url($post_permalink) . '">
                          <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                        </a>';
                }

                echo '
                      </div>
                    </div>
                    <div class="card-preview__info card-info">
                      <div class="card-info-title-wrapper">
                        <h2 class="card-info__title title--lg title--product-card-preview ">' . $product_title . '</h2>
                        <p class="card-info__subtitle">' . $product_rooms . '-комн. ' . $product_category . ', ' . $product_area . ' м², ' . $product_floor . '/' . $product_floor_total . ' этаж</p>';

                if (!empty($product_label)) {
                  echo '<p class="card-info__label">' . $product_label . '</p>';
                }

                echo '
                        <p class="card-info__price title--lg"><span>' . $product_price_format . '</span><span> ₽</span></p>
                        <p class="card-info__price-one-metr">';

                if (!empty($product_price_meter)) {
                  echo '<span>' . $product_price_meter . '</span> ₽/м²';
                }
                echo '
                        </p>
                      </div>
                      <p class="card-info__description">
                        ' . $product_description . '
                      </p>
                    </div>
                    <div class="card-preview__border"></div>
                    <div class="card-preview__order order-agent">';

                if (!empty($product_agent_photo)) {
                  $image_url = wp_get_attachment_image_src($product_agent_photo, 'full');
                  echo '<img src="' . $product_agent_photo . '" alt="" class="order-afent" width="78" height="78">';
                } else {
                  echo '<img src="' .  get_template_directory_uri() . '/assets/images/not_agent_card_preview.svg" alt="" class="order-afent" width="78" height="78">';
                }

                echo '
                      <p class="order-agent__number"> ID <span>' . $product_id . '</span></p>
                      <div class="order-agent__button">
                        <a class="button  button--phone-order" href="tel:' . $product_agent_phone . '"><span>' . $format_phone_agent . '</span></a>
                      </div>
                      <div class="order-agent__callback">
                        <button class="button button--callback" type="button" data-type="popup-form-callback"><span data-type="popup-form-callback">Перезвоните мне</span></button>
                      </div>
                      <p class="order-agent__date">' .  $result . ' дня назад</p>
                    </div>
                  </div>
                </article>
              </div>';
                $postCount++;
                if ($postCount % 2 == 0) {
                  get_template_part('template-page/components/info-sale');
                }
              }
              if ($query->max_num_pages > 1) {
                echo '<div class="pagination">
                        <div class="pagination__container">
                         ';
                $prev_link = get_previous_posts_link('Назад');
                if ($prev_link) {
                  echo '<span class="prev-link">' . $prev_link . '</span>';
                } else {
                  echo '<span class="prev-link disabled">Назад</span>';
                }

                echo paginate_links(array(
                  'total' => $query->max_num_pages,
                  'current' => $paged,
                  'prev_next' => false,
                  'end_size' => 1,
                  'mid_size' => 2,
                  'type' => 'list',
                ));

                $next_link = get_next_posts_link('Дальше', $query->max_num_pages);
                if ($next_link) {
                  echo '<span class="next-link">' . $next_link . '</span>';
                } else {
                  echo '<span class="next-link disabled">Дальше</span>';
                }
                echo '
                     </div>
                    </div>';
              }
            } else {
              echo 'Посты не найдены.';
            }

            wp_reset_postdata();
            ?>
            <script>
              function redirectToURL(url) {
                window.location.href = url;
              }

              const buttonsOrder = document.querySelectorAll('.button--phone-order')

              buttonsOrder.forEach((button) => {
                button.addEventListener('click', showFullNumber)
              })

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
          </div>
        </div>
        <div class="catalog__questions">
          <?php get_template_part('template-page/components/questions'); ?>
        </div>
      </section>
    </div>
  </div>
</main>
<?php
get_footer();
?>
