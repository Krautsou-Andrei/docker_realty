<div class="popup" data-popup="popup-filter" data-close-overlay>
  <div class="popup__wrapper" data-close-overlay>
    <div class="popup__content">
      <button class="popup__close button-close button--close" type="button" aria-label="Закрыть"></button>
      <div class="popup__body">
        <div class="popup__title">
          <h2 class="title--popup">Фильтры</h2>
        </div>
        <form action="" class="filert-mobile-form" data-filter-form>
          <div class="labels-wrapper">
            <div class="label-city-wrapper">
              <label>
                <input type="radio" name="flter-mobile-catalog-city" value="Новороссийск" checked>
                <span>Новороссийск</span>
              </label>
              <label>
                <input type="radio" name="flter-mobile-catalog-city" value="Краснодар">
                <span>Краснодар</span>
              </label>
            </div>
            <div class="label-option-wrapper">
              <div class="label-option-radio-wrapper label-option__one" id="filter-flat" data-checked>
                <div class="option-radio">
                  <span class="option-radio__label" data-checked-view data-default-value="Квартиры">Квартиры</span>
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
              <div class="label-option-checkbox-wrapper label-option__two" id="filter-rooms" data-checked>
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
            </div>
            <div class="label-option-wrapper">
              <div class="label-option-checkbox-wrapper label-option__one" id="filter-flat-district" data-checked>
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
              <div class="label-option-radio-wrapper label-option__two" id="filter-area" data-checked>
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
            </div>
            <div class="label-price-wrapper">
              <label>
                <span>Цена</span>
                <input type="text" placeholder="от">
              </label><span>—</span>
              <label>
                <input type="text" placeholder="до">
                <span>₽</span>
              </label>
            </div>
          </div>
          <!-- <label class="check-box label-checkbox">
            <input type="checkbox" value="value-1">
            <span>Рядом с морем</span>
          </label> -->

          <div class="filert-mobile-form__button filter-button">
            <button class="button" type="submit">
              <!-- <img src="<?php bloginfo('template_url'); ?>/assets/images/search_outline.svg" width="16" height="16" alt=""> -->
              <span>Сохранить</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
