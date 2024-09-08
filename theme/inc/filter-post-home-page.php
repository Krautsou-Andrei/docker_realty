<?php
if (!defined("ABSPATH")) {
    exit;
}

// Функция для загрузки постов
function filter_posts_home_page_handler()
{
    
    $dblocation	= 'belgodom.ru';
    $dbuser		= 'belgodac_2bishop';
    $dbpasswd	= 'f*V2XwKK';
    $dbname		= 'belgodac_2bishop';
	$imgpath	= 'https://2bishop.ru/pictures/';
	$avatarpath = 'https://2bishop.ru/files/avatars/';
	$yandexKey	= "";
	$oblast		= "Краснодарский край";
	$obl_coord	= "45.035470, 38.975313";
    $agency		= 23286;
	$obl       	= 23;
	$city      	= 23286;
	$filter		= 1;
	$scriptpath = '/wp-content/mlsfiles/';
    $pagelink1	= '/kvartiry/';
    $pagelink1r	= '/komnaty/';
    $pagelink1nb= '/novostrojki/';
    $pagelink2	= '/doma/';
    $pagelink2_1= '/uchastki/';
    $pagelink4	= '/kommercheskaya/'; 
    $realty_limit       = 6;
    
    $sql_text_aparts = 'select COMPID,CARDNUM,
    CONCAT((SELECT VOCSTREET.NAME FROM VOCSTREET WHERE VOCSTREET.ID=STREETID),if(HAAP<>" ",CONCAT(", дом&nbsp;",HAAP), "")) AS STREET,
    ROOMS,
    (SELECT VOCRAION.NAME FROM VOCRAION WHERE VOCRAION.ID=RAIONID) AS RAION,
    concat(STAGE, "/", HSTAGE) AS STAGE,
    concat(TRUNCATE(AAREA,0), "/", TRUNCATE(LAREA,0), "/", TRUNCATE(KAREA,0)) AS AREA,
    PICTURES,
    PRICE,
    (SELECT VOCCITY.NAME FROM VOCCITY WHERE VOCCITY.ID=APARTS.CITYID) AS CITY, 
    RPLANID,
    MISC
    from APARTS';
    
    $sql_text_houses = 'select COMPID,CARDNUM,
    CONCAT((SELECT VOCSTREET.NAME FROM VOCSTREET WHERE VOCSTREET.ID=STREETID),if(HAAP<>" ",CONCAT(", дом&nbsp;",HAAP), "")) AS STREET,
    WHAT,
    (SELECT VOCRAION.NAME FROM VOCRAION WHERE VOCRAION.ID=RAIONID) AS RAION,
    HSTAGE AS STAGE,
    round(AAREA) AS AREA,
    round(EAREA) AS EAREA,
    PICTURES,
    PRICE,
    (SELECT VOCCITY.NAME FROM VOCCITY WHERE VOCCITY.ID=HOUSES.CITYID) AS CITY,
    VTOUR,
    BEDROOMS,
    (SELECT VOCALLDATA.NAME FROM VOCALLDATA WHERE VOCALLDATA.ID=BTYPEID) as htype,
    (SELECT VOCALLDATA.NAME FROM VOCALLDATA WHERE VOCALLDATA.ID=USAGEID) as USAGEID,
    MISC
    from HOUSES';
    
    $sql_text_commerc = 'select COMPID,CARDNUM,
    CONCAT((SELECT VOCSTREET.NAME FROM VOCSTREET WHERE VOCSTREET.ID=STREETID),if(HAAP<>" ",CONCAT(", дом&nbsp;",HAAP), "")) AS STREET,
    (SELECT VOCRAION.NAME FROM VOCRAION WHERE VOCRAION.ID=RAIONID) AS RAION,
    HSTAGE AS STAGE,
    round(SQUEAR) AS AREA,
    round(EAREA) AS EAREA,
    PICTURES,
    PRICE,
    (SELECT VOCCITY.NAME FROM VOCCITY WHERE VOCCITY.ID=COMMERC.CITYID) AS CITY,
    VTOUR,
    (SELECT VOCALLDATA.NAME FROM VOCALLDATA WHERE VOCALLDATA.ID=OBJID) as OBJ,
    MISC
    from COMMERC';  
   
    $filter_type = $_POST['filterType'];
    
    ob_start();

    $documents_markup = '';
    $test= array();
    
    if($filter_type === 'kvartiry'){
            
        $dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
        if (!$dbcnx)exit();
        
        $where=' WHERE COMPID='.$agency.' AND PICCOUNT>0 ';
        $contentAPARTS = mysqli_query($dbcnx,$sql_text_aparts.$where.' and WHAT=0 and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$realty_limit);
        $test = $contentAPARTS;
        mysqli_close($dbcnx);
        
        if($contentAPARTS){
            if(mysqli_num_rows($contentAPARTS)>0){
                while($author = mysqli_fetch_array($contentAPARTS)){
                    $documents_markup .= '<li class="promo-slider__slide swiper-slide slide" data-aparts><a href="'.$pagelink1.'?obj='.$author['CARDNUM'].'">';
                    if($author["PICTURES"]!='')
                    {
                       $documents_markup .='<div class="slide-image">';
                         
                           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
                           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
                           $documents_markup .= '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
                    	$documents_markup .='</div>';
                    }
            
                    $documents_markup .= '<div class="slide__info info">';
            		   $documents_markup .= '<h3 class="info__title title--lg title--promo-slide">'.($author['RPLANID']!=622?$author['ROOMS'].'&nbsp;комнатная квартира':'Студия').'</h3>';
            		   $documents_markup .= '<p class="info__subtitle">'.$author['AREA'].'&nbsp;м²'.', '.$author['STAGE'].'&nbsp;этаж</p>';
            		   $documents_markup .= '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
            		   $documents_markup .= '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
            		   $documents_markup .= '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                    $documents_markup .= '</div>';
            	   
                    $documents_markup .= '</a></li>';
                }
            }
        }
    }
    
    if($filter_type === 'doma'){
        $dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
        if (!$dbcnx)exit();
        
        $where=' WHERE COMPID='.$agency.' AND PICCOUNT>0 ';
        $contentHOUSES = mysqli_query($dbcnx,$sql_text_houses.$where.' and WHAT=0 and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$realty_limit);
        
        mysqli_close($dbcnx);
        
        if($contentHOUSES){
            if(mysqli_num_rows($contentHOUSES)>0){
                while($author = mysqli_fetch_array($contentHOUSES)){
            	    $documents_markup .= '<li class="promo-slider__slide swiper-slide slide" data-houses><a href="'.$pagelink2.'?obj='.$author['CARDNUM'].'">';
                    if($author["PICTURES"]!='')
                    {
            		   $documents_markup .='<div class="slide-image">';
            	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
            	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
            	           $documents_markup .= '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
            			$documents_markup .='</div>';
            	    }
            
                    $documents_markup .= '<div class="slide__info info">';
            		$documents_markup .= '<h3 class="info__title title--lg title--promo-slide">'.$author['htype']. ' '.$author['AREA'].'&nbsp;м²</h3>';
            		$documents_markup .= '<p class="info__subtitle">'.($author['EAREA']>0 ? 'Участок '.$author['EAREA'].'&nbsp;сот.':'').($author['BEDROOMS']>0 ? ' Спален: '.$author['BEDROOMS'] :'').'</p>';
            		$documents_markup .= '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
            		$documents_markup .= '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
            		$documents_markup .= '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                    $documents_markup .= '</div>';
            	   
                    $documents_markup .= '</a></li>';
                }
            }
        }
        
        
    }
    
    if($filter_type === 'novostrojki'){
        $args = array(
              'post_type' => 'post',
              'posts_per_page' => -1,
              'posts_per_page' => 6,
    );
    
        $query = new WP_Query($args);

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();

              $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
              $product_region = carbon_get_post_meta(get_the_ID(), 'product-region');
              $product_city = carbon_get_post_meta(get_the_ID(), 'product-city');
              $product_address = carbon_get_post_meta(get_the_ID(), 'product-address');
              $product_description = carbon_get_post_meta(get_the_ID(), 'product-description');
              $product_price =  carbon_get_post_meta(get_the_ID(), 'product-price');
              $product_area = carbon_get_post_meta(get_the_ID(), 'product-area');
              $product_filter_compare = carbon_get_post_meta(get_the_ID(), 'product_compare_content');
              $product_filter_more = carbon_get_post_meta(get_the_ID(), 'product_more_content');

              if ($product_area > 0) {
                $product_price_meter = number_format(round(floatval($product_price) / floatval($product_area), 2), 0, '.', ' ');
              }
              $product_price_format = number_format(round(floatval(carbon_get_post_meta(get_the_ID(), 'product-price'))), 0, '.', ' ');


              $documents_markup .= '  <li class="promo-slider__slide swiper-slide slide" data-new-buildings '.($product_more_content ? 'data-filter-more' : '').'  '.($product_filter_compare ? 'data-filter-compare' : '').'>';
              if (!empty($product_gallery[0])) {
                $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
                $post_permalink = get_permalink(get_the_ID());
                $documents_markup .= '<a href="' . esc_url($post_permalink) . '">
                            <div class="slide-image">
                              <img src="' . $image_url[0] . '" alt="" width="380" height="195">
                            </div>';
              }
              $documents_markup .= '
                            <div class="slide__info info">
                              <h3 class="info__title title--lg title--promo-slide">' . $product_city . '</h3>
                              <p class="info__description">' . $product_description . '</p>';

              if (!empty($product_price_meter)) {
                $documents_markup .= '<p class="info__price">от ' . $product_price_meter . ' ₽ за м²</p>';
              } else {
                $documents_markup .= '<p class="info__price">' . $product_price_format . ' ₽</p>';
              }
              $documents_markup .=  '<p class="info__location"><span>' . $product_address . ' ' . $product_city . '</span><span>, ' . $product_region . '</span></p>
                           </div>
                          </a>
                          </li>';
            }
          }
        
    }
    
    if($filter_type === 'uchastki'){
        $dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
        if (!$dbcnx)exit();
        
        $where=' WHERE COMPID='.$agency.' AND PICCOUNT>0 ';
        $contentUCHASTKI = mysqli_query($dbcnx,$sql_text_houses.$where.' and WHAT=1 and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$realty_limit);
        
        mysqli_close($dbcnx);
        
        if($contentUCHASTKI){
            if(mysqli_num_rows($contentUCHASTKI)>0){
                while($author = mysqli_fetch_array($contentUCHASTKI)){
            	    $documents_markup .= '<li class="promo-slider__slide swiper-slide slide" data-houses><a href="'.$pagelink2.'?obj='.$author['CARDNUM'].'">';
                    if($author["PICTURES"]!='')
                    {
            		   $documents_markup .='<div class="slide-image">';
            	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
            	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
            	           $documents_markup .= '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
            			$documents_markup .='</div>';
            	    }
            
                    $documents_markup .= '<div class="slide__info info">';
            		$documents_markup .= '<h3 class="info__title title--lg title--promo-slide">'.($author['EAREA']>0 ? 'Участок '.$author['EAREA'].'&nbsp;сот.':'').'</h3>';
            // 		$documents_markup .= '<p class="info__subtitle">'.($author['EAREA']>0 ? 'Участок '.$author['EAREA'].'&nbsp;сот.':'').($author['BEDROOMS']>0 ? ' Спален: '.$author['BEDROOMS'] :'').'</p>';
            		$documents_markup .= '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
            		$documents_markup .= '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
            		$documents_markup .= '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                    $documents_markup .= '</div>';
            	   
                    $documents_markup .= '</a></li>';
                }
            }
        }
        
    }
    
    if($filter_type === 'kommercheskaya'){
        $dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
        if (!$dbcnx)exit();
        
        $where=' WHERE COMPID='.$agency.' AND PICCOUNT>0 ';
        $contentCOMMERC = mysqli_query($dbcnx,$sql_text_commerc.$where.' and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$realty_limit);
        
        mysqli_close($dbcnx);
        
        if($contentCOMMERC){
            if(mysqli_num_rows($contentCOMMERC)>0){
                while($author = mysqli_fetch_array($contentCOMMERC)){
                	$documents_markup .= '<li class="promo-slider__slide swiper-slide slide" data-commerc><a href="'.$pagelink4.'?obj='.$author['CARDNUM'].'">';
                    if($author["PICTURES"]!='')
                    {
                		$documents_markup .='<div class="slide-image">';
                	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
                	    if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
                	    $documents_markup .= '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
                	    $documents_markup .='</div>';
                	}
                
                    $documents_markup .= '<div class="slide__info info">';
                	$documents_markup .= '<h3 class="info__title title--lg title--promo-slide">'.$author['OBJ']. ' '.$author['AREA'].'&nbsp;м²</h3>';
                	if($author['EAREA']>0) $documents_markup .='<p clas="mb1">'.$author['EAREA'].' сот.</p>';
                    $documents_markup .= '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
                	$documents_markup .= '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
                    $documents_markup .= '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                    $documents_markup .= '</div>';
                	   
                    $documents_markup .= '</a></li>';
                    }
            }
        }
        
    }
    
    $response = array(
        'posts' => $documents_markup,
        'test' => $test,
        
    );

    wp_send_json_success($response);
    wp_die();
}

add_action('wp_ajax_filter_posts_home_page', 'filter_posts_home_page_handler');
add_action('wp_ajax_nopriv_filter_posts_home_page', 'filter_posts_home_page_handler');
