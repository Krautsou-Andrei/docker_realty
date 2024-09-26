<?php
/*
Template Name: Страница жилого комплекса
*/

require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/get_slug_page.php';

$id_page = get_the_ID();

$slug_page = get_slug_page();

$params_page_gk = [
  'id_page_gk' => $id_page,
  'slug_page' => $slug_page
];

get_header();
wp_enqueue_script('get_card_gk_single-js', get_template_directory_uri() . '/inc/ajax/get_card_gk_single.js', array('jquery'), null, true);
wp_localize_script('get_card_gk_single-js', 'params', $params_page_gk);
?>
<main class="page">
  <div class="main-favorites">
    <?php $crb_new_building_title = carbon_get_post_meta(get_the_ID(), 'crb_new_building_title');


    $filter_city = explode('/', $_SERVER['REQUEST_URI'])[2];
    $countSale = 6;

    $title_city = $filter_city === 'novorossiysk' ? 'в Новороссийске' : ($filter_city === 'krasnodar' ? 'в Краснодаре' : '');
    $search_param_city = $filter_city === 'novorossiysk' ? 'Новороссийск' : ($filter_city === 'krasnodar' ? 'Краснодар' : '');


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
              <!-- <p class="favorites__subtitle">Найдено <?php echo num_word($total_posts, DEFAULT_ENUM::RESIDENTAL_COMPLEX) ?></p> -->
            </div>
          </div>
          <div data-loader class="loader">
            <img src=" <?php bloginfo('template_url'); ?>/assets/images/loading.gif" />
          </div>
          <div id='content-container-page-gk' class="favorites-wrapper">



          </div>
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
        <div class="catalog__questions">
          <?php get_template_part('template-page/components/questions'); ?>
        </div>
      </section>
    </div>
  </div>
</main>
<div class="popup-gallery">
  <?php get_template_part('template-page/popup/popup-gallery-gk') ?>
</div>
<div class="popup-plan">
  <?php get_template_part('template-page/popup/popup-plan') ?>
</div>
<?php
get_footer();
?>