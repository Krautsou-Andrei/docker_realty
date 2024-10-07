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

$crb_gk_is_house = carbon_get_post_meta($id_page_gk, 'crb_gk_is_house');

if (empty($floor_apartaments)) {
    $floor_apartaments = get_apartaments_on_floor($map_apartaments, $current_liter);
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

                <?php if (!empty($map_apartaments[$current_liter]['area'])) { ?>
                    <div class="gk-filter-area">
                        <div class="gk-filter-area__title">Общая площадь</div>
                        <div class="gk-filter-area__wrapper">
                            <?php foreach ($map_apartaments[$current_liter]['area'] as $area) { ?>
                                <label>
                                    <input type="checkbox" name="gk-apartament-area" value="<?php echo intval($area['name']) ?>" data-form-table-input <?php echo in_array($area['name'], $categories_area_checked) ? 'checked' : '' ?> />
                                    <span><?php echo intval($area['name']) ?> м2</span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                <?php  } ?>

            </form>
        </section>
    <?php } ?>
</div>
<div class="product__more" data-container-table>
    <section class="gk-table">
        <?php if (!empty($literal && !$crb_gk_is_house)) { ?>

            <form class="gk-table__tab tab-gk" action="#" data-form-table-liter>
                <?php
                foreach ($literal as $index => $liter) {
                ?>
                    <label class="tab-gk__label">
                        <input hidden type="radio" name="gk-liter" value="<?php echo $liter; ?>" <?php if ($liter == $current_liter) {
                                                                                                        echo 'checked';
                                                                                                    } ?> data-form-table-input />
                        <span>Литера <?php echo $liter; ?></span>
                    </label>
                <?php
                }
                ?>
            </form>

        <?php } ?>


        <?php if (!empty($crb_gk_plan)) { ?>
            <div class="gk-plan">
                <button type="button" data-type="popup-plan"><span data-type="popup-plan">Схема литеров</span></button>
            </div>
        <?php } ?>

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
                        <div>Этаж</div>
                        <?php foreach ($map_apartaments[$current_liter]['floors'] as $key => $floor) {
                            if (count($floor) !== 0) {
                        ?>
                                <div><?php echo $key ?></div>
                        <?php }
                        } ?>
                    </div>
                    <div class="gk-schema__wrapper">
                        <div class="gk-schema__line">
                            <div class="gk-schema-row">
                                <div class="gk-schema__block">
                                    <div class="gk-schema__service"></div>
                                    <?php foreach ($map_apartaments[$current_liter]['floors'] as $floor) {
                                        if (!empty($floor)) {
                                            $current_floor = $floor;

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
                                                    <a href="<?php echo !empty($link) ? $link : '#'; ?>" <?php echo !empty($link) ? '' : 'style="pointer-events: none; cursor: default;"' ?>>
                                                        <div class="gk-schema-apartaments__room <?php echo !empty($link) ? 'active' : ''  ?> ">
                                                            <?php echo intval($apartment['rooms']) ? $apartment['rooms'] : mb_substr($apartment['rooms'], 0, 1); ?>
                                                        </div>
                                                    </a>

                                                <?php } ?>
                                            </div>
                                    <?php }
                                    } ?>
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