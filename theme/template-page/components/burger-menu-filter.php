<div class="burger-menu-filter">
  <div class="burger-menu-filter__city">
    <div class="label-option-radio-wrapper filter-city button--burger-filter" id="filter-city-mobile" data-checked>
      <div class="option-radio">
        <span class="option-radio__label" data-checked-view data-default-value="Новороссийск" data-selected-city>Новороссийск</span>
        <span data-arrow></span>
      </div>
      <div class="option-radio__select" data-select>
        <ul>
          <li>
            <label>
              <span>Новороссийск</span>
              <input type="radio" name="option-radio-city" value="Новороссийск" id="" checked />
              <span></span>
            </label>
          </li>
          <li>
            <label>
              <span>Краснодар</span>
              <input type="radio" name="option-radio-city" value="Краснодар" id="" />
              <span></span>
            </label>
          </li>
        </ul>
      </div>
    </div>
  </div>
    <?php
        $current_url = home_url($_SERVER['REQUEST_URI']);
        $domain = parse_url($current_url, PHP_URL_HOST);
        $favorites_url = trailingslashit( $domain ) . 'favorites';
    ?>
  <div class="burger-menu-filter__favorites">
    <a href="<?php echo esc_url( $favorites_url ); ?>" class="button button--favorites button--burger-filter"><span>избранное</span></a>
  </div>
</div>
