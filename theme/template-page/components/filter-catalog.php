<?php
require_once get_template_directory() . '/inc/enums/categories_name.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/get_params_filter_catalog.php';
require_once get_template_directory() . '/inc/lib/search_id_page_by_name.php';

$search_params = get_params_filter_catalog();

$cities_names = $search_params->cities_names;
$filter_city = $search_params->filter_city;

$filter_type_build = $search_params->filter_type_build;

$filter_rooms = $search_params->filter_rooms;
$filter_rooms_array = $search_params->filter_rooms_array;

$filter_price = $search_params->filter_price;
$filter_price_ot = $search_params->filter_price_ot;
$filter_price_do = $search_params->filter_price_do;

$filter_check_price = $search_params->filter_check_price;

$filter_area = $search_params->filter_area;
$filter_area_ot = $search_params->filter_area_ot;
$filter_area_do = $search_params->filter_area_do;

$filter_region = $search_params->filter_region;
$regions_names = $search_params->regions_names;

$rooms_names = $search_params->rooms_names;

$max_area = $search_params->max_area;
$max_price =  $search_params->max_price;

$link_page_map = $search_params->link_page_map;
$type_filter = $search_params->type_filter;

$filter_price_view = explode('-', $filter_price);

if (!empty($filter_price_view[0])) {
  $filter_price_view = number_format(round($filter_price_view[0]), 0, '.', ' ') . ' - ' . number_format(round($filter_price_view[1]), 0, '.', ' ');
} ?>

