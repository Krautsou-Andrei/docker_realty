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
  <div data-loader class="loader">
    <div class="loader-image-wrapper">
      <img src=" <?php bloginfo('template_url'); ?>/assets/images/loading.gif" />
      <span>Пожалуйста подождите..</span>
    </div>
  </div>
  <div class="main-favorites">
    <?php
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
    <div class="main-favorites__cards-preview">
      <section class="favorites">
        <div class="favorites-wrapper">
          <?php get_template_part('template-page/components/card_gk_single', null, $params_page_gk); ?>
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
<div class="popup-plan">
  <?php get_template_part('template-page/popup/popup-video') ?>
</div>
<div class="popup-apartament">
  <?php get_template_part('template-page/popup/popup_apartaments') ?>
</div>
<?php
get_footer();
?>