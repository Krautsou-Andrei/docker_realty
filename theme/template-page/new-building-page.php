<?php
/*
Template Name: Страница новостройки
*/

require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/enums/template_name.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';

get_header();

?>
<main class="page">
  <div class="main-favorites">
    <?php $crb_new_building_title = carbon_get_post_meta(get_the_ID(), 'crb_new_building_title');

    $filter_city = isset($_GET['city']) ? $_GET['city'] : '2306';
    $search_param_city = $filter_city === '2306' ? 'Новороссийск' : ($filter_city === '2301' ? 'Краснодар' : 'Новороссийск');
    $title_city =  $filter_city === '2306' ? 'в Новороссийске' : ($filter_city === '2301' ? 'в Краснодаре' : '');

    $filter_price = isset($_GET['select_price']) ?  explode('-', $_GET['select_price']) : [];
    $filter_area = isset($_GET['select_area']) ? explode('-', $_GET['select_area']) : [];

    $filter_price_ot = isset($filter_price[0]) ? $filter_price[0] : '';
    $filter_price_do = isset($filter_price[1]) ? $filter_price[1] : '';

    $filter_area_ot = isset($filter_area[0]) ? $filter_area[0] : '';
    $filter_area_do = isset($filter_area[1]) ? $filter_area[1] : '';

    $filter_check_price = isset($_GET['check_price']) ? $_GET['check_price'] : '';

    $id_page = search_id_page_by_name(CATEGORIES_ID::PAGE_NEW_BUILDINGS, $search_param_city) ?? 1;

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = array(
      'post_type'      => 'page', // Тип поста
      'posts_per_page' => 9, // Количество постов на странице
      'paged'          => $paged,
      'post_status'    => 'publish',
      'post_parent'    => $id_page, // Указываем родительскую категорию
      'meta_key'      => '_wp_page_template', // Мета-ключ для шаблона
      'meta_value'    => TEMPLATE_NAME::PAGE_GK, // Имя шаблона     
      'meta_query'     => array(
        'relation' => 'AND', // Указываем, что условия должны выполняться одновременно
      ),
    );

    // Добавляем условия для фильтрации по цене, если они заданы
    if (!empty($filter_price_ot)) {
      $args['meta_query'][] = array(
        'key'     => !empty($filter_check_price) ? 'crb_gk_min_price' : 'crb_gk_min_price_meter', // Мета-ключ для минимальной цены
        'value'   => $filter_price_ot,
        'compare' => '>=', // Больше или равно
        'type'    => 'NUMERIC' // Указываем, что сравниваются числовые значения
      );
    }

    if (!empty($filter_price_do)) {
      $args['meta_query'][] = array(
        'key'     => !empty($filter_check_price) ? 'crb_gk_min_price' : 'crb_gk_min_price_meter', // Мета-ключ для минимальной цены
        'value'   => $filter_price_do,
        'compare' => '<=', // Меньше или равно
        'type'    => 'NUMERIC' // Указываем, что сравниваются числовые значения
      );
    }

    if (!empty($filter_area_ot)) {
      $args['meta_query'][] = array(
        'key'     => 'crb_gk_min_area',
        'value'   => $filter_area_ot,
        'compare' => '>=', // Больше или равно
        'type'    => 'NUMERIC' // Указываем, что сравниваются числовые значения
      );
    }

    if (!empty($filter_area_do)) {
      $args['meta_query'][] = array(
        'key'     => 'crb_gk_max_area',
        'value'   => $filter_area_do,
        'compare' => '<=', // Меньше или равно
        'type'    => 'NUMERIC' // Указываем, что сравниваются числовые значения
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
      <section class="catalog-gk">
        <div class="catalog-gk__container">
          <div class="favorites__title-wrapper">
            <div class="favorites__back-button">
              <?php $referer = wp_get_referer() ?>
              <a class="" href="<?php echo esc_url($referer) ?>">
                <img src="<?php bloginfo('template_url'); ?>/assets/images/back.svg" alt="" />
              </a>
            </div>
            <div class="title-wrapper">
              <h1 class="catalog-gk__title title--xl title--catalog">
                <?php echo $crb_new_building_title . ' ' . $title_city; ?>
              </h1>
              <p class="catalog-gk__subtitle">Найдено <?php echo num_word($total_posts, DEFAULT_ENUM::RESIDENTAL_COMPLEX) ?></p>
            </div>
          </div>
          <ul id='content-container-new-buildings' class="catalog-wrapper">

            <?php
            function pluralForm($number, $forms)
            {
              $cases = array(2, 0, 1, 1, 1, 2);
              return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
            }


            if ($query->have_posts()) {

              while ($query->have_posts()) {
                $query->the_post();

                $params = [
                  'id' => get_the_ID(),
                  'crb_gk_name' => carbon_get_post_meta(get_the_ID(), 'crb_gk_name'),
                  'crb_gk_plan' => carbon_get_post_meta(get_the_ID(), 'crb_gk_plan'),
                  'crb_gk_gallery' => carbon_get_post_meta(get_the_ID(), 'crb_gk_gallery'),
                  'crb_gk_description' => carbon_get_post_meta(get_the_ID(), 'crb_gk_description'),
                  'crb_gk_city' => carbon_get_post_meta(get_the_ID(), 'crb_gk_city'),
                  'crb_gk_address' => carbon_get_post_meta(get_the_ID(), 'crb_gk_address'),
                  'crb_gk_permalink' => get_permalink(),
                ];

                get_template_part('template-page/components/card_gk', null, $params);
              }
              echo '</ul>';
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