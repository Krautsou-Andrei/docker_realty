<?php

require_once get_template_directory() . '/inc/lib/get_apartaments_on_floor.php';
require_once get_template_directory() . '/inc/lib/get_image_url.php';


$id_page_gk = $args['id_page_gk'];
$literal = $args['literal'];
$map_apartaments = $args['map_apartaments'];
$crb_gk_plan = $args['crb_gk_plan'];
$current_liter = isset($args['current_liter']) ? $args['current_liter'] : $literal[0];
$categories_rooms_checked = isset($args['categories_rooms_checked']) ? $args['categories_rooms_checked'] : [];
$categories_area_checked = isset($args['categories_area_checked']) ? $args['categories_area_checked'] : [];
$floor_apartaments = isset($args['floor_apartaments']) ? $args['floor_apartaments'] : [];
$map_houses = isset($args['map_houses']) ? $args['map_houses'] : [];
$product_plan = carbon_get_post_meta($id_page_gk, 'crb_gk_plan');
$image_plan_url = wp_get_attachment_image_src($product_plan[0], 'full');

$crb_gk_is_house = carbon_get_post_meta($id_page_gk, 'crb_gk_is_house');

if (empty($floor_apartaments)) {
    $floor_apartaments = get_apartaments_on_floor($map_apartaments, $current_liter);
}

$count = 0;
while (count($floor_apartaments) % 6 !== 0) {
    $floor_apartaments[] = $floor_apartaments[$count];
    $count++;
}

$max_floor = array_key_first($map_apartaments[$current_liter]['floors']);
$max_floors = [];

if (!empty($max_floor)) {
    $max_floors =  array_reverse(array_fill(0, $max_floor, ''), true);
}

?>
<div class="product__gk-filter">
    <?php if (!$crb_gk_is_house) { ?>
        <section class="page-gk-filter">
            <form action="#" class="page-gk-filter__form" data-form-table-apartamens>
                <?php if (!empty($map_apartaments[$current_liter]['rooms'])) { ?>
                    <div class="gk-filter-rooms">
                        <div class="gk-filter-rooms__title">Количество комнат</div>
                        <?php foreach ($map_apartaments[$current_liter]['rooms'] as $room) { ?>
                            <label>
                                <input type="checkbox" name="gk-apartament-rooms" value="<?php echo $room['name'] ?>" data-form-table-input <?php echo in_array($room['name'], $categories_rooms_checked) ? 'checked' : '' ?> />
                                <span><?php echo $room['name'] ?></span>
                            </label>
                        <?php } ?>
                    </div>
                <?php } ?>
            </form>
        </section>
    <?php } ?>
