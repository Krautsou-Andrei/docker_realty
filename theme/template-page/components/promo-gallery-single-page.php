<?php 

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
    $realty_limit = 5;
    
    
    
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
    DATECOR,
    MISC';
    
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
    MISC';
    
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
    ';  
	
	$sql_text_add_aparts  = 'SELECT WHAT,VARIANT,CITYID,RAIONID,AAREA,ROOMS,PRICE,NEWBUILD'; 
	$sql_text_add_houses  = 'SELECT WHAT,VARIANT,CITYID,RAIONID,AAREA,EAREA,PRICE'; 
	$sql_text_add_commerc = 'SELECT OBJID,VARIANT,CITYID,RAIONID,SQUEAR,PRICE'; 

    $dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
    if (!$dbcnx) exit();
		
	$url = $_SERVER['REQUEST_URI'];
	$where = ' WHERE COMPID='.$agency.' AND PICCOUNT>0 ';
		
	if (strpos($url,$pagelink1) !== false) {
		$sql_add = mysqli_query($dbcnx,$sql_text_add_aparts.' FROM APARTS WHERE COMPID='.$agency.' and CARDNUM='.$_GET['obj']);
		if($sql_add)
			$data=mysqli_fetch_array($sql_add);
		$contentAPARTS = mysqli_query($dbcnx,$sql_text_aparts. ' FROM APARTS '.$where.' 
		and WHAT='.$data['WHAT'].' 
		and NEWBUILD='.$data['NEWBUILD'].' 
		and VARIANT='.$data['VARIANT'].' 
		and CITYID='.$data['CITYID'].' 
		and RAIONID='.$data['RAIONID'].' 
		and ROOMS='.$data['ROOMS'].' 
		and PRICE<('.$data['PRICE'].'*1.2) and PRICE>('.$data['PRICE'].'*0.8)  
		and AAREA<('.$data['AAREA'].'*1.2) and AAREA>('.$data['AAREA'].'*0.8) 
		and CARDNUM<>'.$_GET['obj'].'
		order by DATEINP DESC LIMIT '.$realty_limit);
	}else
		
	if (strpos($url,$pagelink2) !== false) {
		$sql_add = mysqli_query($dbcnx,$sql_text_add_houses.' FROM HOUSES WHERE COMPID='.$agency.' and CARDNUM='.$_GET['obj']);
		if($sql_add)
			$data=mysqli_fetch_array($sql_add);
		$contentHOUSES = mysqli_query($dbcnx,$sql_text_houses. ' FROM HOUSES '.$where.' 
		and WHAT='.$data['WHAT'].' 
		and VARIANT='.$data['VARIANT'].' 
		and CITYID='.$data['CITYID'].' 
		and RAIONID='.$data['RAIONID'].' 
		and PRICE<('.$data['PRICE'].'*1.2) and PRICE>('.$data['PRICE'].'*0.8)  
		and AAREA<('.$data['AAREA'].'*1.2) and AAREA>('.$data['AAREA'].'*0.8) 
		and CARDNUM<>'.$_GET['obj'].'
		order by DATEINP DESC LIMIT '.$realty_limit);
	}else
		
	if (strpos($url,$pagelink2_1) !== false) {
		$sql_add = mysqli_query($dbcnx,$sql_text_add_houses.' FROM HOUSES WHERE COMPID='.$agency.' and CARDNUM='.$_GET['obj']);
		if($sql_add)
			$data=mysqli_fetch_array($sql_add);
		$contentHOUSES = mysqli_query($dbcnx,$sql_text_houses. ' FROM HOUSES '.$where.' 
		and WHAT='.$data['WHAT'].' 
		and VARIANT='.$data['VARIANT'].' 
		and CITYID='.$data['CITYID'].' 
		and RAIONID='.$data['RAIONID'].' 
		and PRICE<('.$data['PRICE'].'*1.2) and PRICE>('.$data['PRICE'].'*0.8)  
		and EAREA<('.$data['EAREA'].'*1.2) and EAREA>('.$data['EAREA'].'*0.8) 
		and CARDNUM<>'.$_GET['obj'].'
		order by DATEINP DESC LIMIT '.$realty_limit);
	}else

	if (strpos($url,$pagelink4) !== false) {
		$sql_add = mysqli_query($dbcnx,$sql_text_add_commerc.' FROM COMMERC WHERE COMPID='.$agency.' and CARDNUM='.$_GET['obj']);
		if($sql_add)
			$data=mysqli_fetch_array($sql_add);
		$contentCOMMERC = mysqli_query($dbcnx,$sql_text_commerc. ' FROM COMMERC '.$where.' 
		and OBJID='.$data['OBJID'].' 
		and VARIANT='.$data['VARIANT'].' 
		and CITYID='.$data['CITYID'].' 
		and RAIONID='.$data['RAIONID'].' 
		and SQUEAR<('.$data['SQUEAR'].'*1.2) and SQUEAR>('.$data['SQUEAR'].'*0.8)  
		and CARDNUM<>'.$_GET['obj'].'
		order by DATEINP DESC LIMIT '.$realty_limit);
	}
		
        mysqli_close($dbcnx);
        
        if(!empty($contentAPARTS))
           if(mysqli_num_rows($contentAPARTS)>0){
            while($author = mysqli_fetch_array($contentAPARTS)){
                
                echo '<li class="promo-slider__slide swiper-slide slide" data-filter-visited><a href="'.$pagelink1.'?obj='.$author['CARDNUM'].'">';
                    if($author["PICTURES"]!='')
                      {
                		   echo '<div class="slide-image">';
                		     
                	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
                	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
                	          echo '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
                			echo '</div>';
                	   }
                
                    echo '<div class="slide__info info">';
                       echo '<h3 class="info__title title--lg title--promo-slide">'.($author['RPLANID']!=622?$author['ROOMS'].'&nbsp;комнатная квартира':'Студия').'</h3>';
                       echo '<p class="info__subtitle">'.$author['AREA'].'&nbsp;м²'.', '.$author['STAGE'].'&nbsp;этаж</p>';
                       echo '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
                    //   echo '<p>дата '.$author['DATECOR'].'</p>';
                       echo '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
                       echo '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                    echo '</div>';
                	   
                    echo '</a></li>';
            }
        }

  

        if(!empty($contentHOUSES))
            if(mysqli_num_rows($contentHOUSES)>0){
                while($author = mysqli_fetch_array($contentHOUSES)){
                    echo '<li class="promo-slider__slide swiper-slide slide" data-filter-visited><a href="'.$pagelink2.'?obj='.$author['CARDNUM'].'">';
                        if($author["PICTURES"]!='')
                          {
                    		  echo '<div class="slide-image">';
                    	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
                    	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
                    	           echo '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
                    			echo '</div>';
                    	   }
                    
                            echo '<div class="slide__info info">';
                               echo '<h3 class="info__title title--lg title--promo-slide">'.$author['htype']. ' '.$author['AREA'].'&nbsp;м²</h3>';
                               echo '<p class="info__subtitle">'.($author['EAREA']>0 ? 'Участок '.$author['EAREA'].'&nbsp;сот.':'').($author['BEDROOMS']>0 ? ' Спален: '.$author['BEDROOMS'] :'').'</p>';
                               echo '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
                               echo '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
                               echo '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                            echo '</div>';
                    	   
                        echo '</a></li>';
                }
            }

  
        if(!empty($contentCOMMERC))
            if(mysqli_num_rows($contentCOMMERC)>0){
                while($author = mysqli_fetch_array($contentCOMMERC)){
                    echo '<li class="promo-slider__slide swiper-slide slide" data-filter-visited><a href="'.$pagelink4.'?obj='.$author['CARDNUM'].'">';
                        if($author["PICTURES"]!='')
                          {
                    		   echo '<div class="slide-image">';
                    	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
                    	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
                    	           echo '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
                    			echo '</div>';
                    	   }
                    
                        echo '<div class="slide__info info">';
                           echo '<h3 class="info__title title--lg title--promo-slide">'.$author['OBJ']. ' '.$author['AREA'].'&nbsp;м²</h3>';
                           if($author['EAREA']>0) echo '<p clas="mb1">'.$author['EAREA'].' сот.</p>';
                           echo '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
                           echo '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
                           echo '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
                       echo '</div>';
                    	   
                         echo '</a></li>';
                }
            }
    // $args = array(
    //     'post_type'      => 'post',
    //     'posts_per_page' => -1,
    //     'meta_query'     => array(
    //         array(
    //             'key' => 'product_compare_content',
    //             'compare' => 'EXISTS',
    //         ),
    //     ),
    // );
    
    
    
    // $query = new WP_Query($args);
    
    // if ($query->have_posts()) {
    //   while ($query->have_posts()) {
    //           $query->the_post();

    //           $product_gallery = carbon_get_post_meta(get_the_ID(), 'product-gallery');
    //           $product_region = carbon_get_post_meta(get_the_ID(), 'product-region');
    //           $product_city = carbon_get_post_meta(get_the_ID(), 'product-city');
    //           $product_address = carbon_get_post_meta(get_the_ID(), 'product-address');
    //           $product_description = carbon_get_post_meta(get_the_ID(), 'product-description');
    //           $product_price =  carbon_get_post_meta(get_the_ID(), 'product-price');
    //           $product_area = carbon_get_post_meta(get_the_ID(), 'product-area');
    //           $product_filter_compare = carbon_get_post_meta(get_the_ID(), 'product_compare_content');
    //           $product_filter_more = carbon_get_post_meta(get_the_ID(), 'product_more_content');

    //           if ($product_area > 0) {
    //             $product_price_meter = number_format(round(floatval($product_price) / floatval($product_area), 2), 0, '.', ' ');
    //           }
    //           $product_price_format = number_format(round(floatval(carbon_get_post_meta(get_the_ID(), 'product-price'))), 0, '.', ' ');


    //           echo '<li class="promo-slider__slide swiper-slide slide" '.(!$product_filter_more && !$product_filter_compar ? 'data-filter-new' : '').' '.($product_filter_more ? 'data-filter-more' : '').'  '.($product_filter_compare ? 'data-filter-compare' : '').'>';
    //           if (!empty($product_gallery[0])) {
    //             $image_url = wp_get_attachment_image_src($product_gallery[0], 'full');
    //             $post_permalink = get_permalink(get_the_ID());
    //             echo '<a href="' . esc_url($post_permalink) . '">
    //                         <div class="slide-image">
    //                           <img src="' . $image_url[0] . '" alt="" width="380" height="195">
    //                         </div>';
    //           }
    //           echo '
    //                         <div class="slide__info info">
    //                           <h3 class="info__title title--lg title--promo-slide">' . $product_city . '</h3>
    //                           <p class="info__description">' . $product_description . '</p>';

    //           if (!empty($product_price_meter)) {
    //             echo '<p class="info__price">от ' . $product_price_meter . ' ₽ за м²</p>';
    //           } else {
    //             echo '<p class="info__price">' . $product_price_format . ' ₽</p>';
    //           }
    //           echo  '<p class="info__location"><span>' . $product_region . '</span><span>, ' . $product_city . ' ' . $product_address . '</span></p>
    //                       </div>
    //                       </a>
    //                       </li>';
    //           }
    //         wp_reset_postdata();
    // } 
?>