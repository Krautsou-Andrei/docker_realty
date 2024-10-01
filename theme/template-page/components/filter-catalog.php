<?php
require_once get_template_directory() . '/inc/lib/get_names_children_categories.php';
require_once get_template_directory() . '/inc/lib/search_id_category_by_name.php';
require_once get_template_directory() . '/inc/enums/categories_name.php';

$filter_city = isset($_GET['city']) ? $_GET['city'] : '2306';



$search_param_city = $filter_city === '2306' ? 'Новороссийск' : ($filter_city === '2301' ? 'Краснодар' : '');

$filter_type_build = isset($_GET['type-build']) ? $_GET['type-build'] : 'Квартиры';

$filter_rooms = isset($_GET['rooms']) ? $_GET['rooms'] : '';
$filter_rooms_array = isset($_GET['rooms']) ? explode(',', $_GET['rooms']) : [];

$filter_price_array = isset($_GET['select_price']) ?  explode('-', $_GET['select_price']) : [];
$filter_price = isset($_GET['select_price']) ?   $_GET['select_price'] : '';
$filter_price_ot = isset($filter_price_array[0]) ? $filter_price_array[0] : '';
$filter_price_do = isset($filter_price_array[1]) ? $filter_price_array[1] : '';

$filter_check_price = isset($_GET['check_price']) ? $_GET['check_price'] : '';

$filter_area_array = isset($_GET['select_area']) ? explode('-', $_GET['select_area']) : [];
$filter_area = isset($_GET['select_area']) ? $_GET['select_area'] : '';
$filter_area_ot = isset($filter_area_array[0]) ? $filter_area_array[0] : '';
$filter_area_do = isset($filter_area_array[1]) ? $filter_area_array[1] : '';


// $cities_parent_category_id  = search_id_category_by_name(CATEGORIES_NAME::CITIES);
// $cities_names = !empty($cities_parent_category_id) ? get_names_children_categories($cities_parent_category_id) : [];

$rooms_paren_category_id = search_id_category_by_name(CATEGORIES_NAME::ROOMS);
$rooms_names = !empty($rooms_paren_category_id) ? get_names_children_categories($rooms_paren_category_id) : [];


?>