</div>
<div class="product__more" data-container-table>
    <section class="gk-table">
        <div class="gk-table__filter">
            <div class="gk-plan">
                <div class="gk-plan__image" data-type="popup-plan">
                    <img src="<?php echo get_image_url($image_plan_url) ?>" alt="" width="258" height="159" data-type="popup-plan" />
                </div>
            </div>
            <div class="tab-gk__wrapper">
                <?php if (!empty($literal && !$crb_gk_is_house)) { ?>
                    <div class="tab-gk__liter">
                        <div class="tab-gk__title">
                            <span>Корпуса</span>
                        </div>
                        <form class="gk-table__tab tab-gk" action="#" data-form-table-liter>
                            <?php
                            foreach ($literal as $index => $liter) {
                            ?>
                                <label class="tab-gk__label">
                                    <input hidden type="radio" name="gk-liter" value="<?php echo $liter; ?>" <?php if ($liter == $current_liter) {
                                                                                                                    echo 'checked';
                                                                                                                } ?> data-form-table-input />
                                    <span><?php echo $liter; ?></span>
                                </label>
                            <?php
                            }
                            ?>
                        </form>

                    </div>
                <?php } ?>
                <?php if (!empty($map_apartaments[$current_liter]['area'])) {

                    $first_value_area = intval(reset($map_apartaments[$current_liter]['area'])['name']);
                    $last_value_area = intval(end($map_apartaments[$current_liter]['area'])['name']);
                ?>
                    <form action="#" class="tab-gk__area" data-filter-slider-area>
                        <div class="tab-gk__title">
                            <span>Площадь, м² <span> от <span data-filter-from-view><?php echo !empty($categories_area_checked[0]) ? $categories_area_checked[0] : $first_value_area ?></span> до <span data-filter-to-view><?php echo !empty($categories_area_checked[1]) ? $categories_area_checked[1] : $last_value_area ?></span></span>
                            </span>
                        </div>
                        <div class="select-area__wrapper-label">
                            <div class="slider">
                                <div class="progress" data-range-progress-filter data-range-progress-area></div>
                            </div>
                            <div class="range-input">
                                <input id='area-from' type="range" class="range-min" min="<?php echo $first_value_area ?>" max="<?php echo $last_value_area ?>" value="<?php echo !empty($categories_area_checked[0]) ? $categories_area_checked[0] : $first_value_area ?>" step="1" name="option-select-area-from" data-input-visible data-filter-from data-form-table-input />
                                <input id='area-to' type="range" class="range-max" min="<?php echo $first_value_area ?>" max="<?php echo $last_value_area ?>" value="<?php echo !empty($categories_area_checked[1]) ? $categories_area_checked[1] : $last_value_area ?>" step="1" name="option-select-area-to" data-input-visible data-filter-to data-form-table-input />
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>

        </div>
        <?php if (!empty($map_apartaments) && !empty($map_apartaments[$current_liter]['floors'])) {
            if ($crb_gk_is_house) {
                if (!empty($map_houses)) { ?>
                    <ul class="gk-schema-house__wrapper">
                        <?php foreach ($map_houses as $house) {  ?>
                            <li class="gk-schema-house">
                                <a href="<?php echo get_permalink($house['post_id']) ?>">
                                    <div class="gk-schema-house__image">
                                        <?php if (!empty($house['image'])) {
                                            $image_url = wp_get_attachment_image_src($house['image'], 'full');
                                        ?>
                                            <img src="<?php echo get_image_url($image_url) ?>" alt="" width="335" height="220" />
                                        <?php  } ?>
                                    </div>
                                    <div class="gk-schema-house__info info">
                                        <?php if (!empty($house['area'])) { ?>
                                            <div class="info__area"><span>Площадь:</span><span><?php echo $house['area'] ?> м²</span></div>
                                        <?php } ?>
                                        <?php if (!empty($house['price'])) { ?>
                                            <div class="info__price"><span>Цена:</span><span><?php echo number_format(round($house['price']), 0, '.', ' ') ?> ₽</span></div>
                                        <?php } ?>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php

                }
            } else { ?>
                <div class="gk-schema">
                    <div class="gk-schema-floor">
                        <div></div>
                        <?php foreach ($max_floors as $key => $floor) { ?>
                            <div><?php echo $key + 1 ?></div>
                        <?php } ?>
                        <div>Этаж</div>
                    </div>
                    <div class="gk-schema__wrapper">
                        <div class="gk-schema__line">
                            <div class="gk-schema-row">
                                <div class="gk-schema__block">
                                    <div class="roof-wrapper">
                                        <div class="gk-schema-apartaments-roof">
                                            <?php foreach ($floor_apartaments as $apartment) { ?>
                                                <div class="gk-schema-apartaments__roof-room">
                                                    <div class=""></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <?php foreach ($max_floors as $floor_key => $sample) {
                                        $floor = $map_apartaments[$current_liter]['floors'][$floor_key + 1];
                                        $current_floor = [];
                                        if (!empty($floor)) {
                                            $current_floor = $floor;
                                        } else {
                                            $current_floor = array_fill(0, count($floor_apartaments), '0');
                                        }
                                    ?>
                                        <div class="gk-schema-apartaments">
                                            <?php foreach ($floor_apartaments as $apartment) {
                                                $link = '';
                                                if (in_array($apartment['rooms'], array_column($current_floor, 'rooms'))) {
                                                    foreach ($current_floor as $key => $item) {
                                                        if ($item['rooms'] == $apartment['rooms']) {
                                                            $link = get_permalink($item['id_post']);
                                                            unset($current_floor[$key]);
                                                            break;
                                                        }
                                                    }
                                                }

                                            ?>
                                                <a href="<?php echo !empty($link) ? $link : '#'; ?>" <?php echo !empty($link) ? '' : 'style="pointer-events: none; cursor: default;"' ?>
                                                    <?php echo !empty($link) ? 'data-info-apartament' : ''; ?>
                                                    <?php echo !empty($link) ? 'data-info-apartament-id=' . $item['id_post'] : ''; ?>>
                                                    <div class="gk-schema-apartaments__room <?php echo !empty($link) ? 'active' : ''  ?> ">
                                                        <?php echo intval($apartment['rooms']) ? $apartment['rooms'] : mb_substr($apartment['rooms'], 0, 1); ?>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="gk-schema__service"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="empty-filter"> Ничего не найдено</div>
        <?php } ?>
    </section>
</div>
<?php if (!empty($map_apartaments) && !$crb_gk_is_house) { ?>
    <div class="product__legend">
        <div class="legend">
            <div class="legend__room active"></div>
            <div class="">-</div>
            <div class="">Свободно</div>
        </div>
        <div class="legend">
            <div class="legend__room"></div>
            <div class="">-</div>
            <div class="">Продано</div>
        </div>
    </div>
<?php } ?>