<form action="/wp-content/themes/realty/inc/lib/filter-new-building.php?>" class="filter-catalog__form form-filter-catalog" method="get">
  <div class="form-filter-catalog__list">
    <input hidden type="radio" name="type" value="<?php echo $type_filter ?>" data-name="Дома" id="" checked data-select-type />
    <input hidden type="text" name="select-price" value="<?php echo $filter_price ?>" id="" checked data-select-price />
    <input hidden type="text" name="select-area" value="<?php echo $filter_area ?>" id="" checked data-select-area />
    <div class="label-option-radio-wrapper label label-region" id="filter-region" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Регион"><?php echo !empty($filter_region) ? $filter_region : 'Регион' ?></span>
        <span data-arrow></span>
      </div>
      <?php if (!empty($regions_names)) { ?>
        <div class="option-radio__select" data-select>
          <ul>
            <?php foreach ($regions_names as $region) { ?>
              <li>
                <label>
                  <span><?php echo $region ?></span>
                  <input type="radio" name="option-radio-region" value="<?php echo $region ?>" data-name="<?php echo $region ?>" id="" <?php echo $filter_region === $region ? 'checked' : '' ?> data-input-visible />
                  <span></span>
                </label>
              </li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
    </div>
    <div class="label-option-radio-wrapper label label-city" id="filter-city" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Город"><?php echo !empty($filter_city) ? $filter_city : 'Город' ?></span>
        <span data-arrow></span>
      </div>
      <?php if (!empty($cities_names)) { ?>
        <div class="option-radio__select" data-select>
          <ul>
            <?php foreach ($cities_names as $city) { ?>
              <li>
                <label>
                  <span><?php echo $city ?></span>
                  <input type="radio" name="option-radio-city" value="<?php echo $city ?>" data-name="<?php echo $city ?>" id="" <?php echo $filter_city === $city ? 'checked' : '' ?> data-input-visible />
                  <span></span>
                </label>
              </li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
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
                  <input type="checkbox" name="option-checkbox-rooms[]" value="<?php echo  $name ?>" <?php echo in_array($name, $filter_rooms_array) ? 'checked' : '' ?> data-input-visible>
                  <span></span>
                </label>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    <?php } ?>
    <div class="label-option-radio-wrapper label label-area" id="filter-area" data-checked data-slider-box>
      <div class="option-checkbox">
        <span class="option-checkbox__label" data-checked-view data-default-value="Площадь"><?php echo !empty($filter_area) ? $filter_area : 'Площадь' ?></span>
        <span data-arrow></span>
      </div>
      <div class="option-checkbox__select select-area" data-select>
        <div class="select-area__top">
          <div class="select-area__title">
            <label>
              <span>Площадь</span>
              <input type="number" placeholder="от" value="<?php echo !empty($filter_area_ot) ? $filter_area_ot : 1  ?>" min="1" max="<?php echo $max_area ?>" data-input-visible data-area-from-view />
            </label>
            <span>—</span>
            <label>
              <input type="number" placeholder="до" value="<?php echo !empty($filter_area_do) ? $filter_area_do : $max_area  ?>" min="1" max="<?php echo $max_area ?>" data-input-visible data-area-to-view />
              <span>м²</span>
            </label>
          </div>

          <div class="select-area__wrapper-label">
            <div class="slider">
              <div class="progress" data-range-progress-area></div>
            </div>
            <div class="range-input">
              <input id="area-from" type="range" class="range-min" min="1" max="<?php echo $max_area ?>" value="<?php echo !empty($filter_area_ot) ? $filter_area_ot : 1  ?>" step="1" name="option-select-area-from" data-input-visible data-area-from />
              <input id="area-to" type="range" class="range-min" min="1" max="<?php echo $max_area ?>" value="<?php echo !empty($filter_area_do) ? $filter_area_do : $max_area  ?>" step="1" name="option-select-area-to" data-input-visible data-area-to />
            </div>
          </div>
        </div>
        <div class="select-area__button">
          <button class="button" type="button" data-button-reset><span>Сбросить</span></button>
        </div>
        <div class="select-area__button">
          <button class="button" type="button" data-button-success><span>Применить</span></button>
        </div>
      </div>
    </div>
    <div class="label-option-checkbox-wrapper label label-price" id="filter-price" data-checked data-slider-box>
      <div class="option-checkbox">
        <span class="option-checkbox__label" data-checked-view data-default-value="Цена"><?php echo !empty($filter_price) ? $filter_price_view : 'Цена'  ?> </span>
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
          <div class="select-price__title">
            <label>
              <span>Цена</span>
              <input type="number" placeholder="от" value="<?php echo !empty($filter_price_ot) ? $filter_price_ot : 1 ?>" min="1" max="<?php echo !empty($max_price) ? $max_price : 1  ?>" data-input-visible data-price-from-view />
            </label>
            <span>—</span>
            <label>
              <input type="number" placeholder="до" value="<?php echo !empty($filter_price_do) ? $filter_price_do : $max_price ?>" min="1" max="<?php echo !empty($max_price) ? $max_price : 1  ?>" data-input-visible data-price-to-view />
              <span>₽</span>
            </label>
          </div>
          <div class="select-area__wrapper-label">
            <div class="slider">
              <div class="progress" data-range-progress-price></div>
            </div>
            <div class="range-input">
              <input id="price-from" type="range" class="range-min" min="1" max="<?php echo !empty($max_price) ? $max_price : 1  ?>" value="<?php echo !empty($filter_price_ot) ? $filter_price_ot : 1 ?>" step="1" name="option-select-price-from" data-input-visible data-price-from />
              <input id="price-to" type="range" class="range-min" min="1" max="<?php echo !empty($max_price) ? $max_price : 1  ?>" value="<?php echo !empty($filter_price_do) ? $filter_price_do : $max_price ?>" step="1" name="option-select-price-to" data-input-visible data-price-to />
            </div>
          </div>
        </div>
        <div class="select-price__button">
          <button class="button" type="button" data-button-reset><span>Сбросить</span></button>
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
    <a class="button" href="<?php echo $link_page_map ?>">
      <img src="<?php bloginfo('template_url'); ?>/assets/images/yamap/map.svg" width="16" height="16" alt="">
      <span>Показать карту</span>
    </a>
  </div>
</form>