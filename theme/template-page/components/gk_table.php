<?php

$literal = $args['literal'];
$categories_area = $args['categories_area'];
$map_apartaments = $args['map_apartaments'];
$crb_gk_plan = $args['crb_gk_plan'];

?>

<section class="gk-table">
    <?php if (!empty($literal)) { ?>
        <div class="gk-table__tab tab-gk">
            <?php
            foreach ($literal as $index => $liter) {
            ?>
                <label class="tab-gk__label">
                    <input hidden type="radio" name="gk-liter" <?php if ($index === 0) {
                                                                    echo 'checked';
                                                                } ?> />
                    <span>Литера <?php echo $liter; ?></span>
                </label>
            <?php
            }
            ?>
        </div>
    <?php } ?>


    <?php if (!empty($crb_gk_plan)) { ?>
        <div class="gk-plan">
            <button type="button" data-type="popup-plan"><span data-type="popup-plan">Схема литеров</span></button>
        </div>
    <?php } ?>

    <?php if (!empty($map_apartaments)) { ?>
        <div class="gk-schema">
            <div class="gk-schema-floor">
                <div>Этаж</div>
                <?php foreach ($map_apartaments[1] as $key => $floor) { ?>
                    <div><?php echo $key ?></div>
                <?php } ?>
            </div>
            <div class="gk-schema__wrapper">
                <div class="gk-schema__line">
                    <div class="gk-schema-row">
                        <div class="gk-schema__block">
                            <div class="gk-schema__service"></div>
                            <?php foreach ($map_apartaments[1] as $floor) { ?>
                                <div class="gk-schema-apartaments">
                                    <?php foreach ($floor as $apartament) { ?>
                                        <a href="<?php echo get_permalink($apartament->id_post) ?>"> <div class="gk-schema-apartaments__room active"><?php echo intval($apartament->rooms) ? $apartament->rooms :  mb_substr($apartament->rooms, 0, 1) ?></div></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</section>