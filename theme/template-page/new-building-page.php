<?php
/*
Template Name: Страница новостройки
*/

require_once get_template_directory() . '/inc/enums/default_enum.php';

get_header();
?>
<main class="page">
  <div class="main-favorites">
    <?php $crb_new_building_title = carbon_get_post_meta(get_the_ID(), 'crb_new_building_title');

    $filter_city = explode('/', $_SERVER['REQUEST_URI'])[2];
    $countSale = 6;

    $title_city = $filter_city === 'novorossiysk' ? 'в Новороссийске' : ($filter_city === 'krasnodar' ? 'в Краснодаре' : '');
    $search_param_city = $filter_city === 'novorossiysk' ? 'Новороссийск' : ($filter_city === 'krasnodar' ? 'Краснодар' : '');

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = array(
      'post_type' => 'post', // Тип поста (может быть 'post', 'page', 'custom-post-type' и т.д.)
      'posts_per_page' => 4, // Количество постов на странице (-1 для вывода всех постов)
      'paged' => $paged
    );

    if (isset($search_param_city)) {
      $args['meta_query'] = array(
        array(
          'key' => 'product-city',
          'value' => $search_param_city,
          'compare' => 'LIKE'
        )
      );
    }

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;

    // $desiredValue = $parts[2];
    if (function_exists('yoast_breadcrumb')) {
      yoast_breadcrumb('<div class="main-favorites__breadcrumbs">
                          <section class="breadcrumbs">
                            <div class="breadcrumbs__container">
                             ', '
                             </div>
                          </section>
                        </div>');
    }
    ?>
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
            <div class="filter-catalog-mobile__button" data-type="popup-filter">
              <button class="button-catalog-filter" data-type="popup-filter">
                <img src="<?php bloginfo('template_url'); ?>/assets/images/filter.svg" alt="" data-type="popup-filter">
                <span data-type="popup-filter">Фильтры </span>
              </button>
            </div>
          </div>
          <?php get_template_part('template-page/components/filter-catalog') ?>
        </div>
      </section>
    </div>
    <div class="main-favorites__cards-preview">
      <section class="favorites">
        <div class="favoritesg__container">
          <div class="favorites__title-wrapper">
            <div class="favorites__back-button">
              <?php $referer = wp_get_referer() ?>
              <a class="" href="<?php echo esc_url($referer) ?>">
                <img src="<?php bloginfo('template_url'); ?>/assets/images/back.svg" alt="" />
              </a>
            </div>
            <div class="title-wrapper">
              <h1 class="favorites__title title--xl title--catalog">
                <?php echo $crb_new_building_title . ' ' . $title_city; ?>
              </h1>
              <p class="favorites__subtitle">Найдено <?php echo num_word($total_posts, DEFAULT_ENUM::RESIDENTAL_COMPLEX) ?></p>
            </div>
          </div>
          <div id='content-container-new-buildings' class="favorites-wrapper">

            <?php
            function pluralForm($number, $forms)
            {
              $cases = array(2, 0, 1, 1, 1, 2);
              return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
            }


            $postIds = [];
            if (isset($_COOKIE['favorites'])) {
              $cookieValue = $_COOKIE['favorites'];
              $unescapedValue = stripslashes($cookieValue);
              $postIds = json_decode($unescapedValue, true);

              if (json_last_error() === JSON_ERROR_NONE) {
                $postIds = array_map('intval', $postIds);
              } else {

                $postIds = [];
              }
            } else {
              $postIds = [];
            }


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
                                                 <img data-src="' . $image_url[0] . '" src="' . get_template_directory_uri() . '/assets/images/1px.png" alt="" class="swiper-lazy" width="260" height="260">
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
                              <img loading="lazy" src="' . $image_url[0] . '" alt="" width="122" height="79">
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
                  echo '<img loading="lazy" src="' . $image_url[0] . '" alt="" class="order-afent" width="78" height="78">';
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