<?

/**
 * MLS CITY
 * @author mls-city@mail.ru
 * @copyright (c) MLS CITY
 * @license http://mls-city.ru
 */
class MLSReal
{

  var $cardSale    = 'Продажа квартиры';
  var $cardRent    = 'Аренда квартиры';
  var $mlspage     = 1;
  var $gets        = '';
  var $order       = 1;
  var $realty_type  = 'stats';
  var $realty_limit = 21;
  var $html_navdiv  = 'pagination';
  var $html_navul  = 'pagination';
  var $nav_start    = '1';
  var $nav_back     = '<<';
  var $nav_next     = '>>';
  var $nav_end      = '';
  var $tetx_objects = 'Объектов';
  var $rc;
  var $script_arr   = '';


  var $sql_tetx = 'select COMPID,CARDNUM,
  PICCOUNT,
  COORDN,
  CONCAT((SELECT VOCSTREET.NAME FROM VOCSTREET WHERE VOCSTREET.ID=STREETID),if(HAAP<>" ",CONCAT(", дом&nbsp;",HAAP), "")) AS STREET,
  ROOMS,
  WHAT,
  (SELECT VOCRAION.NAME FROM VOCRAION WHERE VOCRAION.ID=RAIONID) AS RAION,
  concat(STAGE, "/", HSTAGE) AS STAGE,
  concat(TRUNCATE(AAREA,0), "/", TRUNCATE(LAREA,0), "/", TRUNCATE(KAREA,0)) AS AREA,
  VARIANT,
  PICTURES,
  round(PRICE) AS PRICE,
  (SELECT VOCCITY.NAME FROM VOCCITY WHERE VOCCITY.ID=APARTS.CITYID) AS CITY, 
  VTOUR,
  RPLANID,
  MISC,
  (select PHONE from VOCAGENTS where ID=AGENTID and VOCAGENTS.COMPID=APARTS.COMPID limit 1) as PHONE ,
  (select PHOTO from VOCAGENTS where ID=AGENTID and VOCAGENTS.COMPID=APARTS.COMPID limit 1) as PHOTO ,
  round(SQMPRICE) AS SQMPRICE,
  DATECOR
  from APARTS';

  var $sql_card_tetx = 'select COMPID,CARDNUM,PICTURES,PICNAMES,
   (SELECT VOCCITY.NAME FROM VOCCITY WHERE VOCCITY.ID=APARTS.CITYID) as Город,
   (SELECT VOCRAION.NAME FROM VOCRAION WHERE VOCRAION.ID=RAIONID) as Район,
   ROOMS as Комнат,
   (SELECT VOCALLDATA.NAME FROM VOCALLDATA WHERE VOCALLDATA.ID=HTYPEID AND razdelid=4) as "Тип дома",
   STAGE,
   HSTAGE,
   (select VOCALLDATA.NAME from VOCALLDATA where VOCALLDATA.RAZDELID=8 AND VOCALLDATA.ID = WMATERID) as "Материал стен",
   TRUNCATE(AAREA,0) as AAREA,
   TRUNCATE(LAREA,0) as LAREA,
   TRUNCATE(KAREA,0) as KAREA,
   MISC as "Примечание",
   PRICE as "Цена общая",
   CONCAT((SELECT VOCSTREET.NAME FROM VOCSTREET WHERE VOCSTREET.ID=STREETID),if(HAAP<>" ",CONCAT(", дом&nbsp;",HAAP), "")) as Улица,
   COORDN,
   (select PHONE from VOCAGENTS where ID=AGENTID and VOCAGENTS.COMPID=APARTS.COMPID limit 1) as PHONE ,
   (select CONCAT(NAME, " ",SIRNAME) from VOCAGENTS where ID=AGENTID and VOCAGENTS.COMPID=APARTS.COMPID limit 1) as Агент,
   (select PHOTO from VOCAGENTS where ID=AGENTID and VOCAGENTS.COMPID=APARTS.COMPID limit 1) as AGPHOTO,
   (select SGROUP from VOCAGENTS where ID=AGENTID and VOCAGENTS.COMPID=APARTS.COMPID limit 1) as SGROUP,
   (select NAME from VOCNB where ID=GKID) as GK,
   (select SUBNAME from VOCNB where ID=GKID) as GKD,
   VTOUR,
   SQMPRICE,
   VARIANT,
   RPLANID,
   DATECOR,
   if(((CHECKS1&2)>0),"есть","нет") as Лифт,
   PICCOUNT
   from APARTS';

