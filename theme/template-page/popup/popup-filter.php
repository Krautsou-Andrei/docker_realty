<?php require_once get_template_directory() . '/inc/enums/categories_name.php';
require_once get_template_directory() . '/inc/enums/categories_id.php';
require_once get_template_directory() . '/inc/lib/get_params_filter_catalog.php';

$search_params = get_params_filter_catalog();

$filter_city = $search_params->filter_city;

$search_param_city = $search_params->search_param_city;

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

$rooms_names = $search_params->rooms_names;

$args_categories_area = array(
  'hide_empty' => false,
  'parent' => CATEGORIES_ID::AREA,
);

$categories_area = get_categories($args_categories_area);
$max_area = 0;

foreach ($categories_area as $area) {
  if (intval($area->name) > $max_area) {
    $max_area = intval($area->name);
  }
}
?>

<div class="popup" data-popup="popup-filter" data-close-overlay>
  <div class="popup__wrapper" data-close-overlay>
    <div class="popup__content">
      <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
      <div class="popup__body">
        <div class="popup__title">
          <h2 class="title--popup">Фильтры</h2>
        </div>
        <form action="/wp-content/themes/realty/inc/lib/filter-new-building.php?>" method="get" class="filert-mobile-form" data-filter-form>
          <input hidden type="radio" name="type" value="novostrojki" data-name="Дома" id="" checked />
          <input hidden type="text" name="select-price" value="<?php echo $filter_price ?>" id="" checked data-select-price />
          <input hidden type="text" name="select-area" value="<?php echo $filter_area ?>" id="" checked data-select-area />
          <div class="labels-wrapper">
            <div class="label-city-wrapper" id="filter-city" data-checked>
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
                      <span>Краснадар</span>
                      <input type="radio" name="option-radio-city" value="Краснодар" data-name="Краснодар" id="" <?php echo $filter_city === '2301' ? 'checked' : '' ?> data-input-visible />
                      <span></span>
                    </label>
                  </li>
                </ul>
              </div>
            </div>
            <div class="label-option-wrapper">
              <div class="label-option-radio-wrapper label-option__one" id="filter-flat" data-checked>
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
              <div class="label-option-checkbox-wrapper label-option__two" data-filter-rooms data-checked>
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
            </div>
            <div class="select-area__top">
              <span class="select-area__title">Общая, от <span class="view-slider-area" data-area-from-view><?php echo !empty($filter_area_ot) ? $filter_area_ot : 1  ?></span> до <span class="view-slider-area" data-area-to-view><?php echo !empty($filter_area_do) ? $filter_area_do : $max_area  ?></span> м²</span>
              <div class="select-area__wrapper-label">
                <div class="slider">
                  <div class="progress" data-range-progress></div>
                </div>
                <div class="range-input">
                  <input id="area-from" type="range" class="range-min" min="1" max="<?php echo $max_area ?>" value="<?php echo !empty($filter_area_ot) ? $filter_area_ot : 1  ?>" step="1" name="option-select-area-from" data-input-visible />
                  <input id="area-to" type="range" class="range-min" min="1" max="<?php echo $max_area ?>" value="<?php echo !empty($filter_area_do) ? $filter_area_do : $max_area  ?>" step="1" name="option-select-area-to" data-input-visible />
                </div>
              </div>
            </div>
            <!-- <div class="label-area-wrapper">
              <label>
                <span>Площадь</span>
                <input type="number" name="option-select-area-from" placeholder="от" id="area-from" min="0" data-input-visible value="<?php echo !empty($filter_area_ot) ? $filter_area_ot : ''  ?>">
              </label><span>—</span>
              <label>
                <input type="number" name="option-select-area-to" placeholder="до" id="area-to" min="1" data-input-visible value="<?php echo !empty($filter_area_do) ? $filter_area_do : ''  ?>">
                <span>м²</span>
              </label>
            </div> -->
            <div class="label-price-wrapper">
              <label>
                <span>Цена</span>
                <input type="number" name="option-select-price-from" placeholder="от" id="price-from" min="0" data-input-visible value="<?php echo !empty($filter_price_ot) ? $filter_price_ot : ''  ?>">
              </label><span>—</span>
              <label>
                <input type="number" name="option-select-price-to" placeholder="до" id="price-to" min="1" data-input-visible value="<?php echo !empty($filter_price_do) ? $filter_price_do : ''  ?>">
                <span>₽</span>
              </label>
            </div>
          </div>

          <div class="filert-mobile-form__button filter-button">
            <button class="button" type="submit">
              <span>Найти</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>