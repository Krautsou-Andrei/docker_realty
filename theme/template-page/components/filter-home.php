<form action="/wp-content/mlsfiles/realtyform.php" method="get" class="filter-main__form filter-form" data-filter-form>
  <div class="labels-wrapper">
    <div class="labels-wrapper__top">
      <div class="labels-wrapper__type-realty">
        <?php get_template_part('template-page/components/radio-realty') ?>
      </div>
      <a class="new-buildings-link" href="/novostrojki/" style="background: url(<?php bloginfo('template_url'); ?>/assets/images/arrow_triangle_right.svg) right  no-repeat;">Новостройки</a>
    </div>
    <div class="labels-wrapper__link-new-builds">
      <a href="/novostrojki/">
        <span class="option-radio__label">Новостройки</span>
      </a>
    </div>
    <div class="labels-wrapper__bottom">
      <div class="label-option-radio-wrapper label label-type" id="filter-flat-mobile" data-checked>
        <div class="option-radio">
          <span class="option-radio__label" data-checked-view data-default-value="Тип объекта">Квартиры</span>
          <span data-arrow></span>
        </div>
        <div class="option-radio__select" data-select>
          <ul>
            <li>
              <label>
                <span>Квартиры</span>
                <input type="radio" name="type" value="kvartiry" data-name="Квартиры" data-value id="" checked data-input-visible />
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>Дома</span>
                <input type="radio" name="type" value="doma" data-name="Дома" id="" data-input-visible />
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>Участки</span>
                <input type="radio" name="type" value="uchastki" data-name="Участки" id="" data-input-visible />
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>Коммерция</span>
                <input type="radio" name="type" value="kommercheskaya" data-name="Коммерция" id="" data-input-visible />
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>

      <div class="label-option-radio-wrapper label label-rooms" id="rooms" data-checked>
        <div class="option-radio">
          <span class="option-radio__label" data-checked-view data-default-value="Комнат">Комнат</span>
          <span data-arrow></span>
        </div>
        <div class="option-radio__select" data-select>
          <ul>
            <li><label><span>Студия</span><input type="radio" name="rooms" value="Студия" id="" data-input-visible><span></span></label></li>
            <li><label><span>1-комн.</span><input type="radio" name="rooms" value="1-комн." id="" data-input-visible><span></span></label></li>
            <li><label><span>2-комн.</span><input type="radio" name="rooms" value="2-комн." id="" data-input-visible><span></span></label></li>
            <li><label><span>3-комн.</span><input type="radio" name="rooms" value="3-комн." id="" data-input-visible><span></span></label></li>
            <li><label><span>4-комн.</span><input type="radio" name="rooms" value="4-комн." id="" data-input-visible><span></span></label></li>
          </ul>
        </div>
      </div>

      <div class="label-option-radio-wrapper label label-area" id="area" data-checked>
        <div class="option-radio">
          <span class="option-radio__label" data-checked-view data-default-value="Площадь">Площадь</span>
          <span data-arrow></span>
        </div>
        <div class="option-radio__select" data-select>
          <ul>
            <li>
              <label>
                <span>до 35</span>
                <input type="radio" name="area" value="до 35" id="" data-input-visible>
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>35-55</span>
                <input type="radio" name="area" value="35-55" id="" data-input-visible>
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>больше 55</span>
                <input type="radio" name="area" value="больше 55" id="" data-input-visible>
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>

      <div class="label-price-wrapper label label-price">
        <label>
          <span>Цена</span>
          <input type="text" name="price_from" placeholder="от" data-input-visible>
        </label><span>—</span>
        <label>
          <input type="text" name="price_to" placeholder="до" data-input-visible>
          <span>₽</span>
        </label>
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
                <input type="checkbox" name="option-checkbox-district" value="Центральный" id="" checked data-input-visible />
                <span></span>
              </label>
            </li>
            <li>
              <label>
                <span>Приморский</span>
                <input type="checkbox" name="option-checkbox-district" value="Приморский" id="" data-input-visible />
                <span></span>
              </label>
            </li>
          </ul>
        </div>
      </div>

      <div class="visually-hidden" id="city" data-checked aria-hidden="true">
        <div class="">
          <span class="" data-checked-view data-default-value="Город">Город</span>
          <span data-arrow></span>
        </div>
        <div class="" data-select>
          <ul>
            <li><label><span>Новороссийск</span><input type="radio" name="city" value="Новороссийск" checked data-selected-city-get data-input-visible><span></span></label></li>
          </ul>
        </div>
      </div>
      <div class="filter-form__button filter-button">
        <button class="button" type="submit">
          <img src="<?php bloginfo('template_url'); ?>/assets/images/search_outline.svg" width="16" height="16" alt="">
          <span>Найти</span>
        </button>
      </div>

      <?php
      $current_url = home_url($_SERVER['REQUEST_URI']);
      $favorites_url = trailingslashit($current_url) . 'favorites';
      ?>

      <div class="label-favorites">
        <a class="link-favorites" href="<?php echo esc_url($favorites_url); ?>"><span>Избранное</span></a>
      </div>


    </div>
  </div>

</form>