  function start()
  {
    include "file.php";
    $this->imgpath     = $imgpath;
    $this->oblast      = $oblast;
    $this->yandexKey    = $yandexKey;
    $this->agency      = $agency;
    $this->obl      = $obl;
    $this->city      = $city;
    $this->pagelink1  = $pagelink1;
    $this->pagelink1r  = $pagelink1r;
    $this->pagelink1nb  = $pagelink1nb;
    $this->pagelink2  = $pagelink2;
    $this->pagelink2_1  = $pagelink2_1;
    $this->pagelink4  = $pagelink4;
    $this->scriptpath  = $scriptpath;
    $this->avatarpath   = $avatarpath;
    $this->filter     = $filter;
    $this->obl_coord   = $obl_coord;
    echo '<link type="text/css" media="screen" rel="stylesheet" href="' . $this->scriptpath . 'css/mls.css?9"/>';
    if ($this->yandexKey != '') echo '<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU&' . $this->yandexKey . '" type="text/javascript"></script>';
    if (isset($_GET['mlspage'])) $this->mlspage = intval($_GET['mlspage']);
    if (isset($_GET['type'])) $this->realty_type = $this->getParam($_GET['type']);

    $this->city = 0;
    $this->stype = 0;
    if (isset($_GET['city'])) $this->city = intval($_GET['city']);
    if (isset($_GET['stage'])) $this->stage = intval($_GET['stage']);
    if ($this->mlspage == 0) $this->mlspage = 1;

    $gts = $this->gets = '';
    $this->dbcnx = mysqli_connect($dblocation, $dbuser, $dbpasswd, $dbname);

    if (!$this->dbcnx) {
      echo "<p>В настоящий момент сервер базы данных не доступен, поэтому корректное отображение страницы невозможно.</p>";
      exit();
    }

    if (isset($_GET['obj'])) {
      $this->realty_type = 'card';
      $content = mysqli_query($this->dbcnx, $this->sql_card_tetx . ' where CARDNUM=' . intval($_GET['obj']) . ' AND COMPID=' . $this->agency);
    }

    if ($this->realty_type == 'stats') {

      if (isset($_POST['UPD'])) {

        if (isset($_POST['mlspage'])) $this->mlspage = intval($_POST['mlspage']);

        if (isset($_POST['mlsorder'])) {
          $this->mlsorder = $_POST['mlsorder'];
          $this->order = explode('&', $_POST['mlsorder'])[0];
          $this->direction = explode('&', $_POST['mlsorder'])[1];
        }

        if (isset($_POST['city'])) {
          $this->city = intval($_POST['city']);
          $this->gets .= '&city=' . $this->city;
        };

        $whereS = '';
        if (isset($_POST['raions'])) {
          foreach ($_POST['raions'] as $checkbox) if (isset($checkbox)) $whereS .= $checkbox . ',';
          $whereS = substr($whereS, 0, strlen($whereS) - 1);
        }

        $whereM = '';
        if (isset($_POST['mater'])) {
          foreach ($_POST['mater'] as $checkbox) if (isset($checkbox)) $whereM .= intval($checkbox) . ',';
          $whereM = substr($whereM, 0, strlen($whereM) - 1);
        }

        $whereR = '';
        if (isset($_POST['rooms'])) {
          foreach ($_POST['rooms'] as $checkbox) if (isset($checkbox)) $whereR .= intval($checkbox) . ',';
          $whereR = substr($whereR, 0, strlen($whereR) - 1);
        }

        if (isset($_POST['stage'])) {
          $this->stage = intval($_POST['stage']);
          $this->gets .= '&stage=' . $this->stage;
        }

        if (isset($_POST['stype'])) {
          foreach ($_POST['stype'] as $checkbox) if (isset($checkbox))  $this->stype = intval($checkbox);
          $this->gets .= '&stype[]=' . $this->stype;
        }


        if (isset($_POST['area_ot'])) {
          $this->area_ot = floatval($_POST['area_ot']);
          $this->gets .= '&area_ot=' . $this->area_ot;
        }
        if (isset($_POST['area_do'])) {
          $this->area_do = floatval($_POST['area_do']);
          $this->gets .= '&area_do=' . $this->area_do;
        }
        if (isset($_POST['price_ot'])) {
          $this->price_ot = floatval($_POST['price_ot']);
          $this->gets .= '&price_ot=' . $this->price_ot;
        }
        if (isset($_POST['price_do'])) {
          $this->price_do = floatval($_POST['price_do']);
          $this->gets .= '&price_do=' . $this->price_do;
        }

        if ($whereS != '') $this->gets .= '&raions[]=' . $whereS;
        if ($whereM != '') $this->gets .= '&mater[]=' . $whereM;
        if ($whereR != '') $this->gets .= '&rooms[]=' . $whereR;
      } else {

        $whereS = '';
        $whereM = '';
        $whereR = '';
        if ($whereS == '') {
          if (isset($_GET['raions'])) foreach (explode(',', $_GET['raions'][0]) as $rn) if (isset($rn)) $whereS .= intval($rn) . ',';
          $whereS = substr($whereS, 0, strlen($whereS) - 1);
          if ($whereS != '') $this->gets .= '&raions[]=' . $whereS;
        } else {
          if ($whereS != '') $this->gets .= '&raions[]=' . $whereS;
        }

        if (isset($_GET['rooms']) && $_GET['rooms'][0] == 'Студия') $_GET['rooms'][0] = '9999';
        if ($whereR == '') {
          if (isset($_GET['rooms'])) foreach ($_GET['rooms'] as $rn) if (isset($rn)) $whereR .= intval($rn) . ',';
          $whereR = substr($whereR, 0, strlen($whereR) - 1);
          if ($whereR != '') $this->gets .= '&rooms[]=' . $whereR;
        } else {
          if ($whereR != '') $this->gets .= '&rooms[]=' . $whereR;
        }

        if ($whereM == '') {
          if (isset($_GET['mater'])) foreach (explode(',', $_GET['mater'][0]) as $rn) if (isset($rn)) $whereM .= intval($rn) . ',';
          $whereM = substr($whereM, 0, strlen($whereM) - 1);
          if ($whereM != '') $this->gets .= '&mater[]=' . $whereM;
        } else {
          if ($whereM != '') $this->gets .= '&mater[]=' . $whereM;
        }

        if (isset($_GET['stype'])) {
          foreach ($_GET['stype'] as $checkbox) if (isset($checkbox))  $this->stype = intval($checkbox);
          $this->gets .= '&stype[]=' . $this->stype;
        }

        if (isset($_GET['area_ot'])) {
          $this->area_ot = floatval($_GET['area_ot']);
          $this->gets .= '&area_ot=' . $this->area_ot;
        }
        if (isset($_GET['area_do'])) {
          $this->area_do = floatval($_GET['area_do']);
          $this->gets .= '&area_do=' . $this->area_do;
        }
        if (isset($_GET['price_ot'])) {
          $this->price_ot = floatval($_GET['price_ot']);
          $this->gets .= '&price_ot=' . $this->price_ot;
        }
        if (isset($_GET['price_do'])) {
          $this->price_do = floatval($_GET['price_do']);
          $this->gets .= '&price_do=' . $this->price_do;
        }


        $this->gets .= '&city=' . $this->city;
      }


      $whereOD = '';
      if (isset($this->area_ot) && $this->area_ot != '') $whereOD .= ' AAREA>=' . $this->area_ot . ' AND ';
      if (isset($this->area_do) && $this->area_do != '') $whereOD .= ' AAREA<=' . $this->area_do . ' AND ';

      if (isset($this->price_ot) && $this->price_ot != '') $whereOD .= ' PRICE>=' . $this->price_ot . ' AND ';
      if (isset($this->price_do) && $this->price_do != '') $whereOD .= ' PRICE<=' . $this->price_do . ' AND ';

      if (isset($this->stype) && $this->stype != '') {
        if ($this->stype == 1) $whereOD .= ' VARIANT=2 AND ';
        else  $whereOD .= ' VARIANT<>2 AND ';
      } else $whereOD .= ' VARIANT<>2 AND ';


      $where = '';
      if ($whereS != '' || $whereR != '' || $whereM != '' || $this->stage != '') {
        if ($whereS != '') $where .= 'RAIONID in (' . $whereS . ') AND ';
        if ($whereM != '') {
          foreach (explode(',', $whereM) as $rn) if (intval($rn) == 76) $where .= 'NOT WMATERID in (80,83,510,82,78) AND ';
          else $where .= 'WMATERID in (' . $whereM . ') AND ';
        }
        if ($whereR != '') {
          if (strpos($whereR, "9999") !== false) $where .= '(ROOMS in (' . $whereR . ') or RPLANID=622) AND ';
          else
               if (strpos($whereR, "4") !== false) $where .= '(ROOMS in (' . $whereR . ') or ROOMS>4) AND ';
          else $where .= 'ROOMS in (' . $whereR . ') AND ';
        };
        if ($this->stage != '') {
          if ($this->stage == 1)  $where .= 'STAGE=1 AND ';  //первый
          if ($this->stage == 2)  $where .= 'STAGE=HSTAGE AND ';  //последний
          if ($this->stage == 3)  $where .= 'STAGE<>1 AND ';  //не первый
          if ($this->stage == 4)  $where .= 'STAGE<>HSTAGE AND ';  //не последний
          if ($this->stage == 5)  $where .= 'STAGE<>1 AND STAGE<>HSTAGE AND ';  //не крайние
          if ($this->stage == 6)  $where .= 'STAGE<=5 AND ';  //Ниже 5-го
          if ($this->stage == 7)  $where .= 'STAGE>5 AND ';  //Выше 5-го
          if ($this->stage == 8)  $where .= 'STAGE>10 AND ';  //Выше 10-го
        }
        $where .= ' INET=1 ';
        if ($this->city > 0) $where .= ' AND CITYID=' . $this->city;
        $where = ' WHERE ' . $whereOD . ' ' . $where;
      } else {
        $where = ' WHERE ' . $whereOD . ' INET=1 ';
        if ($this->city > 0) $where .= ' AND CITYID=' . $this->city;
      };

      if (isset($_POST['RST'])) {
        $whereS = '';
        $whereM = '';
        $whereR = '';
        $this->stage = '';
        $this->gets = '';
        $this->order = 1; //' DATECOR DESC';
        $this->direction = '';
        $this->mlspage = 1;
        $where = ' WHERE INET=1 '; //' AND CITYID=1';
      };

      $where .= ' AND NEWBUILD=0 AND WHAT<>1 ';

      if ($this->order == 1) $ordering = ' EXLUSIVE=3 DESC,PICCOUNT>0 DESC,DATECOR DESC';
      else $ordering = $this->order;

      $where .= " AND APARTS.COMPID = " . $this->agency;
      $hav = '';
      $this->rc = mysqli_fetch_row(mysqli_query($this->dbcnx, 'select count(*) from APARTS' . $where));
      $this->rc = $this->rc[0];
      $content = mysqli_query($this->dbcnx, $this->sql_tetx . $where . $hav . ' order by ' . $ordering . ' ' . $this->direction . ' LIMIT ' . $this->realty_limit * ($this->mlspage - 1) . ',' . $this->realty_limit);
      $contentMAP = mysqli_query($this->dbcnx, $this->sql_tetx . $where . $hav);


      $sql_city = mysqli_query($this->dbcnx, 'select ID,NAME from VOCCITY where CITYID=' . $this->obl);
      $sql_cityM = mysqli_query($this->dbcnx, 'select MAX(ID),MIN(ID) from VOCCITY where CITYID=' . $this->obl);
      $sql_raion = mysqli_query($this->dbcnx, 'select ID,NAME,CITYID from VOCRAION');

      if ($this->filter == 1) {
        $sql_price = mysqli_query($this->dbcnx, 'select MIN(PRICE),MAX(PRICE) from APARTS where ' . (isset($this->stype) && $this->stype == 1 ? 'VARIANT=2' : 'VARIANT<>2') . ' and NEWBUILD=0 and WHAT=0 and COMPID=' . $this->agency);
        $dataPrice = mysqli_fetch_row($sql_price);
        $sql_area = mysqli_query($this->dbcnx, 'select MIN(AAREA),MAX(AAREA) from APARTS where ' . (isset($this->stype) && $this->stype == 1 ? 'VARIANT=2' : 'VARIANT<>2') . ' and NEWBUILD=0 and WHAT=0 and COMPID=' . $this->agency);
        $dataArea = mysqli_fetch_row($sql_area);
      } else {
        $dataPrice[0] = '';
        $dataPrice[1] = '';
        $dataArea[0] = '';
        $dataArea[1] = '';
      }

      $m_c = explode(',', $whereS);
      $dataM = mysqli_fetch_row($sql_cityM);
      for ($i = intval($dataM[1]); $i <= intval($dataM[0]); $i++) {
        $this->script_arr .= 'csel_rn[' . $i . ']=[';
        mysqli_data_seek($sql_raion, 0);
        for ($j = 0; $j < mysqli_num_rows($sql_raion); $j++) {
          $dataR = mysqli_fetch_row($sql_raion);
          if ($dataR[2] == $i || $dataR[2] == -1) $this->script_arr .= '{id:' . $dataR[0] . ',text:\'' . $dataR[1] . '\'},';
        }
        $this->script_arr .= '];';
      }
    } //if stats
    switch ($this->realty_type) {
      case 'stats':
        $this->echofilter($sql_city, $sql_raion, $whereS, $whereM, $whereR, $content, $contentMAP, round($dataPrice[0]), round($dataPrice[1]), round($dataArea[0]), round($dataArea[1]));
        $this->createStats($content, $this->rc);
        break;
      case 'card':
        $this->showObject($content);
        break;
    }
    mysqli_close($this->dbcnx);
  }


