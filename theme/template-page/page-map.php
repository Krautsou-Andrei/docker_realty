<?php
/*
Template Name: Страница карта
*/



require_once get_template_directory() . '/inc/enums/default_enum.php';
require_once get_template_directory() . '/inc/enums/template_name.php';
require_once get_template_directory() . '/inc/lib/get_query_filter_catalog.php';
require_once get_template_directory() . '/inc/lib/sort_gk.php';
require_once get_template_directory() . '/inc/maps/map_cities.php';
require_once get_template_directory() . '/inc/maps/map_title_by_cities.php';

get_header();

?>
<main class="page page-map">
    <div class="main-favorites">
        <?php $crb_new_building_title = carbon_get_post_meta(get_the_ID(), 'crb_new_building_title');

        $filter_city = isset($_GET['city']) ? $_GET['city'] : '2306';

        $search_param_city = isset($MAP_CITIES[$filter_city]) ? $MAP_CITIES[$filter_city] : 'Новороссийск';
        $title_city =  isset($MAP_TITLE_BY_CITIES[$filter_city]) ? $MAP_TITLE_BY_CITIES[$filter_city] : 'в Краснодаре';

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
            'city' => $search_param_city,
            'coordinates_center' => isset($locations[0]) ? $locations[0]['coordinates'] : [],
            'locations' => $locations,
            'title' => 'Новостройки в Новороссийске',
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