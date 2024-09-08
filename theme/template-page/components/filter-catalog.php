<form action="" class="filter-catalog__form form-filter-catalog" method='get'>
  <div class="form-filter-catalog__list">
    <div class="label-option-radio-wrapper label label-type" id="filter-flat" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Тип объекта">Тип объекта</span>
        <span data-arrow></span>
      </div>
      <div class="option-radio__select" data-select>
        <ul>
          <li>
            <label>
              <span>Квартиры</span>
              <input type="radio" name="option-radio-type-build" value="Квартиры" id="">
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
    <div class="label-option-radio-wrapper label label-city" id="filter-city" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Город">Город</span>
        <span data-arrow></span>
      </div>
      <div class="option-radio__select" data-select>
        <ul>
          <li>
            <label>
              <span>Новороссийск</span>
              <input type="radio" name="option-radio-type-build" value="Новороссийск" id="">
              <span></span>
            </label>
          </li>
          <li>
            <label>
              <span>Краснодар</span>
              <input type="radio" name="option-radio-type-build" value="Краснодар" id="">
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
              <input type="checkbox" name="option-checkbox-district" value="Центральный" id="">
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
              <input type="checkbox" name="option-checkbox-rooms" value="1-комн.">
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
    <div class="label-option-radio-wrapper label label-area" id="filter-area" data-checked>
      <div class="option-checkbox">
        <span class="option-checkbox__label" data-checked-view data-default-value="Площадь">Площадь</span>
        <span data-arrow></span>
      </div>
      <div class="option-checkbox__select select-area" data-select>
        <div class="select-area__top">
          <span class="select-area__title">Общая, м²</span>
          <div class="select-area__wrapper-label">
            <label>
              <input type="number" name="option-select-area" placeholder="от" id="area-from">
            </label>
            <label>
              <input type="number" name="option-select-area" placeholder="до" id="area-to">
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
        <span class="option-checkbox__label" data-checked-view data-default-value="Цена">Цена</span>
        <span data-arrow></span>
      </div>
      <div class="option-checkbox__select select-area" data-select>
        <div class="select-price__top">
          <div class="select-price__title">
            <label class="switch">
              <input type="checkbox" id="check-price" checked>
              <span class="slider"></span>
            </label>
            <span id="all-area">За всю площадь</span>
            <span id="metr-area">За квадрат</span>
          </div>
          <div class="select-price__wrapper-label">
            <label>
              <input type="number" name="option-select-price" placeholder="от" id="price-from">
              <span>₽</span>
            </label>
            <label>
              <input type="number" name="option-select-price" placeholder="до" id="price-to">
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
