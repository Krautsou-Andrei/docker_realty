<?php
require_once get_template_directory() . '/inc/lib/get_image_url.php';

$image = $args['image'];
$price = $args['price'];
$price_meter = $args['price_meter'];

$image_url = wp_get_attachment_image_src($image, 'full');
?>


<div class="popup-apartament__title">Квартира</div>
<div class="popup-apartament__info-wrapper">
    <div class="popup-apartament__image">
        <img src="<?php echo get_image_url($image_url) ?>" alt="" width="100" height="100" />
    </div>
    <div class="popup-apartament__description description">
        <? if (!empty($price)) { ?>
            <div class="description__price"><?php echo number_format(round(floatval($price)), 0, '.', ' ') ?> ₽</div>
        <?php } ?>
        <? if (!empty($price_meter)) { ?>
            <div class="description__price-meter"><?php echo number_format(round(floatval($price_meter)), 0, '.', ' ') ?> ₽/м²</div>
        <?php } ?>
    </div>
</div>
</div>