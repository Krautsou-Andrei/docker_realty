<?php
if (!defined("ABSPATH")) {
    exit;
}

function category_card($author, $option_category_card)
{
    $imgpath1 = '';
    $imgpath2 = '';
    $imgpath3 = '';
    $imgpath4 = '';
    $imgpath5 = '';
    if (!empty($option_category_card['isPost'])) {
        if (!empty($author['PICTURES'][0]))  $imgpath1 = $author['PICTURES'][0];
        if (!empty($author['PICTURES'][1]))  $imgpath2 = $author['PICTURES'][1];
        if (!empty($author['PICTURES'][2]))  $imgpath3 = $author['PICTURES'][2];
        if (!empty($author['PICTURES'][3]))  $imgpath4 = $author['PICTURES'][3];
        if (!empty($author['PICTURES'][4]))  $imgpath5 = $author['PICTURES'][4];
    } else if ($author['PICTURES'] != '') {
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
            if (isset($pics[0])) $imgpath1 = $option_category_card['imgpath'] . $author['COMPID'] . '/' . $pics[0];
            if (isset($pics[1])) $imgpath2 = $option_category_card['imgpath'] . $author['COMPID'] . '/' . $pics[1];
            if (isset($pics[2])) $imgpath3 = $option_category_card['imgpath'] . $author['COMPID'] . '/' . $pics[2];
            if (isset($pics[3])) $imgpath4 = $option_category_card['imgpath'] . $author['COMPID'] . '/' . $pics[3];
            if (isset($pics[4])) $imgpath5 = $option_category_card['imgpath'] . $author['COMPID'] . '/' . $pics[4];
        }
    }

    if (empty($author['PICTURES']) || $author['PICTURES'] == '') {
        $imgpath1 = get_template_directory_uri() . '/assets/images/no_image.png';;
    }


    $is_favorite = in_array($author['CARDNUM'], array_column($option_category_card['arrayCategories'], 'number'));

    $current_obj = esc_url('?obj=' . $author['CARDNUM']);

    if (!empty($option_category_card['isPost'])) {
        $current_obj = $author['POST_PERMALINK'];
    };

    $category_cookies = 'APARTS';

    if (isset($option_category_card['isArea']) && $option_category_card['isArea']) {
        $category_cookies = 'HOUSES';
    }
    if (isset($option_category_card['isHome']) && $option_category_card['isHome']) {
        $category_cookies = 'HOUSES';
    }
    if (isset($option_category_card['isCommerce']) && $option_category_card['isCommerce']) {
        $category_cookies = 'COMMERC';
    }




    echo '  
        	<div class="catalog__card" onclick="linkPageProduct(event,\'' . $current_obj . '\')">                    
        	  <article class="product-card" >
              <div class="product-card__wrapper card-preview">
               <div class="card-preview__gallery preview-gallery" onclick="linkPageProduct(event,\'' . $current_obj . '\')">
                    <script>
                        function linkPageProduct(event,obj){
                            event.preventDefault();
                            const currentURL = window.location.origin;
                            let newURL = currentURL;                           
                                if (' . (isset($option_category_card['isAparts']) && $option_category_card['isAparts'] === true ? 'true' : 'false') . ') {
                                    newURL += `/kvartiry/${obj}`;
                                } 
                                if (' . (isset($option_category_card['isRooms']) && $option_category_card['isRooms'] === true ? 'true' : 'false') . ') {
                                    newURL += `/komnaty/${obj}`;
                                } 
                                if (' . (isset($option_category_card['isArea']) && $option_category_card['isArea'] === true ? 'true' : 'false') . ') {
                                    newURL += `/uchastki/${obj}`;
                                } 
                                if (' . (isset($option_category_card['isHome']) && $option_category_card['isHome'] === true ? 'true' : 'false') . ') {
                                    newURL += `/doma/${obj}`;
                                } 
                                if (' . (isset($option_category_card['isCommerce']) && $option_category_card['isCommerce'] === true ? 'true' : 'false') . ') {
                                    newURL += `/kommercheskaya/${obj}`;
                                } 
                                if (' . (isset($option_category_card['isNewBuilds']) && $option_category_card['isNewBuilds'] === true ? 'true' : 'false') . ') {
                                    newURL += `/novostrojki/${obj}`;
                                } 
                                if (' . (isset($option_category_card['isPost']) && $option_category_card['isPost'] === true ? 'true' : 'false') . ') {
                                    newURL = `${obj}`;                               
                                } 
                            if(event.target.innerText !== "Перезвоните мне"){
                                window.location.href = newURL;
                            }
                              if(event.target.innerText === "Перезвоните мне"){
                              const formSeven = document.querySelector("[data-form-callback]");

                                if (formSeven) {
                                const input = formSeven.querySelector(`input[name=your-link]`);
                                input.value = `${newURL}`;                              
                                }
                              }  
                            
                        }
                    </script>
                  <div class="preview-gallery__image">
                    <div class="preview-gallery__id"><span>ID </span>' . $author['CARDNUM'] . '</div>
                    <img loading="lazy" class="preview" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath1 . '&w=400&h=300&zc=1">
                    <div class="preview-gallery-mobile swiper">
                      <div class="preview-gallery-mobile__wrapper swiper-wrapper">';
    foreach ($pics as $pic) {
        echo '    <div class="preview-gallery-mobile__slide swiper-slide">
                          <img class="swiper-lazy" data-src="' . $option_category_card['imgpath'] . $author['COMPID'] . '/' . $pic . '" src="' . get_template_directory_uri() . '/assets/images/1px.png" alt="" >
                          <div class="swiper-lazy-preloader"></div>
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

    if (!empty($option_category_card['isPost'])) {
        if ($imgpath1 != '') echo '<a href="' . esc_url($author['POST_PERMALINK'])  . '" class="1" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath1 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath2 != '') echo '<a href="' . esc_url($author['POST_PERMALINK'])  . '" class="2" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath2 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath3 != '') echo '<a href="' . esc_url($author['POST_PERMALINK'])  . '" class="3" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath3 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath4 != '') echo '<a href="' . esc_url($author['POST_PERMALINK'])  . '" class="4" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath4 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath5 != '') echo '<a href="' . esc_url($author['POST_PERMALINK'])  . '" class="5" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath5 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
    } else {
        if ($imgpath1 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="1" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath1 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath2 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="2" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath2 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath3 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="3" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath3 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath4 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="4" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath4 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
        if ($imgpath5 != '') echo '<a onclick="linkPageProduct(event,\'' . esc_url('?obj=' . $author['CARDNUM']) . '\')" class="5" href="#"><img loading="lazy" src="' . $option_category_card['scriptpath'] . 'timthumb.php?src=' . $imgpath5 . '&w=122&h=79&zc=1" alt="" width="122" height="79"></a>';
    }



    echo '    </div>
              </div>
              <div class="card-preview__info card-info">               
                  <div class="card-info-title-wrapper">
                    <h2 class="card-info__title title--lg title--product-card-preview "></h2>                    
                    <p class="card-info__subtitle">';


    if (!empty($option_category_card['isAparts']) || !empty($option_category_card['isNewBuilds'])) {
        echo ($author['RPLANID'] != 622 ? $author['ROOMS'] . ' комнатная квартира' : 'Студия') . ', ' . $author['AREA'] . ' м²' . ', ' . $author['STAGE'] . ' этаж';
    };
    if (!empty($option_category_card['isPost'])) {
        echo ($author['IS_STUDIO_APARTMENT'] === true ? 'Студия' : $author['ROOMS'] . ' комнатная квартира') . ', ' . $author['AREA'] . ' м²' . ', ' . $author['STAGE'] . ' этаж';
    };
    if (!empty($option_category_card['isRooms'])) {
        echo ($author['RPLANID'] != 622 ? 'Комната в ' . $author['ROOMS'] . ' ком. квартире' : 'Студия') . ', ' . $author['AREA'] . ' м²' . ', ' . $author['STAGE'] . ' этаж';
    };
    if (!empty($option_category_card['isArea'])) {
        echo 'Участок ' . $author['EAREA'] . ' сот.';
    };
    if (!empty($option_category_card['isHome'])) {
        echo '' . $author['htype'] . ' ' . $author['AREA'] . ' м²';
    }
    if (!empty($option_category_card['isCommerce'])) {
        echo '' . $author['OBJ'] . ' ' . $author['AREA'] . ' м²';
    };


    echo '</p>';


    if (!empty($author['PRODUCT_LABEL'])) {
        echo '<p class="card-info__label">' . $author['PRODUCT_LABEL'] . '</p>';
    }


    if (!empty($option_category_card['isArea'])) {
        echo '<p class="mb1">' . ($author['USAGEID'] != '' ? 'земли ' . mb_strtolower($author['USAGEID']) : '') . '</p>';
    };
    if (!empty($option_category_card['isHome'])) {
        echo '<p class="mb1">' . ($author['EAREA'] > 0 ? 'Участок ' . $author['EAREA'] . ' сот.' : '') . ($author['BEDROOMS'] > 0 ? ' Спален: ' . $author['BEDROOMS'] : '') . '</p>';
    }
    if (!empty($option_category_card[''])) {
        echo ($author['EAREA'] > 0 ? '<p clas="mb1">' . $author['EAREA'] . ' сот.</p>' : '');
    }

    if (!empty($option_category_card['isNewBuilds'])) {
        echo ' ' . ($author['GK'] != '' ? '<p class="mb1">' . $author['GK'] . '</p>' : '') . '';
    }


    echo '  <p class="mb1">' . ($author['CITY'] == 'Область' ? '' : $author['CITY'] . ', ') . ($author['RAION'] != '' ? $author['RAION'] . ', ' : '') . $author['STREET'] . '</p>
            <p class="card-info__price title--lg">
                <span> ' . number_format(($author['PRICE'] / 1000), 3, ' ', ' ') . '</span><span> ₽</span>
            </p>
            <p class="card-info__price-one-metr">';


    if (!empty($option_category_card['isAparts']) || !empty($option_category_card['isRooms']) || !empty($option_category_card['isNewBuilds'])) {
        echo ' <span>' . number_format(($author['SQMPRICE'] / 1000), 3, ' ', ' ') . '</span> ₽/м²';
    };
    if (!empty($option_category_card['isArea'])) {
        echo ($author['EAREA'] > 0 ? '<span>' . number_format((($author['PRICE'] / $author['EAREA']) / 1000), 3, ' ', ' ') . '</span> ₽/сотка' : '');
    };
    if (!empty($option_category_card['isHome'])) {
        echo ($author['AREA'] > 0 ? '<span>' . number_format((($author['PRICE'] / $author['AREA']) / 1000), 3, ' ', ' ') . '</span> ₽/м²' : '');
    }


    echo '</p>
                  </div>
                  <p class="card-info__description">' . strip_tags($author['MISC']) . '</p>                
              </div>
              <div class="card-preview__border"></div>
              <div class="card-preview__order order-agent">
                <div class="order-agent__image">';
    if (!empty($option_category_card['isPost'])) {
        echo '  <img loading="lazy" src="' . ($author['PHOTO'] ?  $author['PHOTO'] : '/wp-content/themes/realty/assets/images/not_agent_card_preview.svg') . '" alt="" class="order-afent" width="78" height="78">';
    } else {
        echo ' <img loading="lazy" src="' . ($author['PHOTO'] ? $option_category_card['avatarpath'] . $author['PHOTO'] : '/wp-content/themes/realty/assets/images/not_agent_card_preview.svg') . '" alt="" class="order-afent" width="78" height="78">';
    };


    echo '</div>
                <p class="order-agent__number"> 
                    ID <span>' . $author['CARDNUM'] . '</span>
                </p>
                <div class="order-agent__button">
                    <a onclick="showFullNumber(event)" class="button button--phone-order" href="tel:' . preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $author["PHONE"]) . '"><span>' . substr(preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7 $2 $3 - $4 - $5", $author["PHONE"]), 0, 14) . '...</span></a>
                </div>
                <div class="order-agent__callback">
                    <button class="button button--callback" type="button" data-type="popup-form-callback" >
                        <span data-type="popup-form-callback">Перезвоните мне</span>
                    </button>
                </div>
                <div class="order-agent__favorites">
                    <button class="button button--favorites" type="button" data-favorite-cookies="' . $author['CARDNUM'] . '" data-category-cookie="' . $category_cookies . '" data-button-favorite data-delete-favorite="' . $is_favorite . '">
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
            </div>';
}

add_action('view_category_card', 'category_card', 10, 4);