  function echofilter($sql_city, $sql_raion, $whereS, $whereM, $whereR, $stats, $statsMAP, $dataPriceMIN, $dataPriceMAX, $dataAreaMIN, $dataAreaMAX)
  {
    $m_c = explode(',', $whereR);
    $echo_city = '';
    for ($i = 0; $i < mysqli_num_rows($sql_city); $i++) {
      $data = mysqli_fetch_row($sql_city);
      if ($data[0] == 2306 || $data[0] == 2301) {
        $echo_city .= '
          <option value=' . $data[0] . ' ' . ($this->city == $data[0] ? 'selected' : '') . '>' . $data[1] . '</option>';
      }
    }

    echo '
	<script type="text/javascript" src="' . $this->scriptpath . 'js/selectize.min.js"></script>
  	<link rel="stylesheet" href="' . $this->scriptpath . 'css/selectize.css">

    <div class="mb1 main-catalog__filter">
      <section class="filter-catalog">
        <div class="filter-catalog__container">
		
       <div class="filter-catalog-mobile mb1">
            <div class="filter-catalog-mobile__button">
              <a class="button-catalog-filter" onclick="window.history.go(-1); return false;">
                <img src="/wp-content/themes/realty/assets/images/back.svg" alt="">
                <span>Назад </span>
              </a>
            </div>
            <div class="filter-catalog-mobile__button">
              <button class="button-catalog-filter">
                <img src="/wp-content/themes/realty/assets/images/filter.svg">
                <span data-type="popup-filter">Фильтры </span>
              </button>
            </div>
          </div>


<form method="post" id="realty_filter_form" class="hidden-xs">
<div class="row realty_filter search_filter"><div class="col-xs-12">

 	 <div class="op10 col-lg-2 col-md-4 col-sm-6 col-xs-12">

					<select class="form-control" name="razdel" onchange="javascript:location=this.value;">
                        <option value="' . $this->pagelink1 . '" selected>Квартиры</option>
                        <option value="' . $this->pagelink1r . '">Комнаты</option>
                        <option value="' . $this->pagelink1nb . '">Новостройки</option>
                        <option value="' . $this->pagelink2 . '">Дома</option>
                        <option value="' . $this->pagelink2_1 . '">Участки</option>
                        <option value="' . $this->pagelink4 . '">Коммерческая</option>
                    </select>
	         </div>
   <div class="op10 col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <select class="form-control" name="city" onchange="load_raion(this.value,\'raions\')">';
    echo '<option ';
    if ($this->city == 0) echo ' selected value=0>Любой город</option>';
    echo $echo_city;
    echo '</select>
      </div>


	        <div class="op10 col-lg-3 col-md-4 col-sm-6 col-xs-12">
		         <select multiple id="raions" class="form-control" name="raions[]" class="form-control" tabindex="4" data-placeholder="Район">
		         </select>
	         </div>
          <div class="op10 col-lg-2 col-md-4 col-sm-6 col-xs-12">
			<select id="mater" multiple name="mater[]" class="form-control" data-placeholder="Материал стен"></select>
           </div>


           <div class="op10 col-lg-2 col-md-4 col-sm-6 col-xs-12">
			<select name="stage" class="form-control">
		          <option value="" disabled selected>Этаж</option>
		          <option value="">Любой этаж</option>
		          <option value="1" ' . ($this->stage == 1 ? 'selected' : '') . '>Первый</option>
		          <option value="2" ' . ($this->stage == 2 ? 'selected' : '') . '>Последний</option>
		          <option value="3" ' . ($this->stage == 3 ? 'selected' : '') . '>Не первый</option>
		          <option value="4" ' . ($this->stage == 4 ? 'selected' : '') . '>Не последний</option>
		          <option value="5" ' . ($this->stage == 5 ? 'selected' : '') . '>Не крайние</option>
		          <option value="6" ' . ($this->stage == 6 ? 'selected' : '') . '>Ниже 5-го</option>
		          <option value="7" ' . ($this->stage == 7 ? 'selected' : '') . '>Выше 5-го</option>
		          <option value="8" ' . ($this->stage == 8 ? 'selected' : '') . '>Выше 10-го</option>
		         </select>
           </div>



	<link rel="stylesheet" href="' . $this->scriptpath . 'css/jquery-ui.min.css">
    <script src="' . $this->scriptpath . 'js/jquery-ui.min.js"></script>
    <script src="' . $this->scriptpath . 'js/jquery.ui.touch-punch.min.js"></script>

    <div class="ui-slider-title op10 col-lg-3 col-md-4 col-sm-6 col-xs-12 pl1 pr1 mt7">
       <strong>Площадь, м²</strong><span id="mlsArea"> от ' . (isset($this->area_ot) && $this->area_ot > 0 ? $this->area_ot : $dataAreaMIN) . ' до ' . (isset($this->area_do) && $this->area_do > 0 ? $this->area_do : $dataAreaMAX) . '</span><br />
			<input type="hidden" id="area_ot" name="area_ot" value="' . (isset($this->area_ot) && $this->area_ot > 0 ? $this->area_ot : '') . '"/>
			<input type="hidden" id="area_do" name="area_do" value="' . (isset($this->area_do) && $this->area_do > 0 ? $this->area_do : '') . '"/>
			<div class="col-xs-12 mt1" id="slider-mlsArea"></div>
    </div>

	    <div class="ui-slider-title op10 rooms_f col-lg-3 col-md-4 col-sm-6 col-xs-12 mt7">
	        <b>Комнат</b>
	        <div class="rooms-wrapper">
    			<input type="checkbox" name="rooms[]" id="rooms9999" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 9999) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="9999"><label for="rooms9999"><span class="btn btn-default">Студия</span></label>
    			<input type="checkbox" name="rooms[]" id="rooms1" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 1) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="1"><label for="rooms1"><span class="btn btn-default">1</span></label>
    			<input type="checkbox" name="rooms[]" id="rooms2" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 2) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="2"><label for="rooms2"><span class="btn btn-default">2</span></label>
    			<input type="checkbox" name="rooms[]" id="rooms3" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 3) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="3"><label for="rooms3"><span class="btn btn-default">3</span></label>
    			<input type="checkbox" name="rooms[]" id="rooms4" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 4) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="4"><label for="rooms4"><span class="btn btn-default">4+</span></label>
	        </div>
	    </div>

	<div class="ui-slider-title op10 col-lg-3 col-md-4 col-sm-6 col-xs-12 mt7">
		<strong>Цена, ₽</strong><span id="mlsPrice"> от ' . number_format((isset($this->price_ot) && $this->price_ot > 0 ? $this->price_ot : $dataPriceMIN) / 1000, 3, ' ', ' ') . ' до ' . number_format((isset($this->price_do) && $this->price_do > 0 ? $this->price_do : $dataPriceMAX) / 1000, 3, ' ', ' ') . '</span><br />
			<input type="hidden" id="price_ot" name="price_ot" value="' . (isset($this->price_ot) && $this->price_ot > 0 ? $this->price_ot : '') . '">
			<input type="hidden" id="price_do" name="price_do" value="' . (isset($this->price_do) && $this->price_do > 0 ? $this->price_do : '') . '">
			<div class="col-xs-12 mt1" id="slider-mlsPrice"></div>
	</div>
	<script>
		function numberWithSpaces(x) {
		  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
		}
		function clearslide(){
		$( "#price_ot" ).val(0);
		$( "#price_do" ).val(0);
		$( "#area_ot" ).val(0);
		$( "#area_do" ).val(0);
	}
	  $( function() {
		$( "#slider-mlsPrice" ).slider({
		  range: true,
		  min: ' . $dataPriceMIN . ',
		  max: ' . $dataPriceMAX . ',
		  step: 1,
		  values: [ ' . (isset($this->price_ot) && $this->price_ot > 0 ? $this->price_ot : $dataPriceMIN) . ', ' . (isset($this->price_do) && $this->price_do > 0 ? $this->price_do : $dataPriceMAX) . ' ],
		  slide: function( event, ui ) {
			$( "#price_ot" ).val(ui.values[0]);
			$( "#price_do" ).val(ui.values[1]);
			$( "#mlsPrice" ).html( " от " + numberWithSpaces(ui.values[ 0 ]) + " до " + numberWithSpaces(ui.values[ 1 ])  );
		  }
		});
		//$( "#price_ot" ).val( $( "#slider-mlsPrice" ).slider( "values", 0 )  );
		//$( "#price_do" ).val( $( "#slider-mlsPrice" ).slider( "values", 1 )  );
	  } );

	  $( function() {
		$( "#slider-mlsArea" ).slider({
		  range: true,
		  min: ' . $dataAreaMIN . ',
		  max: ' . $dataAreaMAX . ',
		  step: 1,
		  values: [ ' . (isset($this->area_ot) && $this->area_ot > 0 ? $this->area_ot : $dataAreaMIN) . ', ' . (isset($this->area_do) && $this->area_do > 0 ? $this->area_do : $dataAreaMAX) . ' ],
		  slide: function( event, ui ) {
			$( "#area_ot" ).val(ui.values[0]);
			$( "#area_do" ).val(ui.values[1]);
			$( "#mlsArea" ).html( " от " + ui.values[ 0 ] + " до " + ui.values[ 1 ]  );
		  }
		});
		//$( "#area_ot" ).val( $( "#slider-mlsArea" ).slider( "values", 0 )  );
		//$( "#area_do" ).val( $( "#slider-mlsArea" ).slider( "values", 1 )  );
	  } );
	</script>

             <div class="op10 col-lg-push-1 col-lg-2 col-md-8 col-sm-6 col-xs-12 text-right pt25">
			  
				<div class="form-filter-catalog__button">
    				<button name="UPD" id="UPDFilter" class="button" type="submit">
    				      <img src="/wp-content/themes/realty/assets/images/search_outline.svg" width="16" height="16" alt=""><span>Найти</span>
    				</button>
                </div>
				<input name="mlspage" id="mlspage" type="hidden" value="1"/>
				<input name="mlsorder" id="mlsorder" type="hidden" value="' . (isset($this->mlsorder) ? $this->mlsorder : '1') . '"/>
             </div>
  </div></div></form>