<form action="/wp-content/themes/realty/inc/lib/filter-new-building.php?>" class="filter-catalog__form form-filter-catalog" method="get">
  <div class="form-filter-catalog__list">
    <input hidden type="radio" name="type" value="novostrojki" data-name="Дома" id="" checked />
    <input hidden type="text" name="select-price" value="<?php echo $filter_price ?>" id="" checked data-select-price />
    <input hidden type="text" name="select-area" value="<?php echo $filter_area ?>" id="" checked data-select-area />
    <div class="label-option-radio-wrapper label label-city" id="filter-city" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Город"><?php echo !empty($search_param_city) ? $search_param_city : 'Город' ?></span>
        <span data-arrow></span>
      </div>

      <div class="option-radio__select" data-select>
        <ul>
          <li>
            <label>
              <span>Новороссийск</span>
              <input type="radio" name="option-radio-city" value="Новороссийск" data-name="Новороссийск" id="" <?php echo $filter_city === '2306' ? 'checked' : '' ?> data-input-visible />
              <span></span>
            </label>
          </li>
          <li>
            <label>
              <span>Краснодар</span>
              <input type="radio" name="option-radio-city" value="Краснодар" data-name="Краснодар" id="" <?php echo $filter_city === '2301' ? 'checked' : '' ?> data-input-visible />
              <span></span>
            </label>
          </li>
        </ul>
      </div>
    </div>
    <div class="label-option-radio-wrapper label label-type" id="filter-flat" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Тип объекта"><?php echo !empty($filter_type_build) ? $filter_type_build : 'Тип объекта' ?></span>
        <span data-arrow></span>
      </div>
      <div class="option-radio__select" data-select>
        <ul>
          <li>
            <label>
              <span>Квартиры</span>
              <input type="radio" name="option-radio-type-build" value="Квартиры" data-name="Квартиры" id="" <?php echo $filter_type_build === 'Квартиры' ? 'checked' : '' ?> data-input-visible />
              <span></span>
            </label>
          </li>
          <li>
            <label>
              <span>Дома</span>
              <input type="radio" name="option-radio-type-build" value="Дома" data-name="Дома" id="" <?php echo $filter_type_build === 'Дома' ? 'checked' : '' ?> data-input-visible />
              <span></span>
            </label>
          </li>
        </ul>
      </div>
    </div>
    <?php if (!empty($rooms_names)) { ?>
      <div class="label-option-checkbox-wrapper label label-rooms" id="filter-rooms" data-filter-rooms data-checked>
        <div class="option-checkbox">
          <span class="option-checkbox__label" data-checked-view data-default-value="<?php echo CATEGORIES_NAME::ROOMS ?>"><?php echo !empty($filter_rooms) ? $filter_rooms : CATEGORIES_NAME::ROOMS ?></span>
          <span data-arrow></span>
        </div>
        <div class="option-checkbox__select" data-select>
          <ul>
            <?php foreach ($rooms_names as $room_name) {
              $name = intval($room_name) ? intval($room_name) . '-комн.' : $room_name;
            ?>
              <li>
                <label>
                  <span> <?php echo  $name ?></span>
                  <input type="checkbox" name="option-checkbox-rooms[]" value="<?php echo  $name ?>" <?php echo in_array($name, $filter_rooms_array) ? 'checked' : '' ?>data-input-visible>
                  <span></span>
                </label>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    <?php } ?>
    <div class="label-option-radio-wrapper label label-area" id="filter-area" data-checked>
      <div class="option-checkbox">
        <span class="option-checkbox__label" data-checked-view data-default-value="Площадь"><?php echo !empty($filter_area) ? $filter_area : 'Площадь' ?></span>
        <span data-arrow></span>
      </div>
      <div class="option-checkbox__select select-area" data-select>
        <div class="select-area__top">
          <span class="select-area__title">Общая, м²</span>
          <div class="select-area__wrapper-label">
            <label>
              <input type="number" name="option-select-area-from" placeholder="от" id="area-from" min="1" data-input-visible value="<?php echo !empty($filter_area_ot) ? $filter_area_ot : ''  ?>">
            </label>
            <label>
              <input type="number" name="option-select-area-to" placeholder="до" id="area-to" min="1" data-input-visible value="<?php echo !empty($filter_area_do) ? $filter_area_do : ''  ?>">
            </label>
          </div>
        </div>
        <div class="select-area__button">
          <button class="button" type="button" data-button-success><span>Применить</span></button>
        </div>
      </div>
    </div>
    <div class="label-option-checkbox-wrapper label label-price" id="filter-price" data-checked>
      <div class="option-checkbox">
        <span class="option-checkbox__label" data-checked-view data-default-value="Цена"><?php echo !empty($filter_price) ? $filter_price : 'Цена'  ?> </span>
        <span data-arrow></span>
      </div>
      <div class="option-checkbox__select select-area" data-select>
        <div class="select-price__top">
          <div class="select-price__title">
            <label class="switch">
              <input type="checkbox" id="check-price" name="check-price" value="<?php echo $filter_check_price ?>" <?php echo $filter_check_price == 'on' ? 'checked' : '' ?> data-check-price>
              <span class="slider"></span>
            </label>
            <span id="all-area">За всю площадь</span>
            <span id="metr-area">За квадрат</span>
          </div>
          <div class="select-price__wrapper-label">
            <label>
              <input type="number" name="option-select-price-from" placeholder="от" id="price-from" min="1" data-input-visible value="<?php echo !empty($filter_price_ot) ? $filter_price_ot : ''  ?>">
              <span>₽</span>
            </label>
            <label>
              <input type="number" name="option-select-price-to" placeholder="до" id="price-to" min="1" data-input-visible value="<?php echo !empty($filter_price_do) ? $filter_price_do : ''  ?>">
              <span>₽</span>
            </label>
          </div>
        </div>
        <div class="select-price__button">
          <button class="button" type="button" data-button-success><span>Применить</span></button>
        </div>
      </div>
    </div>
  </div>

  <div class="form-filter-catalog__button">
    <button class="button" type="submit">
      <img src="<?php bloginfo('template_url'); ?>/assets/images/search_outline.svg" width="16" height="16" alt="">
      <span>Найти</span>
    </button>
  </div>
</form>