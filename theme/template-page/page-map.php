<?php
/*
Template Name: Страница карта
*/



require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/names_sities.php';
require_once get_template_directory() . '/inc/enums/template_name.php';
require_once get_template_directory() . '/inc/lib/get_query_filter_catalog.php';
require_once get_template_directory() . '/inc/lib/get_title_city.php';
require_once get_template_directory() . '/inc/lib/sort_gk.php';

get_header();

?>
<main class="page page-map">
    <div class="main-favorites">
        <?php $crb_new_building_title = carbon_get_post_meta(get_the_ID(), 'crb_new_building_title');

        global  $names_default_cities;

        $filter_region = isset($_GET['region']) ? $_GET['region'] : DEFAULT_ENUM::DEFAULT_FILTER_REGION;

        $dafault_city = $names_default_cities[$filter_region] ?? DEFAULT_ENUM::DEFAULT_FILTER_CITY;
        $filter_city = isset($_GET['city']) ? $_GET['city'] : $dafault_city;

        $title_city =  get_title_city($filter_city);

        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $query = get_query_filter_catalog($paged);

        $total_posts = $query->found_posts;
        $locations = [];


        if ($query->have_posts()) {

            while ($query->have_posts()) {
                $query->the_post();
                $id_post = get_the_ID();
                $title = get_the_title();
                $crb_gk_latitude = carbon_get_post_meta($id_post, 'crb_gk_latitude');
                $crb_gk_longitude = carbon_get_post_meta($id_post, 'crb_gk_longitude');

                $locations[] = ['coordinates' => [$crb_gk_longitude, $crb_gk_latitude], 'balloonContent' => $title, 'link_gk' => get_permalink($id_post)];
            }


            wp_reset_postdata();
        }

        $params_map = [
            'city' => $filter_city,
            'coordinates_center' => isset($locations[0]) ? $locations[0]['coordinates'] : [],
            'locations' => $locations,
            'title' => 'Новостройки ' . $title_city,
            'is_padding' => true,
            'zoom' => 13,
        ];


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


    </div>

    <div class="single-page catalog-gk__map">
        <?php get_template_part('template-page/blocks/yandex_map', null, $params_map); ?>
    </div>

</main>
<?php
get_footer();
?>