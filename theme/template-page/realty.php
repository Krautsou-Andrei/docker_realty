<?php
/*
Template Name: Realty
*/
get_header();
?>

<main class="page">
  <div class="main-catalog">

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
          <!-- Подумать, как показывать по условию 
          <h2 class="catalog__title title--xl title--catalog">
            <? /*the_title();*/ ?>
          </h2>-->
          <div id='content-container' class="catalog-wrapper mb1">

            <? get_template_part('template-parts/content', 'page'); ?>

          </div>
        </div>
        <? $parts = parse_url($_SERVER["REQUEST_URI"]);
        parse_str(!empty($parts['query']), $query); ?>
        <div class="single-page__promo">
          <? if (!empty($query['obj']) && $query['obj'] > 0) get_template_part('template-page/components/promo'); ?>
        </div>
        <?
        if (!empty($query['obj']) && $query['obj'] > 0) echo '<div class="single-page__questions">' . get_template_part('template-page/components/questions') . '</div>';
        else echo '<div class="catalog__questions">' . get_template_part('template-page/components/questions') . '</div>';
        ?>
      </section>
    </div>
  </div>
</main>


<?php
get_footer();
?>