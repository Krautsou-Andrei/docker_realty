<form class="filter-main__form filter-form" action="" data-filter-form method="get">
  <div class="labels-wrapper">
    <div class="labels-wrapper__top ">
      <div class="labels-wrapper__city">
        <label>
          <input type="radio" name="flter-mobile-catalog-city" value="Новороссийск" checked data-filter-novoross>
          <span>Новороссийск</span>
        </label>
        <label>
          <input id="krasnodar-mobile" type="radio" name="flter-mobile-catalog-city" value="Краснодар" data-filter-krasnodar>
          <span>Краснодар</span>
        </label>
      </div>
      <div class="labels-wrapper__type-realty">
        <?php get_template_part('template-page/components/radio-realty') ?>
      </div>
    </div>
    <div class="labels-wrapper__bottom">
      <div class="label-option-radio-wrapper label label-city-1" id="filter-city-1">
        <label class="label-option-radio-wrapper ">
          <input class="filter-form__input" type="radio" name="flter-mobile-catalog-city" value="Новороссийск" data-filter-novoross>
          <span>Новороссийск</span>
        </label>
      </div>
      <div class="label-option-radio-wrapper label label-city-2" id="filter-city-2">
        <label class="label-option-radio-wrapper ">
          <input id="krasnodar" class="filter-form__input" type="radio" name="flter-mobile-catalog-city" value="Краснодар" data-filter-krasnodar>
          <span>Краснодар</span>
        </label>
      </div>
      <div class="label-option-radio-wrapper label label-type" id="filter-flat" data-checked>
        <div class="option-radio">
          <span class="option-radio__label" data-checked-view data-default-value="Тип объекта">Квартиры</span>
          <span data-arrow></span>
        </div>
        <div class="option-radio__select" data-select>
          <ul>
            <li>
              <label>
                <span>Квартиры</span>
                <input type="radio" name="option-radio-type-build" value="Квартиры" id="" checked>
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>Дома</span>
                <input type="radio" name="option-radio-type-build" value="Дома" id="">
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>
      <div class="label-option-checkbox-wrapper label label-rooms" id="filter-rooms" data-checked>
        <div class="option-checkbox">
          <span class="option-checkbox__label" data-checked-view data-default-value="Комнаты">Комнаты</span>
          <span data-arrow></span>
        </div>
        <div class="option-checkbox__select" data-select>
          <ul>
            <li>
              <label>
                <span>1-комн.</span>
                <input type="checkbox" name="option-checkbox-rooms" value="1-комн." checked>
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>2-комн.</span>
                <input type="checkbox" name="option-checkbox-rooms" value="2-комн." id="">
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>
      <div class="label-option-checkbox-wrapper label label-district" id="filter-flat-district" data-checked>
        <div class="option-checkbox">
          <span class="option-checkbox__label" data-checked-view data-default-value="Район">Район</span>
          <span data-arrow></span>
        </div>
        <div class="option-checkbox__select" data-select>
          <ul>
            <li>
              <label>
                <span>Центральный</span>
                <input type="checkbox" name="option-checkbox-district" value="Центральный" id="" checked>
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>Приморский</span>
                <input type="checkbox" name="option-checkbox-district" value="Приморский" id="">
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>
      <div class="label-option-radio-wrapper label label-area" id="filter-area" data-checked>
        <div class="option-radio">
          <span class="option-radio__label" data-checked-view data-default-value="Площадь">Площадь</span>
          <span data-arrow></span>
        </div>
        <div class="option-radio__select" data-select>
          <ul>
            <li>
              <label>
                <span>18-35</span>
                <input type="radio" name="option-radio-area" value="18-35" id="" checked>
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>35-55</span>
                <input type="radio" name="option-radio-area" value="35-55" id="">
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>
      <div class="label-price-wrapper label label-price">
        <label>
          <span>Цена</span>
          <input type="text" placeholder="от">
        </label><span>—</span>
        <label>
          <input type="text" placeholder="до">
          <span>₽</span>
        </label>
      </div>
      <div class="filter-form__button filter-button">
        <button class="button" type="submit">
          <img src="<?php bloginfo('template_url'); ?>/assets/images/search_outline.svg" width="16" height="16" alt="">
          <span>Найти</span>
        </button>
      </div>
      <label id="near-sea" class="check-box label-checkbox">
        <!-- <input type="checkbox" value="value-1">
        <span>Рядом с морем</span> -->
      </label>
    </div>
  </div>
</form>