</div></section></div>

<div class="popup-filter">
		<div class="popup" data-popup="popup-filter" data-close-overlay="" aria-hidden="false">
  <div class="popup__wrapper" data-close-overlay="">
    <div class="popup__content">
      <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
      <div class="popup__body">
        <div class="popup__title">
          <h2 class="title--popup">Фильтры</h2>
        </div>

<form method="get" class="filert-mobile-form">
    <div class="realty_filter search_filter">
        <div class="col-xs-12">
 	        <div class="col-xs-12">
				<select class="form-control" onchange="javascript:location=this.value;">
                    <option value="' . $this->pagelink1 . '" selected>Квартиры</option>
                    <option value="' . $this->pagelink1r . '">Комнаты</option>
                    <option value="' . $this->pagelink1nb . '">Новостройки</option>
                    <option value="' . $this->pagelink2 . '">Дома</option>
                    <option value="' . $this->pagelink2_1 . '">Участки</option>
                    <option value="' . $this->pagelink4 . '">Коммерческая</option>
                </select>
	        </div>
            <div class="mt1 col-xs-12">
              <select class="form-control" name="city" onchange="load_raion(this.value,\'raions\')">';
    echo '<option ';
    if ($this->city == 0) echo ' selected ';
    echo ' value=0>Любой город</option>';
    echo $echo_city;
    echo '</select>
            </div>
            <div class="mt1 col-xs-12">
    			<select name="stage" class="form-control">
    		          <option value="" disabled selected>Этаж</option>
    		          <option value="">Любой этаж</option>
    		          <option value="1" ' . ($this->stage == 1 ? 'selected' : '') . '>Первый</option>
    		          <option value="2" ' . ($this->stage == 2 ? 'selected' : '') . '>Последний</option>
    		          <option value="3" ' . ($this->stage == 3 ? 'selected' : '') . '>Не первый</option>
    		          <option value="4" ' . ($this->stage == 4 ? 'selected' : '') . '>Не последний</option>
    		          <option value="5" ' . ($this->stage == 5 ? 'selected' : '') . '>Не крайние</option>
    		          <option value="6" ' . ($this->stage == 6 ? 'selected' : '') . '>Ниже 5-го</option>
    		          <option value="7" ' . ($this->stage == 7 ? 'selected' : '') . '>Выше 5-го</option>
    		          <option value="8" ' . ($this->stage == 8 ? 'selected' : '') . '>Выше 10-го</option>
    		    </select>
            </div>
            <div class="ui-slider-title mt2 col-xs-12">
               <strong>Площадь, м²</strong><span id="mlsAreaM"> от ' . (isset($this->area_ot) && $this->area_ot > 0 ? $this->area_ot : $dataAreaMIN) . ' до ' . (isset($this->area_do) && $this->area_do > 0 ? $this->area_do : $dataAreaMAX) . '</span><br />
        			<input type="hidden" id="area_otM" name="area_ot" value="' . (isset($this->area_ot) && $this->area_ot > 0 ? $this->area_ot : '') . '"/>
        			<input type="hidden" id="area_doM" name="area_do" value="' . (isset($this->area_do) && $this->area_do > 0 ? $this->area_do : '') . '"/>
        			<div class="col-xs-12 mt1" id="slider-mlsAreaM"></div>
            </div>
    	    <div class="ui-slider-title rooms_f mt2 col-xs-12">
    	       <b>Комнат</b>
    	        <div class="rooms-wrapper">
        			<input type="checkbox" name="rooms[]" id="rooms9999M" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 9999) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="9999"><label for="rooms9999M"><span class="btn btn-default">Студия</span></label>
        			<input type="checkbox" name="rooms[]" id="rooms1M" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 1) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="1"><label for="rooms1M"><span class="btn btn-default">1</span></label>
        			<input type="checkbox" name="rooms[]" id="rooms2M" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 2) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="2"><label for="rooms2M"><span class="btn btn-default">2</span></label>
        			<input type="checkbox" name="rooms[]" id="rooms3M" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 3) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="3"><label for="rooms3M"><span class="btn btn-default">3</span></label>
        			<input type="checkbox" name="rooms[]" id="rooms4M" ';
    if ($m_c) foreach ($m_c as $checkbox) if ($checkbox == 4) {
      echo ' checked="checked" ';
      break;
    };
    echo ' value="4"><label for="rooms4M"><span class="btn btn-default">4+</span></label>
    	        </div>
    	    </div>
        	<div class="ui-slider-title mt2 col-xs-12">
        		<strong>Цена, ₽</strong><span id="mlsPriceM"> от ' . number_format((isset($this->price_ot) && $this->price_ot > 0 ? $this->price_ot : $dataPriceMIN) / 1000, 3, ' ', ' ') . ' до ' . number_format((isset($this->price_do) && $this->price_do > 0 ? $this->price_do : $dataPriceMAX) / 1000, 3, ' ', ' ') . '</span><br />
        			<input type="hidden" id="price_otM" name="price_ot" value="' . (isset($this->price_ot) && $this->price_ot > 0 ? $this->price_ot : '') . '">
        			<input type="hidden" id="price_doM" name="price_do" value="' . (isset($this->price_do) && $this->price_do > 0 ? $this->price_do : '') . '">
        			<div class="col-xs-12 mt1" id="slider-mlsPriceM"></div>
        	</div>
                    	<script>
                    		function numberWithSpaces(x) {
                    		  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                    		}
                    		function clearslide(){
                    		$( "#price_otM" ).val(0);
                    		$( "#price_doM" ).val(0);
                    		$( "#area_otM" ).val(0);
                    		$( "#area_doM" ).val(0);
                    	}
                    	  $( function() {
                    		$( "#slider-mlsPriceM" ).slider({
                    		  range: true,
                    		  min: ' . $dataPriceMIN . ',
                    		  max: ' . $dataPriceMAX . ',
                    		  step: 1,
                    		  values: [ ' . (isset($this->price_ot) && $this->price_ot > 0 ? $this->price_ot : $dataPriceMIN) . ', ' . (isset($this->price_do) && $this->price_do > 0 ? $this->price_do : $dataPriceMAX) . ' ],
                    		  slide: function( event, ui ) {
                    			$( "#price_otM" ).val(ui.values[0]);
                    			$( "#price_doM" ).val(ui.values[1]);
                    			$( "#mlsPriceM" ).html( " от " + numberWithSpaces(ui.values[ 0 ]) + " до " + numberWithSpaces(ui.values[ 1 ])  );
                    		  }
                    		});
                    	  } );
                    
                    	  $( function() {
                    		$( "#slider-mlsAreaM" ).slider({
                    		  range: true,
                    		  min: ' . $dataAreaMIN . ',
                    		  max: ' . $dataAreaMAX . ',
                    		  step: 1,
                    		  values: [ ' . (isset($this->area_ot) && $this->area_ot > 0 ? $this->area_ot : $dataAreaMIN) . ', ' . (isset($this->area_do) && $this->area_do > 0 ? $this->area_do : $dataAreaMAX) . ' ],
                    		  slide: function( event, ui ) {
                    			$( "#area_otM" ).val(ui.values[0]);
                    			$( "#area_doM" ).val(ui.values[1]);
                    			$( "#mlsAreaM" ).html( " от " + ui.values[ 0 ] + " до " + ui.values[ 1 ]  );
                    		  }
                    		});
                    	  } );
                    	</script>

            <div class="col-xs-12 filert-mobile-form__button filter-button">
				<button name="UPD" class="button" type="submit">
				      <span>Применить</span>
				</button>
            </div>
        </div>
    </div>
