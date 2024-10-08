<?php
/*
Template Name: Страница избранное
*/
require_once get_template_directory() . '/inc/lib/get_cookies_favorites.php';
require_once get_template_directory() . '/inc/lib/get_image_url.php';
get_header();
?>

<main class="page">
    <div class="main-favorites">
        <?php $crb_favorites_title = carbon_get_post_meta(get_the_ID(), 'crb_favorites_title');

        $dblocation    = 'belgodom.ru';
        $dbuser        = 'belgodac_2bishop';
        $dbpasswd    = 'f*V2XwKK';
        $dbname        = 'belgodac_2bishop';
        $imgpath    = 'https://2bishop.ru/pictures/';
        $avatarpath = 'https://2bishop.ru/files/avatars/';
        $yandexKey    = "";
        $oblast        = "Краснодарский край";
        $obl_coord    = "45.035470, 38.975313";
        $agency        = 23286;
        $obl           = 23;
        $city          = 23286;
        $filter        = 1;
        $scriptpath = '/wp-content/mlsfiles/';
        $pagelink1    = '/kvartiry/';
        $pagelink1r    = '/komnaty/';
        $pagelink1nb = '/novostrojki/';
        $pagelink2    = '/doma/';
        $pagelink2_1 = '/uchastki/';
        $pagelink4    = '/kommercheskaya/';
        $countSale = 6;

        include "wp-content/themes/realty/sql-requests/sql-request-post-catalog.php";

        $post_ids_categories = get_cookies_favorites(true);
        $arrayCategories = array();

        foreach ($post_ids_categories as $str) {
            $value = explode(",", $str);
            $category =  isset($value[0]) ? $value[0] : '';
            $number = isset($value[1]) ? intval($value[1]) : '';
            $categoryType = isset($value[2]) ? $value[2] : '';

            if (!empty($categoryType) && !empty($number) && !empty($category)) {

                $obj = array("category" => $category, "number" => $number, "type" => $categoryType);

                $arrayCategories[] = $obj;
            }
        }


        $dbcnx = mysqli_connect($dblocation, $dbuser, $dbpasswd, $dbname);
        if (!$dbcnx) exit();

        $searchApart = array();
        $searchHouses = array();
        $searchCommerc = array();
        $searchUchastok = array();
        $searchNewAparts = array();


        if (count($arrayCategories) > 0) {
            foreach ($arrayCategories as $category) {
                if ($category['category'] === 'APARTS') {
                    $query = $sql_text_aparts . " FROM APARTS WHERE COMPID = " . $agency . " AND CARDNUM = " . $category['number'];
                    $result = mysqli_query($dbcnx, $query);

                    if ($result && $category['type'] === 'NEW-APARTS') {
                        $searchNewAparts[] = $result;
                    } else if ($result && $category['type'] !== 'NEW-APARTS') {
                        $searchApart[] = $result;
                    } else {
                        echo mysqli_error($dbcnx);
                    }
                }

                if ($category['category'] === 'HOUSES') {
                    $query = $sql_text_houses . " FROM HOUSES WHERE COMPID = " . $agency . " AND CARDNUM = " . $category['number'];
                    $result = mysqli_query($dbcnx, $query);

                    if ($result && $category['type'] === 'UCHASTOK') {
                        $searchUchastok[] = $result;
                    } else if ($result && $category['type'] !== 'UCHASTOK') {
                        $searchHouses[] = $result;
                    } else {
                        echo mysqli_error($dbcnx);
                    }
                }

                if ($category['category'] === 'COMMERC') {
                    $query = $sql_text_commerc . " FROM COMMERC WHERE COMPID = " . $agency . " AND CARDNUM = " . $category['number'];
                    $result = mysqli_query($dbcnx, $query);

                    if ($result) {
                        $searchCommerc[] = $result;
                    } else {
                        echo mysqli_error($dbcnx);
                    }
                }
            }
        }

        mysqli_close($dbcnx);

        $post_ids_favorites = get_cookies_favorites();

        if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div class="main-favorites__breadcrumbs">
                          <section class="breadcrumbs">
                            <div class="breadcrumbs__container">
                             ', '
                             </div>
                          </section>
                        </div>');
        }

        $args = array(
            'post_type'      => 'post', // Тип поста
            'posts_per_page' => -1,      // Получить все посты
            'post__in'       => $post_ids_favorites, // Массив ID постов, которые нужно получить
            'orderby'        => 'post__in' // Сохраняем порядок ID
        );

        $query = new WP_Query($args);

        $countPosts = $query->post_count;

        $countPosts = !empty($post_ids_favorites) ? $query->post_count : 0;

        $totalCountFavorites = count($arrayCategories) + $countPosts;


        ?>
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
                                <?php echo $crb_favorites_title; ?>
                            </h1>
                            <p class="favorites__subtitle"><?php echo num_word($totalCountFavorites, array('объявление', 'объявления', 'объявлений')) ?></p>
                        </div>
                    </div>
                    <div id='content-container' class="favorites-wrapper">

                        <?php

                        function pluralForm($number, $forms)
                        {
                            $cases = array(2, 0, 1, 1, 1, 2);
                            return $number . ' ' . $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
                        }



                        if (!empty($post_ids_favorites)) {
                            if ($query->have_posts()) {
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
                                    $product_update_date = get_the_modified_date('d-m-Y', get_the_ID());

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

                                    $is_favorite = in_array(get_the_ID(), $post_ids_favorites);

                                    echo '
                                    <div class="favorites__card">
                                        <article class="favorite-card" onclick="redirectToURL(event,\'' . esc_url($post_permalink) . '\')">
                                            <div class="favorite-card__wrapper card-preview">
                                                <div class="card-preview__gallery preview-gallery">';
                                    if (!empty($product_gallery[0])) {
                                        $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
                                        echo '<div class="preview-gallery__image">
                                                        <div class="preview-gallery__id"><span>ID </span>' . $product_id . '</div>
                                                        <img class="preview" src="' . $image_url[0] . '" alt="" class="">
                                                        <div class="preview-gallery-mobile swiper">
                                                            <div class="preview-gallery-mobile__wrapper swiper-wrapper">';

                                        foreach ($product_gallery as $image_id) {
                                            $image_url = wp_get_attachment_image_src($image_id, 'full');

                                            echo '  <div class="preview-gallery-mobile__slide swiper-slide">
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

                                    echo '  <div class="preview-gallery__gallery">';

                                    if (!empty($product_gallery[1])) {
                                        $image_url = wp_get_attachment_image_src($product_gallery[1], 'full');
                                        $post_permalink = get_permalink(get_the_ID());
                                        echo '  <a href="' . esc_url($post_permalink) . '">
                                                        <img  src="' . $image_url[0] . '" alt="" width="122" height="79">
                                                    </a>';
                                    }
                                    if (!empty($product_gallery[2])) {
                                        $image_url = wp_get_attachment_image_src($product_gallery[2], 'full');
                                        $post_permalink = get_permalink(get_the_ID());
                                        echo '  <a href="' . esc_url($post_permalink) . '">
                                                        <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                                                    </a>';
                                    }
                                    if (!empty($product_gallery[3])) {
                                        $image_url = wp_get_attachment_image_src($product_gallery[3], 'full');
                                        $post_permalink = get_permalink(get_the_ID());
                                        echo '  <a href="' . esc_url($post_permalink) . '">
                                                        <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                                                    </a>';
                                    }
                                    if (!empty($product_gallery[4])) {
                                        $image_url = wp_get_attachment_image_src($product_gallery[4], 'full');
                                        $post_permalink = get_permalink(get_the_ID());
                                        echo '  <a href="' . esc_url($post_permalink) . '">
                                                        <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                                                    </a>';
                                    }
                                    if (!empty($product_gallery[5])) {
                                        $image_url = wp_get_attachment_image_src($product_gallery[5], 'full');
                                        $post_permalink = get_permalink(get_the_ID());
                                        echo '  <a href="' . esc_url($post_permalink) . '">
                                                        <img src="' . $image_url[0] . '" alt="" width="122" height="79">
                                                    </a>';
                                    }

                                    echo '
                                            </div>
                                        </div>
                                        <div class="card-preview__info card-info">
                                            <div class="card-info-title-wrapper">
                                                <h2 class="card-info__title title--lg title--favorite-card-preview">' . $product_title . '</h2>
                                                <p class="card-info__subtitle">' . $product_rooms . '-комн. ' . $product_category . ', ' . $product_area . ' м², ' . $product_floor . '/' . $product_floor_total . ' этаж</p>';

                                    if (!empty($product_label)) {
                                        echo '      <p class="card-info__label">' . $product_label . '</p>';
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
                                        <div class="card-preview__order favorite-order-agent">
                                            <div class="order-agent__image">';

                                    if (!empty($product_agent_photo)) {
                                        $image_url = wp_get_attachment_image_src($product_agent_photo, 'full');
                                        echo '<img src="' . get_image_url($image_url) . '" alt="" class="order-afent" width="78" height="78">';
                                    } else {
                                        echo '<img src="' .  get_template_directory_uri() . '/assets/images/not_agent_card_preview.svg" alt="" class="order-afent" width="78" height="78">';
                                    }

                                    echo '      </div>
                                            <p class="favorite-order-agent__number"> ID <span>' . $product_id . '</span></p>
                                            <div class="favorite-order-agent__button">
                                                <a class="button  button--phone-order" href="tel:' . $product_agent_phone . '"><span>' . $format_phone_agent . '</span></a>
                                            </div>
                                            <div class="favorite-order-agent__callback" ">
                                                <button class="button button--callback" type="button" data-type="popup-form-callback" >
                                                    <span data-type="popup-form-callback">Перезвоните мне</span>
                                                </button>
                                            </div>
                                            <div class="favorite-order-agent__favorites">
                                                <button class="button button--favorites" type="button" data-favorite-cookies="' . get_the_ID() . '" data-button-favorite data-delete-favorite="' . $is_favorite . '">
                                                    <span>';
                                    if ($is_favorite) {
                                        echo "удалить";
                                    } else {
                                        echo "В избранное";
                                    }
                                    echo '          </span>
                                                </button>
                                            </div>
                                            <p class="favorite-order-agent__date">' .  $result . ' назад</p>
                                        </div>
                                    </div>
                                </article>
                            </div>';
                                }
                            }
                        }
                        wp_reset_postdata();

                        foreach ($searchApart as $apart) {

                            while ($author = mysqli_fetch_array($apart)) {

                                $imgpath1 = '';
                                $imgpath2 = '';
                                $imgpath3 = '';
                                $imgpath4 = '';
                                $imgpath5 = '';
                                if ($author['PICTURES'] != '') {
                                    $pics = explode(';', $author['PICTURES']);
                                    $count = 0;
                                    $str = str_replace("http", "", $pics[0], $count);
                                    if ($count > 0) {
                                        if (isset($pics[0])) $imgpath1 = $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $pics[4];
                                    } else {
                                        if (isset($pics[0])) $imgpath1 = $imgpath . $author['COMPID'] . '/' . $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $imgpath . $author['COMPID'] . '/' . $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $imgpath . $author['COMPID'] . '/' . $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $imgpath . $author['COMPID'] . '/' . $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $imgpath . $author['COMPID'] . '/' . $pics[4];
                                    }
                                } else {
                                    $imgpath1 = $scriptpath . 'img/nofoto.jpg';
                                    $imgpath2 = '';
                                    $imgpath3 = '';
                                    $imgpath4 = '';
                                    $imgpath5 = '';
                                }

                                $is_favorite = in_array($author['CARDNUM'], array_column($arrayCategories, 'number'));

                                echo '
                            	<div class="catalog__card">
                            	    <article class="product-card" >
                                        <div class="product-card__wrapper card-preview">
                                            <div class="card-preview__gallery preview-gallery" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\',\'kvartiry\')">
                                	            <script>
                                        	            function linkPageProduct(event,obj,type){
                                    	                    event.preventDefault();     
                                            	            const currentURL = window.location.origin;
                                            	            const newURL = `${currentURL}/${type}/${obj}`;                                                       
                                            	            window.location.href =  newURL;
                                                        }
                                    	            </script>
                                                <div class="preview-gallery__image">
                                                    <div class="preview-gallery__id"><span>ID </span>' . $author['CARDNUM'] . '</div>
                                                        <img class="preview" src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=400&h=300&zc=1">
                                                        <div class="preview-gallery-mobile swiper">
                                                            <div class="preview-gallery-mobile__wrapper swiper-wrapper">';
                                foreach ($pics as $pic) {
                                    echo '  <div class="preview-gallery-mobile__slide swiper-slide">
                                                                                        <img src="' . $imgpath . $author['COMPID'] . '/' . $pic . '" alt="" >
                                                                                    </div>';
                                }
                                echo '      </div>
                                                            <div class="preview-gallery-mobile__pagination">
                                                                <div class="swiper-pagination">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-gallery__gallery">';
                                if ($imgpath1 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="1" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath2 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="2" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath2 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath3 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="3" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath3 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath4 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="4" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath4 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath5 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="5" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath5 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                echo '   </div>
                                            </div>
                                            <div class="card-preview__info card-info" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')">
                                                
                                                    <div class="card-info-title-wrapper">
                                                        <h2 class="card-info__title title--lg title--product-card-preview "></h2>
                                                        <p class="card-info__subtitle">' . ($author['RPLANID'] != 622 ? $author['ROOMS'] . ' комнатная квартира' : 'Студия') . ', ' . $author['AREA'] . ' м²' . ', ' . $author['STAGE'] . ' этаж</p>
                                                        <p class="mb1">' . ($author['CITY'] == 'Область' ? '' : $author['CITY'] . ', ') . ($author['RAION'] != '' ? $author['RAION'] . ', ' : '') . $author['STREET'] . '</p>
                                                        <p class="card-info__price title--lg">
                                                            <span>' . number_format(($author['PRICE'] / 1000), 3, ' ', ' ') . '</span><span> ₽</span>
                                                        </p>
                                                        <p class="card-info__price-one-metr">
                                                            <span>' . number_format(($author['SQMPRICE'] / 1000), 3, ' ', ' ') . '</span> ₽/м²
                                                        </p>
                                                    </div>
                                                    <p class="card-info__description">' . strip_tags($author['MISC']) . '</p>
                                                
                                            </div>
                                            <div class="card-preview__border"></div>
                                            <div class="card-preview__order order-agent">
                                                <div class="order-agent__image">
                                                    <img src="' . ($author['PHOTO'] ? $avatarpath . $author['PHOTO'] : '/wp-content/themes/realty/assets/images/not_agent_card_preview.svg') . '" alt="" class="order-afent" width="78" height="78">
                                                </div>
                                                <p class="order-agent__number"> 
                                                    ID <span>' . $author['CARDNUM'] . '</span>
                                                </p>
                                                <div class="order-agent__button">
                                                    <a onclick="showFullNumber(event)" class="button button--phone-order" href="tel:' . preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $author["PHONE"]) . '"><span>' . substr(preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7 $2 $3 - $4 - $5", $author["PHONE"]), 0, 14) . '...</span></a>
                                                </div>
                                                <div class="order-agent__callback" onclick="setLink(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\',\'kvartiry\')">
                                                    <button class="button button--callback" type="button" data-type="popup-form-callback" >
                                                        <span data-type="popup-form-callback">Перезвоните мне</span>
                                                    </button>
                                                </div>
                                                <div class="order-agent__favorites">
                                                    <button class="button button--favorites" type="button" data-favorite-cookies="' . $author['CARDNUM'] . '" data-category-cookie="APARTS" data-button-favorite data-delete-favorite="' . $is_favorite . '">
                                                        <span>';
                                if ($is_favorite) {
                                    echo "удалить";
                                } else {
                                    echo "В избранное";
                                }
                                echo '  </span>
                                                    </button>
                                                </div>';
                                $now = new DateTime();
                                $ref = new DateTime($author['DATECOR']);
                                $diff = $now->diff($ref);
                                echo '    <p class="order-agent__date">' . ($diff->d > 0 ? num_word($diff->d, array('день', 'дня', 'дней')) . ' назад' : 'сегодня') . '</p>
                                            </div>
                                        </div>
                                    </article>
                                    <script>
                                        function setLink(event, obj, type) {
                                            if (event.target.innerText === "Перезвоните мне") {
                                                const formSeven = document.querySelector("[data-form-callback]");
                                                if (formSeven) {
                                                    const input = formSeven.querySelector(`input[name=your-link]`);                                          
                                                    const currentURL = window.location.origin;
                                                    const newURL = `${currentURL}/${type}/${obj}`;                                                
                                                    input.value = `${newURL}`;
                                                    console.log("link", newURL);
                                                }
                                            }
                                        }
                                    </script>
                                </div>';
                            };
                        }



                        foreach ($searchHouses as $house) {
                            while ($author = mysqli_fetch_array($house)) {

                                $imgpath1 = '';
                                $imgpath2 = '';
                                $imgpath3 = '';
                                $imgpath4 = '';
                                $imgpath5 = '';
                                if ($author['PICTURES'] != '') {
                                    $pics = explode(';', $author['PICTURES']);
                                    $count = 0;
                                    $str = str_replace("http", "", $pics[0], $count);
                                    if ($count > 0) {
                                        if (isset($pics[0])) $imgpath1 = $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $pics[4];
                                    } else {
                                        if (isset($pics[0])) $imgpath1 = $imgpath . $author['COMPID'] . '/' . $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $imgpath . $author['COMPID'] . '/' . $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $imgpath . $author['COMPID'] . '/' . $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $imgpath . $author['COMPID'] . '/' . $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $imgpath . $author['COMPID'] . '/' . $pics[4];
                                    }
                                } else {
                                    $imgpath1 = $scriptpath . 'img/nofoto.jpg';
                                    $imgpath2 = '';
                                    $imgpath3 = '';
                                    $imgpath4 = '';
                                    $imgpath5 = '';
                                }

                                $is_favorite = in_array($author['CARDNUM'], array_column($arrayCategories, 'number'));

                                echo '
                    	
                    	<div class="catalog__card">
                            	    <article class="product-card" >
                                        <div class="product-card__wrapper card-preview">
                                            <div class="card-preview__gallery preview-gallery" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\', \'doma\')">
                                	            <script>
                                        	            function linkPageProduct(event,obj,type){
                                    	                    event.preventDefault();     
                                            	            const currentURL = window.location.origin;
                                            	            const newURL = `${currentURL}/${type}/${obj}`;
                                            	            window.location.href =  newURL;
                                                        }
                                    	            </script>
                                                <div class="preview-gallery__image">
                                                    <div class="preview-gallery__id"><span>ID </span>' . $author['CARDNUM'] . '</div>
                                                        <img class="preview" src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=400&h=300&zc=1">
                                                        <div class="preview-gallery-mobile swiper">
                                                            <div class="preview-gallery-mobile__wrapper swiper-wrapper">';
                                foreach ($pics as $pic) {
                                    echo '  <div class="preview-gallery-mobile__slide swiper-slide">
                                                                                        <img src="' . $imgpath . $author['COMPID'] . '/' . $pic . '" alt="" >
                                                                                    </div>';
                                }
                                echo '      </div>
                                                            <div class="preview-gallery-mobile__pagination">
                                                                <div class="swiper-pagination">
                                                            </div>
                                                        </div>
                                                    </div>
                                                        </div>
                                                            <div class="preview-gallery__gallery">';
                                if ($imgpath1 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="1" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath2 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="2" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath2 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath3 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="3" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath3 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath4 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="4" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath4 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath5 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="5" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath5 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                echo '   </div>
                                                        </div>
                                                        <div class="card-preview__info card-info" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')">
                                                            
                                                                <div class="card-info-title-wrapper">
                                                                    <h2 class="card-info__title title--lg title--product-card-preview "></h2>
                                                                    <p class="card-info__subtitle">' . $author['htype'] . ' ' . $author['AREA'] . ' м²</p>
                                                                    <p class="mb1">' . ($author['EAREA'] > 0 ? 'Участок ' . $author['EAREA'] . ' сот.' : '') . ($author['BEDROOMS'] > 0 ? ' Спален: ' . $author['BEDROOMS'] : '') . '</p>
                                                                    <p class="mb1">' . ($author['CITY'] == 'Область' ? '' : $author['CITY'] . ', ') . ($author['RAION'] != '' ? $author['RAION'] . ', ' : '') . $author['STREET'] . '</p>
                                                                    <p class="card-info__price title--lg">
                                                                        <span>' . number_format(($author['PRICE'] / 1000), 3, ' ', ' ') . '</span><span> ₽</span>
                                                                    </p>
                                                                    ' . ($author['AREA'] > 0 ? '<p class="card-info__price-one-metr">
                                                                                                <span>' . number_format((($author['PRICE'] / $author['AREA']) / 1000), 3, ' ', ' ') . '</span> ₽/м²
                                                                                            </p>' : '') . '
                                                                    
                                                                </div>
                                                                <p class="card-info__description">' . strip_tags($author['MISC']) . '</p>
                                                            
                                                        </div>
                                                        <div class="card-preview__border"></div>
                                                        <div class="card-preview__order order-agent">
                                                            <div class="order-agent__image">
                                                                <img src="' . ($author['PHOTO'] ? $avatarpath . $author['PHOTO'] : '/wp-content/themes/realty/assets/images/not_agent_card_preview.svg') . '" alt="" class="order-afent" width="78" height="78">
                                                            </div>
                                                            <p class="order-agent__number"> 
                                                                ID <span>' . $author['CARDNUM'] . '</span>
                                                            </p>
                                                            <div class="order-agent__button">
                                                                <a onclick="showFullNumber(event)" class="button button--phone-order" href="tel:' . preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $author["PHONE"]) . '"><span>' . substr(preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7 $2 $3 - $4 - $5", $author["PHONE"]), 0, 14) . '...</span></a>
                                                            </div>
                                                            <div class="order-agent__callback" onclick="setLink(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\', \'doma\')">
                                                                <button class="button button--callback" type="button" data-type="popup-form-callback">
                                                                    <span data-type="popup-form-callback">Перезвоните мне</span>
                                                                </button>
                                                            </div>
                                                            <div class="order-agent__favorites">
                                                                <button class="button button--favorites" type="button" data-favorite-cookies="' . $author['CARDNUM'] . '" data-category-cookie="HOUSES" data-button-favorite data-delete-favorite="' . $is_favorite . '">
                                                                    <span>';
                                if ($is_favorite) {
                                    echo "удалить";
                                } else {
                                    echo "В избранное";
                                }
                                echo '  </span>
                                                                </button>
                                                            </div>';
                                $now = new DateTime();
                                $ref = new DateTime($author['DATECOR']);
                                $diff = $now->diff($ref);
                                echo '   <p class="order-agent__date">' . ($diff->d > 0 ? num_word($diff->d, array('день', 'дня', 'дней')) . ' назад' : 'сегодня') . '</p>
                                                        </div>
                                                         <script>
                                                            function setLink(event, obj, type) {
                                                            if (event.target.innerText === "Перезвоните мне") {
                                                                const formSeven = document.querySelector("[data-form-callback]");
                                                                if (formSeven) {
                                                                    const input = formSeven.querySelector(`input[name=your-link]`);     
                                                                    const currentURL = window.location.origin;
                                                                    const newURL = `${currentURL}/${type}/${obj}`; 
                                                                    input.value = `${newURL}`;
                                                                    console.log("link", newURL);
                                                                }
                                                            }
                                                            }
                                                        </script>


                                                    </div>
                                                </article>
                                            </div>';
                            };
                        }



                        foreach ($searchCommerc as $commerc) {
                            while ($author = mysqli_fetch_array($commerc)) {

                                $imgpath1 = '';
                                $imgpath2 = '';
                                $imgpath3 = '';
                                $imgpath4 = '';
                                $imgpath5 = '';
                                if ($author['PICTURES'] != '') {
                                    $pics = explode(';', $author['PICTURES']);
                                    $count = 0;
                                    $str = str_replace("http", "", $pics[0], $count);
                                    if ($count > 0) {
                                        if (isset($pics[0])) $imgpath1 = $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $pics[4];
                                    } else {
                                        if (isset($pics[0])) $imgpath1 = $imgpath . $author['COMPID'] . '/' . $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $imgpath . $author['COMPID'] . '/' . $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $imgpath . $author['COMPID'] . '/' . $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $imgpath . $author['COMPID'] . '/' . $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $imgpath . $author['COMPID'] . '/' . $pics[4];
                                    }
                                } else {
                                    $imgpath1 = $scriptpath . 'img/nofoto.jpg';
                                    $imgpath2 = '';
                                    $imgpath3 = '';
                                    $imgpath4 = '';
                                    $imgpath5 = '';
                                }

                                $is_favorite = in_array($author['CARDNUM'], array_column($arrayCategories, 'number'));

                                echo '
                        	
                        	<div class="catalog__card">
                                	    <article class="product-card" >
                                            <div class="product-card__wrapper card-preview">
                                                <div class="card-preview__gallery preview-gallery" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\',\'kommercheskaya\')">
                                                    <script>
                                        	            function linkPageProduct(event,obj,type){
                                    	                    event.preventDefault();     
                                            	            const currentURL = window.location.origin;
                                            	            const newURL = `${currentURL}/${type}/${obj}`;
                                            	            window.location.href =  newURL;
                                                        }
                                    	            </script>
                                                    <div class="preview-gallery__image">
                                                        <div class="preview-gallery__id"><span>ID </span>' . $author['CARDNUM'] . '</div>
                                                            <img class="preview" src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=400&h=300&zc=1">
                                                            <div class="preview-gallery-mobile swiper">
                                                                <div class="preview-gallery-mobile__wrapper swiper-wrapper">';
                                foreach ($pics as $pic) {
                                    echo '  <div class="preview-gallery-mobile__slide swiper-slide">
                                                                                            <img src="' . $imgpath . $author['COMPID'] . '/' . $pic . '" alt="" >
                                                                                        </div>';
                                }
                                echo '      </div>
                                                                <div class="preview-gallery-mobile__pagination">
                                                                    <div class="swiper-pagination">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="preview-gallery__gallery">';
                                if ($imgpath1 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="1" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath2 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="2" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath2 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath3 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="3" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath3 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath4 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="4" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath4 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath5 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="5" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath5 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                echo '   </div>
                                                </div>
                                                <div class="card-preview__info card-info" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')">
                                                   
                                                        <div class="card-info-title-wrapper">
                                                            <h2 class="card-info__title title--lg title--product-card-preview "></h2>
                                                            <p class="card-info__subtitle">' . $author['OBJ'] . ' ' . $author['AREA'] . ' м²</p>
                                                            ' . ($author['EAREA'] > 0 ? '<p clas="mb1">' . $author['EAREA'] . ' сот.</p>' : '') . '
                                                            <p class="mb1">' . ($author['CITY'] == 'Область' ? '' : $author['CITY'] . ', ') . ($author['RAION'] != '' ? $author['RAION'] . ', ' : '') . $author['STREET'] . '</p>
                                                            <p class="card-info__price title--lg">
                                                                <span>' . number_format(($author['PRICE'] / 1000), 3, ' ', ' ') . '</span><span> ₽</span>
                                                            </p>
                                                        </div>
                                                        <p class="card-info__description">' . strip_tags($author['MISC']) . '</p>
                                                    
                                                </div>
                                                <div class="card-preview__border"></div>
                                                <div class="card-preview__order order-agent">
                                                    <div class="order-agent__image">
                                                        <img src="' . ($author['PHOTO'] ? $avatarpath . $author['PHOTO'] : '/wp-content/themes/realty/assets/images/not_agent_card_preview.svg') . '" alt="" class="order-afent" width="78" height="78">
                                                    </div>
                                                    <p class="order-agent__number"> ID 
                                                        <span>' . $author['CARDNUM'] . '</span>
                                                    </p>
                                                    <div class="order-agent__button">
                                                        <a onclick="showFullNumber(event)" class="button button--phone-order" href="tel:' . preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $author["PHONE"]) . '"><span>' . substr(preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7 $2 $3 - $4 - $5", $author["PHONE"]), 0, 14) . '...</span></a>
                                                    </div>
                                                    <div class="order-agent__callback" onclick="setLink(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\',\'kommercheskaya\')">
                                                        <button class="button button--callback" type="button" data-type="popup-form-callback">
                                                            <span data-type="popup-form-callback">Перезвоните мне</span>
                                                        </button>
                                                    </div>
                                                    <div class="order-agent__favorites">
                                                        <button class="button button--favorites" type="button" data-favorite-cookies="' . $author['CARDNUM'] . '" data-category-cookie="COMMERC" data-button-favorite data-delete-favorite="' . $is_favorite . '">
                                                            <span>';
                                if ($is_favorite) {
                                    echo "удалить";
                                } else {
                                    echo "В избранное";
                                }
                                echo '  </span>
                                                        </button>
                                                    </div>';
                                $now = new DateTime();
                                $ref = new DateTime($author['DATECOR']);
                                $diff = $now->diff($ref);
                                echo '
                                                    <p class="order-agent__date">' . ($diff->d > 0 ? num_word($diff->d, array('день', 'дня', 'дней')) . ' назад' : 'сегодня') . '</p>
                                                </div>
                                                 <script>
                                                    function setLink(event, obj, type) {
                                                        if (event.target.innerText === "Перезвоните мне") {
                                                            const formSeven = document.querySelector("[data-form-callback]");
                                                            if (formSeven) {
                                                                const input = formSeven.querySelector(`input[name=your-link]`);    
                                                                const currentURL = window.location.origin;
                                                                const newURL = `${currentURL}/${type}/${obj}`;     
                                                                input.value = `${newURL}`;
                                                                console.log("link", newURL);
                                                            }
                                                        }
                                                    }

                                                </script>
                                            </div>
                                        </article>
                                    </div>';
                            };
                        }


                        foreach ($searchUchastok as $uchastok) {
                            while ($author = mysqli_fetch_array($uchastok)) {

                                $imgpath1 = '';
                                $imgpath2 = '';
                                $imgpath3 = '';
                                $imgpath4 = '';
                                $imgpath5 = '';
                                if ($author['PICTURES'] != '') {
                                    $pics = explode(';', $author['PICTURES']);
                                    $count = 0;
                                    $str = str_replace("http", "", $pics[0], $count);
                                    if ($count > 0) {
                                        if (isset($pics[0])) $imgpath1 = $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $pics[4];
                                    } else {
                                        if (isset($pics[0])) $imgpath1 = $imgpath . $author['COMPID'] . '/' . $pics[0];
                                        if (isset($pics[1])) $imgpath2 = $imgpath . $author['COMPID'] . '/' . $pics[1];
                                        if (isset($pics[2])) $imgpath3 = $imgpath . $author['COMPID'] . '/' . $pics[2];
                                        if (isset($pics[3])) $imgpath4 = $imgpath . $author['COMPID'] . '/' . $pics[3];
                                        if (isset($pics[4])) $imgpath5 = $imgpath . $author['COMPID'] . '/' . $pics[4];
                                    }
                                } else {
                                    $imgpath1 = $scriptpath . 'img/nofoto.jpg';
                                    $imgpath2 = '';
                                    $imgpath3 = '';
                                    $imgpath4 = '';
                                    $imgpath5 = '';
                                }

                                $is_favorite = in_array($author['CARDNUM'], array_column($arrayCategories, 'number'));

                                echo '
                        	
                        	<div class="catalog__card">
                                	    <article class="product-card" >
                                            <div class="product-card__wrapper card-preview">
                                                <div class="card-preview__gallery preview-gallery" onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\', \'uchastki\')">
                                    	            <script>
                                        	            function linkPageProduct(event,obj,type){
                                    	                    event.preventDefault();     
                                            	            const currentURL = window.location.origin;
                                            	            const newURL = `${currentURL}/${type}/${obj}`;
                                            	            window.location.href =  newURL;
                                                        }
                                    	            </script>
                                                    <div class="preview-gallery__image">
                                                        <div class="preview-gallery__id"><span>ID </span>' . $author['CARDNUM'] . '</div>
                                                            <img class="preview" src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=400&h=300&zc=1">
                                                            <div class="preview-gallery-mobile swiper">
                                                                <div class="preview-gallery-mobile__wrapper swiper-wrapper">';
                                foreach ($pics as $pic) {
                                    echo '                                              <div class="preview-gallery-mobile__slide swiper-slide">
                                                                                            <img src="' . $imgpath . $author['COMPID'] . '/' . $pic . '" alt="" >
                                                                                        </div>';
                                }
                                echo '                          </div>
                                                                <div class="preview-gallery-mobile__pagination">
                                                                    <div class="swiper-pagination">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                            						<div class="preview-gallery__gallery">';
                                if ($imgpath1 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="1" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath1 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath2 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="2" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath2 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath3 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="3" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath3 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath4 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="4" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath4 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                if ($imgpath5 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="5" href="#"><img src="' . $scriptpath . 'timthumb.php?src=' . $imgpath5 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
                                echo '              </div>
                                                </div>
                                                <div class="card-preview__info card-info">
                                                    <a href="?obj=' . $author['CARDNUM'] . '">
                                                        <div class="card-info-title-wrapper">
                                                            <h2 class="card-info__title title--lg title--product-card-preview "></h2>
                                                            <p class="card-info__subtitle">Участок ' . $author['EAREA'] . ' сот.</p>
                                                            <p class="mb1">' . ($author['USAGEID'] != '' ? 'земли ' . mb_strtolower($author['USAGEID']) : '') . '</p>
                                                            <p class="mb1">' . ($author['CITY'] == 'Область' ? '' : $author['CITY'] . ', ') . ($author['RAION'] != '' ? $author['RAION'] . ', ' : '') . $author['STREET'] . '</p>
                                                            <p class="card-info__price title--lg">
                                                                <span>' . number_format(($author['PRICE'] / 1000), 3, ' ', ' ') . '</span><span> ₽</span>
                                                            </p>
                                                            ' . ($author['EAREA'] > 0 ? '<p class="card-info__price-one-metr">
                                                                                        <span>' . number_format((($author['PRICE'] / $author['EAREA']) / 1000), 3, ' ', ' ') . '</span> ₽/сотка' : '') . '
                                                                                    </p>        
                                                        </div>
                                                        <p class="card-info__description">' . strip_tags($author['MISC']) . '</p>
                                                    </a>
                                                </div>
                                                <div class="card-preview__border"></div>
                                                <div class="card-preview__order order-agent">
                                                    <div class="order-agent__image">
                                                        <img src="/wp-content/themes/realty/assets/images/not_agent_card_preview.svg" alt="" class="order-afent" width="78" height="78">
                                                    </div>
                                                    <p class="order-agent__number"> ID 
                                                        <span>' . $author['CARDNUM'] . '</span>
                                                    </p>
                                                    <div class="order-agent__button">
                                                        <a onclick="showFullNumber(event)" class="button button--phone-order" href="tel:' . preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $author["PHONE"]) . '"><span>' . substr(preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7 $2 $3 - $4 - $5", $author["PHONE"]), 0, 14) . '...</span></a>
                                                    </div>
                                                    <div class="order-agent__callback" onclick="setLink(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\', \'uchastki\')">
                                                        <button class="button button--callback" type="button" data-type="popup-form-callback">
                                                            <span data-type="popup-form-callback">Перезвоните мне</span>
                                                        </button>
                                                    </div>
                                                    <div class="order-agent__favorites">
                                                        <button class="button button--favorites" type="button" data-favorite-cookies="' . $author['CARDNUM'] . '" data-category-cookie="HOUSES" data-category-type-cookie="UCHASTOK"  data-button-favorite data-delete-favorite="' . $is_favorite . '">
                                                            <span>';
                                if ($is_favorite) {
                                    echo "удалить";
                                } else {
                                    echo "В избранное";
                                }
                                echo '  </span>
                                                        </button>
                                                    </div>';
                                $now = new DateTime();
                                $ref = new DateTime($author['DATECOR']);
                                $diff = $now->diff($ref);
                                echo '
                                                    <p class="order-agent__date">' . ($diff->d > 0 ? num_word($diff->d, array('день', 'дня', 'дней')) . ' назад' : 'сегодня') . '</p>
                                                </div>
                                                <script>
                                                    function setLink(event, obj, type) {
                                                        if (event.target.innerText === "Перезвоните мне") {
                                                            const formSeven = document.querySelector("[data-form-callback]");
                                                            if (formSeven) {
                                                                const input = formSeven.querySelector(`input[name=your-link]`);  
                                                                const currentURL = window.location.origin;
                                                                const newURL = `${currentURL}/${type}/${obj}`;     
                                                                input.value = `${newURL}`;
                                                                console.log("link", newURL);
                                                            }
                                                        }
                                                    }
                                                </script>
                                              </div>
                                            </article>
                            	        </div>';
                            };
                        }


                        ?>
                        <script>
                            function redirectToURL(event, url) {
                                if (event.target.innerText !== "Перезвоните мне") {
                                    window.location.href = url;
                                }
                                if (event.target.innerText === "Перезвоните мне") {
                                    const formSeven = document.querySelector('[data-form-callback]');

                                    if (formSeven) {
                                        const input = formSeven.querySelector(`input[name=your-link]`);
                                        input.value = `${url}`;
                                        console.log("url", url);
                                    }
                                }
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