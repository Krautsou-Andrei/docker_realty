<?php
class Real{
  var $realty_limit       = 5;

  var $sql_tetx = 'select COMPID,CARDNUM,
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
  
  var $sql_tetx2 = 'select COMPID,CARDNUM,
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

  var $sql_tetx4 = 'select COMPID,CARDNUM,
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
  
  function start(){
    global $pagelink1,$pagelink2,$pagelink4,$agency,$imgpath;
    include "file.php";
      $dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
      mysqli_query($dbcnx,"SET NAMES UTF8");
      if (!$dbcnx)exit();

        $where=' WHERE COMPID='.$agency.' AND PICCOUNT>0 ';
      //  $where=' WHERE COMPID='.$agency.' AND PICCOUNT>0 AND SMIMARK like "%[10201]%" ';
       $contentAPARTS = mysqli_query($dbcnx,$this->sql_tetx.$where.' and WHAT=0 and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$this->realty_limit);
       $contentHOUSES = mysqli_query($dbcnx,$this->sql_tetx2.$where.' and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$this->realty_limit);
       $contentCOMMERC = mysqli_query($dbcnx,$this->sql_tetx4.$where.' and VARIANT<>2 and SMIMARK LIKE "%[10000001]%" order by DATEINP DESC LIMIT '.$this->realty_limit);
    mysqli_close($dbcnx);
	$this->createStats($contentAPARTS,$contentHOUSES,$contentCOMMERC);
  }

  // ------------------------------------------------------------------------------
  function createStats($contentAPARTS,$contentHOUSES,$contentCOMMERC){
  global $pagelink1,$pagelink2,$pagelink4,$imgpath;

  $stats=$contentAPARTS;
  
  if($stats)
   if(mysqli_num_rows($stats)>0){
    while($author = mysqli_fetch_array($stats)){
      echo '<li class="promo-slider__slide swiper-slide slide" data-aparts><a href="'.$pagelink1.'?obj='.$author['CARDNUM'].'">';
      if($author["PICTURES"]!='')
       {
		   echo'<div class="slide-image">';
		     
	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
	           echo '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
			echo'</div>';
	   }

       echo '<div class="slide__info info">';
		   echo '<h3 class="info__title title--lg title--promo-slide">'.($author['RPLANID']!=622?$author['ROOMS'].'&nbsp;комнатная квартира':'Студия').'</h3>';
		   echo '<p class="info__subtitle">'.$author['AREA'].'&nbsp;м²'.', '.$author['STAGE'].'&nbsp;этаж</p>';
		   echo '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
		   echo '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
		   echo '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
       echo '</div>';
	   
    echo '</a></li>';
    }
    }

  
  $stats=$contentHOUSES;
  if($stats)
   if(mysqli_num_rows($stats)>0){
    while($author = mysqli_fetch_array($stats)){
	  echo '<li class="promo-slider__slide swiper-slide slide" data-houses><a href="'.$pagelink2.'?obj='.$author['CARDNUM'].'">';
      if($author["PICTURES"]!='')
       {
		   echo'<div class="slide-image">';
	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
	           echo '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
			echo'</div>';
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

  $stats=$contentCOMMERC;
  if($stats)
   if(mysqli_num_rows($stats)>0){
    while($author = mysqli_fetch_array($stats)){
	  echo '<li class="promo-slider__slide swiper-slide slide" data-commerc><a href="'.$pagelink4.'?obj='.$author['CARDNUM'].'">';
      if($author["PICTURES"]!='')
       {
		   echo'<div class="slide-image">';
	           $pics=explode(';',$author["PICTURES"]); $count=0; $str = str_replace("http", "", $pics[0], $count);
	           if ($count>0) $imgpath1=$pics[0]; else $imgpath1=$imgpath.$author[0].'/'.$pics[0];
	           echo '<img src="/wp-content/mlsfiles/timthumb.php?src='.$imgpath1.'&w=380&h=195&zc=1">';
			echo'</div>';
	   }

       echo '<div class="slide__info info">';
		   echo '<h3 class="info__title title--lg title--promo-slide">'.$author['OBJ']. ' '.$author['AREA'].'&nbsp;м²</h3>';
		   if($author['EAREA']>0) echo'<p clas="mb1">'.$author['EAREA'].' сот.</p>';
		   echo '<p class="info__description">'.strip_tags($author['MISC']).'</p>';
		   echo '<p class="info__price"><span>'.number_format(($author['PRICE']/1000),3, ' ', ' ').'</span><span>&nbsp;₽</span></p>';
		   echo '<p class="info__location"><span>'.($author['STREET'].', ').($author['RAION']!=''?$author['RAION'].', ':'').($author['CITY']=='Область'?'':$author['CITY']).'</span></p>';
       echo '</div>';
	   
    echo '</a></li>';
    }
    }
  
  }  //function createStats


 }//class

$mls = new Real;
$mls->start();
/*
echo '

<script>
    $("li[data-aparts-show]").on("click", function(e) {
      $("li[data-houses]").addClass("d-none"); 
	  $("li[data-nb]").addClass("d-none");
	  $("li[data-commerc]").addClass("d-none"); 	 
	  $("li[data-land]").addClass("d-none");  
      e.preventDefault();
    });
</script>

';*/