</form>

      </div>
    </div>
  </div>
</div>
</div>

	<script>
   if(typeof(csel_rn)==\'undefined\')var csel_rn = new Array(); ' . $this->script_arr . '
                 var selectize=[];
						var $sel_tools2=$(\'#raions\').selectize({
							maxItems: null,
							valueField: \'id\',
							labelField: \'text\',
							searchField: \'text\',
							' . ($this->city != '' ? 'options: csel_rn[' . $this->city . '],' : '') . '
							' . ($whereS != '' ? 'items:[' . $whereS . '],' : '') . '
							create: false,
							plugins: [\'remove_button\']
						});

			         		var data_cel4=[{id:80,text:\'Кирпичный\'},
											{id:83,text:\'Панельный\'},
											{id:510,text:\'Блочный\'},
											{id:82,text:\'Монолитный\'},
											{id:78,text:\'Деревянный\'},
											{id:76,text:\'другое\'}];

						var $sel_tools4=$(\'#mater\').selectize({
							maxItems: null,
							valueField: \'id\',
							labelField: \'text\',
							searchField: \'text\',
							//closeAfterSelect:true,
							options: data_cel4,
							' . ($whereM != '' ? 'items:[' . $whereM . '],' : '') . '
							create: false,
							plugins: [\'remove_button\']
						});
                     selectize[\'raions\']=$sel_tools2[0].selectize;
                     selectize[\'mater\']=$sel_tools4[0].selectize;
			         function PutListToSelect(selid,list,selectedids)
			         {
			         		selectize[selid].clear();
           					selectize[selid].clearOptions();
           					selectize[selid].addOption(list);
			         }
			function load_raion(q,name){PutListToSelect(name,csel_rn[q],[]);};

	</script>
   ';
  }


  // ------------------------------------------------------------------------------
  function createStats($stats, $rc)
  {

    echo '<div class="row">
		        <div class="col-xs-12">
			        <div class="catalog__subtitle">' . num_word($rc, array('Найден', 'Найдено', 'Найдено'), false) . ' ' . num_word($rc, array('квартира', 'квартиры', 'квартир')) . '</div>
		        </div>
		      </div>';
    echo '<div class="row realty_">
            <div class="col-xs-12 catalog-wrapper" >';

    if ($stats)
      if (mysqli_num_rows($stats) > 0) {
        $postCount = 0;

        $cookieValueCategories = $_COOKIE['categories'];
        $unescapedValueCategories = stripslashes($cookieValueCategories);
        $postIdsCategories = json_decode($unescapedValueCategories, true); // Декодирование JSON строки в массив

        $arrayCategories = array();


        foreach ($postIdsCategories as $str) {
          $value = explode(",", $str);
          $category = $value[0];
          $number = intval($value[1]);
          $categoryType = $value[2];

          $obj = array("category" => $category, "number" => $number, "type" => $categoryType);

          $arrayCategories[] = $obj;
        }

        $option_category_card = array(
          'imgpath' => $this->imgpath,
          'scriptpath' => $this->scriptpath,
          'avatarpath' => $this->avatarpath,
          'arrayCategories' => $arrayCategories,
          'isAparts' => true
        );

        while ($author = mysqli_fetch_array($stats)) {


          do_action('view_category_card', $author, $option_category_card);

          $postCount++;
          if ($postCount % 6 == 0) {
            get_template_part('template-page/components/info-sale');
          }
        };
      } else echo 'Ничего не найдено';
    echo '  </div>
              </div>';

    if ($this->rc > $this->realty_limit) {
      echo '      <div class="row">
                              <div class="col-xs-12">
                                <div class="' . $this->html_navdiv . '">
                                  <div class="pagination__container">';
      $this->navigation($this->rc, $this->realty_limit);
      echo '            </div>
                                </div>
                              </div>
                            </div>';
    }
  }



  //-----------------------------------------------------------
  // Вывод карточки объекта
  function showObject($fields)
  {
    include "file.php";
    $sql_text_summ = 'select SQMPRICE';

    $where = ' WHERE COMPID=' . $agency . ' AND PICCOUNT>0 ';

    $dbcnx = mysqli_connect($dblocation, $dbuser, $dbpasswd, $dbname);
    if (!$dbcnx) exit();
    $sq_all_price = mysqli_query($dbcnx, $sql_text_summ . ' FROM APARTS ' . $where . ' and WHAT=0 and VARIANT<>2 order by DATEINP DESC ');

    $sq_int_sq_price_array = array();

    if (!empty($sq_all_price)) {
      if (mysqli_num_rows($sq_all_price) > 0) {
        while ($price = mysqli_fetch_array($sq_all_price)) {
          $sq_int_sq_price_array[] = intval($price['SQMPRICE']);
        }
      }
    }

    $sum_price = array_sum($sq_int_sq_price_array);



    if (!empty($sum_price) && !empty($sq_int_sq_price_array)) {
      $average_price = intval($sum_price / count($sq_int_sq_price_array));
    }



    $cnt = mysqli_fetch_array($fields);
    if ($cnt["Город"] == 'Область') $town = $this->oblast;
    else $town = ("г." . $cnt["Город"]);
    if ($cnt["CARDNUM"] == 0) {
      echo '<h2>ЭТОГО ОБЪЕКТА УЖЕ НЕТ В НАШЕЙ БАЗЕ!</h2>
    <h3>Кто-то его купил, или снял с продажи, но если вы посмотрите на сайте вы возможно найдете подходящий вариант.</h3>';
    } else {
      $pTtl = ($cnt["VARIANT"] == 2 ? 'Сдаётся ' . ($cnt["RPLANID"] != 622 ? $cnt["Комнат"] . '-ком. квартира' : 'студия') : 'Продаётся ' . ($cnt["RPLANID"] != 622 ? $cnt["Комнат"] . '-ком. квартира' : 'студия')) . ' ' . $cnt["AAREA"] . '<small> м²</small>';
      $addr = $town . ", " . (trim($cnt["Район"]) != '' ? str_replace('р-н', '', $cnt["Район"]) . " р-н, " : '') . $cnt["Улица"];

      $pTtl1 = $town . ", " . ($cnt["RPLANID"] != 622 ? $cnt["Комнат"] . '-комнатная квартира' : 'студия') . ", " . $cnt["Тип дома"] . ", на " . $cnt["Улица"] . ", " . $cnt["STAGE"] . ", " . $cnt["Материал стен"] . ",площадь: " . $cnt["AAREA"] . " кв.м, цена: " . number_format($cnt["Цена общая"] / 1000, 3, ' ', ' ') . " рублей.";

      $doctitle = $pTtl . ' ' . $addr;
      $doctitle1 = $pTtl1;

      if (trim($cnt['PICTURES']) != '') $pics = explode(';', $cnt['PICTURES']);
      else $pics = '';
      foreach ($pics as $pic) {
        $count = 0;
        $str = str_replace("http", "", $pic, $count);
        if ($count > 0) $imgpath1 = $pic;
        else $imgpath1 = $this->imgpath . $cnt['COMPID'] . '/' . $pic;
        $pics_carbon[] = $imgpath1;
      }
      $str = strpos(trim($cnt['VTOUR']), "youtu");
      if ((trim($cnt['VTOUR']) != '') && ($str > 0)) $pics_carbon[] = $cnt['VTOUR'];
      else $pics_carbon[] = '0';
      carbon_set_post_meta(get_the_ID(), 'product-id', $cnt['CARDNUM']);
      carbon_set_post_meta(get_the_ID(), 'product-agent-phone', preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $cnt["PHONE"]));
      carbon_set_post_meta(get_the_ID(), 'product-gallery', $pics_carbon);
      carbon_set_post_meta(get_the_ID(), 'product-price', number_format($cnt["Цена общая"] / 1000, 3, ' ', ' '));

      $cookieValueCategories = $_COOKIE['categories'];
      $unescapedValueCategories = stripslashes($cookieValueCategories);
      $postIdsCategories = json_decode($unescapedValueCategories, true); // Декодирование JSON строки в массив

      $arrayCategories = array();


      foreach ($postIdsCategories as $str) {
        $value = explode(",", $str);
        $category = $value[0];
        $number = intval($value[1]);
        $categoryType = $value[2];

        $obj = array("category" => $category, "number" => $number, "type" => $categoryType);

        $arrayCategories[] = $obj;
      }

      $is_favorite = in_array($cnt['CARDNUM'], array_column($arrayCategories, 'number'));
      echo '

<div class="main-second-product__page">
    <section class="single-page">
      <div class="single-page__container p0 mb1">
        <div class="single-page-wrapper">
          <div class="single-page__info">
            <div class="single-page__title">
              <a class="button-back" href="" onclick="window.history.go(-1); return false;" aria-label="Назад"></a>
              <h1 class=" title--xl title--catalog title--singe-page">' . $pTtl . '</h1>
              <script>document.title = "' . strip_tags($pTtl) . '" </script>
            </div>
            <p class="single-page__subtitle">' . $addr . '              <a href="#single-map">Показать на карте</a>
           
            </p>
            <div class="product-wrapper">
              <div class="single-page__product product">';




      if ($pics != '') {
        echo '<div class="product__image-wrapper">  
             <div class="product__image" data-type="popup-gallery">
			 <div class="product-image-wrapper">';
        $pic = $pics[0];
        unset($pics[0]);
        $count = 0;
        $str = str_replace("http", "", $pic, $count);
        if ($count > 0) $imgpath1 = $pic;
        else $imgpath1 = $this->imgpath . $cnt['COMPID'] . '/' . $pic;
        echo '<img loading="lazy" class="product-image-wrapper__preview" data-type="popup-gallery" src="' . $imgpath1 . '" data-post-id="' . $cnt['CARDNUM'] . '">';
        echo '</div>
			</div>
	   
                  <div class="product-single-slider swiper">
                    <div class="product-single-slider__wrapper swiper-wrapper">';

        foreach ($pics as $pic) {
          $count = 0;
          $str = str_replace("http", "", $pic, $count);
          if ($count > 0) $imgpath1 = $pic;
          else $imgpath1 = $this->imgpath . $cnt['COMPID'] . '/' . $pic;
          echo '  <div class="product-single-slider__slide swiper-slide" >
                                    <img class="swiper-lazy" data-src="' . $imgpath1 . '" src="' . get_template_directory_uri() . '/assets/images/1px.png" alt="" data-type="popup-gallery">
                                    <div class="swiper-lazy-preloader"></div>
                                  </div>';
        }

        echo ' 
                    </div>
                  </div>
                <div class="custom-scrollbar"></div>
			    
	   
                <div class="product__gallery">
                    <div data-type="popup-gallery">';

        if (!empty($pics[2])) {
          $pic = $pics[2];
          $count = 0;
          $str = str_replace("http", "", $pic, $count);
          if ($count > 0) $imgpath1 = $pic;
          else $imgpath1 = $this->imgpath . $cnt['COMPID'] . '/' . $pic;
          echo ' <img loading="lazy" src="' . $imgpath1 . '" alt="" width="226" height="166" >';
        }
        echo '
                    </div>
                    <div data-type="popup-gallery">';
        if (!empty($pics[3])) {
          $pic = $pics[3];
          $count = 0;
          $str = str_replace("http", "", $pic, $count);
          if ($count > 0) $imgpath1 = $pic;
          else $imgpath1 = $this->imgpath . $cnt['COMPID'] . '/' . $pic;
          echo ' <img loading="lazy" src="' . $imgpath1 . '" alt="" width="226" height="166" >';
        }
        echo '
                      <span data-type="popup-gallery">' . $cnt["PICCOUNT"] . '</span>
                    </div>
                </div>
    	   </div>
    	   <div class="product-single-slide-gallery swiper">
    	        <div class="product-single-slide-gallery__wrapper swiper-wrapper">';
        foreach ($pics as $pic) {
          $count = 0;
          $str = str_replace("http", "", $pic, $count);
          if ($count > 0) $imgpath1 = $pic;
          else $imgpath1 = $this->imgpath . $cnt['COMPID'] . '/' . $pic;
          echo ' 
                                          <div class="product-single-slide-gallery__slide swiper-slide">
                                            <img src="' . $imgpath1 . '" alt="" >
                                          </div>';
        }
      }


      echo '
		</div>
    
		</div>		
   
                <div class="product__info">
                  <div class="second-product-info">
                    <div class="second-product-info__price">
                      <div>
                        <h2 class="title--xl">' . number_format(($cnt["Цена общая"] / 1000), 3, ' ', ' ') . ' ₽</h2>
                         <span>' . number_format(($cnt["SQMPRICE"] / 1000), 3, ' ', ' ') . ' ₽ / м²</span>   
                                            </div>
                                           
                      <div class="second-product-info__label">' . (!empty($average_price) && $average_price >= intval($cnt["SQMPRICE"]) ? "Хорошая цена!" : "Элитная недвижимость!")  . '</div>
                    </div>
                    <div class="second-product-info__list">
                      <div class="second-product-info__item">
                                <img src="http://obj-estate.ru/wp-content/themes/realty/assets/images/size.svg" alt="" width="48" height="48">
                                <div class="info">
                                  <span class="title">
                                    <span>Общая площадь</span>
                                    <span>общая пл.</span>
                                  </span>
                                  <span class="value">' . $cnt["AAREA"] . ' м²</span>
                                </div>
                              </div>                      <div class="second-product-info__item">
                                <img src="http://obj-estate.ru/wp-content/themes/realty/assets/images/plan.svg" alt="" width="48" height="48">
                                <div class="info">
                                  <span class="title">
                                    <span>Жилая площадь</span>
                                    <span>жилая пл.</span>
                                  </span>
                                  <span class="value">' . $cnt["LAREA"] . ' м²</span>
                                </div>
                              </div>                      <div class="second-product-info__item">
                                <img src="http://obj-estate.ru/wp-content/themes/realty/assets/images/kitchen.svg" alt="" width="48" height="48">
                                <div class="info">
                                  <span class="title">
                                    <span>Площадь кухни</span>
                                    <span>пл. кухни</span>
                                  </span>
                                  <span class="value">' . $cnt["KAREA"] . ' м²</span>
                                </div>
                              </div>                                                                                      </div>
                  </div>
                </div>
                <div class="product__description">
                  <div class="second-product-description no-active" data-more-text="">' . $cnt["Примечание"] . '</div>
                </div>
                <div class="product__button-more">
                  <button class="show-more" type="button" data-more=""><span>Узнать больше</span></button>
                </div>
                <div class="product__more">
                  <section class="more-list">
                    <div class="more-list__column more-column">
                      <label class="more-tabs__label">
                        <input class="more-tabs__input" type="radio" name="more-list" checked="">
                        <span class="more-column__title title--lg">О квартире</span>
                      </label>
                      <div class="more-column__list">
                        <div class="more-column__row">
                          <span>Тип жилья</span><span>Вторичное</span>
                        </div>
						  <div class="more-column__row">
                                  <span>Комнат</span><span>' . ($cnt["RPLANID"] != 622 ? $cnt["Комнат"] : 'студия') . '</span>
                          </div>  						
                         <div class="more-column__row">
                                  <span>Общая площадь</span><span> ' . $cnt["AAREA"] . '</span>
                                </div> 
						 <div class="more-column__row">
                                  <span>Жилая площадь</span><span>' . $cnt["LAREA"] . '</span>
                          </div>
						  <div class="more-column__row">
                                  <span>Площадь кухни</span><span>' . $cnt["KAREA"] . '</span>
                          </div>  
						  <div class="more-column__row">
                                  <span>Этаж</span><span>' . $cnt["STAGE"] . '</span>
                          </div>  
						  </div>
                    </div>
                    <div class="more-list__column more-column">
                      <label class="more-tabs__label">
                        <input class="more-tabs__input" type="radio" name="more-list">
                        <span class="more-column__title title--lg">О доме</span>
                      </label>
                      <div class="more-column__list">';
      if ($cnt["GK"] != '') echo '<div class="more-column__row"><span>Жилой комплекс</span><span>' . $cnt["GK"] . '</span></div>';
      if ($cnt["GKD"] != '') echo '<div class="more-column__row"><span>Секция</span><span>' . $cnt["GKD"] . '</span></div>';
      if ($cnt["HSTAGE"] != '') echo '<div class="more-column__row"><span>Этажность</span><span>' . $cnt["HSTAGE"] . '</span></div>';
      if ($cnt["Материал стен"] != '') echo '<div class="more-column__row"><span>Материал стен</span><span>' . mb_strtolower($cnt["Материал стен"]) . '</span></div>';

      echo '
                        <div class="more-column__row">
                          <span>Лифт</span>
                          <span>
                            ' . $cnt["Лифт"] . '                          </span>
                        </div>
                         <div class="more-column__row">
                                  <span>Тип дома</span><span>' . mb_strtolower($cnt["Тип дома"]) . '</span>
                                </div>                      </div>
                    </div>
                  </section>
                </div>
                <div id="single-map" class="product__map">
                    <div class="single-page-map-title">' . $addr . '</div>
                  
                    <div class="container">
                        <div class="map__select">
                            <div>
                                <input id="map_select_1" class="map_select_checkbox" type="checkbox" value="market" name="show[]">
                                <label for="map_select_1" class="map_select_checkbox_label">Продуктовые магазины</label>
                            </div>
                            <div>
                                <input id="map_select_2" class="map_select_checkbox" type="checkbox" value="hospital" name="show[]">
                                <label for="map_select_2" class="map_select_checkbox_label">Медицинские учреждения</label>
                            </div>
                            <div>
                                <input id="map_select_3" class="map_select_checkbox" type="checkbox" value="school" name="show[]" checked="true">
                                <label for="map_select_3" class="map_select_checkbox_label">Школы</label>
                            </div>
                            <div>
                                <input id="map_select_4" class="map_select_checkbox" type="checkbox" value="kindergarten" name="show[]" checked="true">
                                <label for="map_select_4" class="map_select_checkbox_label">Детские сады</label>
                            </div>
                        </div>
                        <div id="map" class="map-wrapper"></div>
                    </div>
                    
                    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=d62977c1-6b54-4445-9e4f-920e2ef797de"
                        type="text/javascript">
                    </script>
                    <script>
                    
                    ymaps.ready(init);
                    
                    function init() {
                          var myMap = new ymaps.Map("map", {
                            center: [ ' . $cnt["COORDN"] . ' ],
                            zoom: 16
                          }, {
                            searchControlProvider: "yandex#search"
                          }),
                            objectManager = new ymaps.ObjectManager({
                              clusterize: false,
                              gridSize: 32,
                              clusterDisableClickZoom: false,
                            });
                        
                            var myPlacemark = new ymaps.Placemark( [  ' . $cnt["COORDN"] . '  ], {
                              balloonContent: ""
                            }, {
                              iconLayout: "default#imageWithContent",
                              iconImageHref:  "/wp-content/themes/realty/assets/images/yamap/ico_adres.svg" ,
                              iconImageSize: [26, 30],
                              iconImageOffset: [-13, -30],
                              balloonOffset: [17, 0],
                              balloonPanelMaxMapArea: 0,
                              hideIconOnBalloonOpen: false
                            });
                          
                            objectManager.objects.options.set("iconLayout", "default#imageWithContent");
                            objectManager.objects.options.set("iconImageSize", [20, 20]);
                            objectManager.objects.options.set("iconImageOffset", [-10, -20]);
                            objectManager.objects.options.set("balloonOffset", [0, -10]);
                            objectManager.objects.options.set("balloonPanelMaxMapArea", 0);
                            objectManager.objects.options.set("hideIconOnBalloonOpen", false);
                            objectManager.clusters.options.set("iconColor', '#4C4DA2");
                        
                            
                          myMap.geoObjects.add(objectManager);
                          myMap.geoObjects.add(myPlacemark);
                        
                          $(document).ready(function() {
                                function handleCheckboxChange() {
                                    $(".map_select_checkbox").each(function() {
                                        objectManager.removeAll();
                                        if ($(this).is(":checked")) {
                                            var city = "' . $cnt['Город'] . '"; 
                                            var citySlug = (city === "Новороссийск") ? "novoross" :  "krasnodar" ;
                                            
                                            if (citySlug !== "") {
                                                var url = "/map/" + citySlug + "/" + $(this).val() + ".json";
                                                $.get(url).done(function (data) {
                                                    objectManager.add(data);
                                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                                    console.error("Ошибка при загрузке данных: " + errorThrown);
                                                });
                                            } else {
                                                console.error("Город не определен");
                                            }
                                        }
                                    });
                                }
                            
                                $(".map_select_checkbox").on("change", handleCheckboxChange);
                                handleCheckboxChange(); // Вызов функции для обработки состояния чекбоксов при загрузке страницы
                            });
                        
                        }
                        </script>
                    
   
	               
                </div>
              </div>
              <div class="single-page__order">
                <article class="agent-order" data-agent-order="">
                  <p class="agent-order__date">Информация обновлена  ' . date('d.m.Y', strtotime($cnt["DATECOR"])) . '</p>
                  <h2 class="agent-order__price title--xl title--product-agent">' . number_format(($cnt["Цена общая"] / 1000), 3, ' ', ' ') . ' ₽</h2>
                  <div class="agent-order__label">' . (!empty($average_price) && $average_price >= intval($cnt["SQMPRICE"]) ? "Хорошая цена!" : "Элитная недвижимость!")  . '</div>
                  <div class="agent-order__ipoteca">В ипотеку от <span>' . number_format(((($cnt["Цена общая"] - $cnt["Цена общая"] * 0.2) * 0.01 * (1.01 ** (30 * 12)) / ((1.01 ** (30 * 12)) - 1)) / 1000), 3, ' ', ' ') . '</span> ₽/мес</div><div class="agent-order__price-one-metr agent-price-one-mert">
                      <span class="agent-price-one-mert__title">Цена за метр</span>
                      <span class="agent-price-one-mert__space"></span>
                      <span class="agent-price-one-mert__price">' . number_format(($cnt["SQMPRICE"] / 1000), 3, ' ', ' ') . ' ₽/м²</span>
                  </div>                  
                        
                  <div class="button-wrapper">      
                      <div class="agent-order__button">
                        <a onclick="showFullNumber(event)" class="button button--phone-order" href="tel:' . preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7$2$3$4$5", $cnt["PHONE"]) . '"><span>' . substr(preg_replace("/(\\d{1})(\\d{3}|\\d{4})(\\d{3}|\\d{4})(\\d{2})(\\d{2})$/i", "+7 $2 $3 - $4 - $5", $cnt["PHONE"]), 0, 14) . '...</span></a>
                        <button class="button--favorites-mobile ' . ($is_favorite ? 'delete' : '') . '" type="button" data-favorite-cookies="' . $cnt['CARDNUM'] . '" data-category-cookie="APARTS" data-button-favorite-mobile data-delete-favorite="' . $is_favorite . '"><span></span></button>
                      </div>
                      <div class="agent-order__callback" onclick="setLink(event,\'' . esc_url('?obj=' . $cnt['CARDNUM']) . '\',\'kvartiry\')">
                        <button class="button button--callback" type="button" data-type="popup-form-callback"><span data-type="popup-form-callback">Перезвоните мне</span></button>
                      </div>
                      <div class="agent-order__favorites">
                        <button class="button button--favorites" type="button" data-favorite-cookies="' . $cnt['CARDNUM'] . '" data-category-cookie="APARTS" data-button-favorite data-delete-favorite="' . $is_favorite . '">
                            <span>';
      if ($is_favorite) {
        echo "удалить";
      } else {
        echo "В избранное";
      }
      echo '  </span>
                        </button>
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
                                }
                            }
                          }
                      </script>
                  </div>
                </article>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="popup-gallery">';
      get_template_part('template-page/popup/popup-gallery');
      echo ' </div>
  </section>
 </div>

';
    }
  }

  function navigation($recs, $limit)
  {
    $dieser   = ceil($recs / $limit);
    $count    = $this->mlspage;
    $numpages = 5;
    if ($dieser <= $numpages) {
      $cnd = 1;
      $cnm = $dieser;
    } elseif ($count - ($numpages - 1) / 2 < 0) {
      $cnd = 1;
      $cnm = $numpages;
    } elseif ($count + ($numpages - 1) / 2 > $dieser) {
      $cnm = $dieser;
      $cnd = $dieser - $numpages;
    } else {
      $cnd = $count - ($numpages - 1) / 2;
      $cnm = $count + ($numpages - 1) / 2;
    }
    if ($cnd < 1) $cnd = 1;

    echo '<ul class="page-numbers">';
    for ($i = $cnd; $i <= $cnm; $i++) {
      if ($i == $count) echo "<li class='active'><span>" . $i . "</span></li>";
      else echo "<li><a class='cpointer' onclick='$(\"#mlspage\").val(\"" . $i . "\");$(\"#UPDFilter\").click();return false;'>" . $i . "</a></li>";
    }
    if ($count < $dieser) {
      if ($count < $dieser - 11) echo "<li> <span>...</span> <a class='cpointer' onclick='$(\"#mlspage\").val(\"" . ($count + 10) . "\");$(\"#UPDFilter\").click();return false;'>" . ($count + 10) . "</a></li>";
      echo "<li><a class='cpointer' onclick='$(\"#mlspage\").val(\"" . ($count + 1) . "\");$(\"#UPDFilter\").click();return false;'>" . $this->nav_next . "</a></li>";
    } else echo "<li>" . $this->nav_end . "</li>";
    echo '</ul>';
  }

  function getParam($param, $val = '')
  {
    if (isset($param)) {
      $return = strval(urldecode($param));
      $return = preg_replace("/[^a-zA-Z0-9\,\.\_\-]/i", "", $return);
      if (!get_magic_quotes_gpc()) {
        $return = addslashes($return);
      }
      return preg_replace("/[^\x20-\xFF]/", "", @strval($return));
    } else {
      return $val;
    }
  }
}

$mls = new MLSReal;
$mls->start();
