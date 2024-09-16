<?php

$gk = $args;

$gk_name =  $gk['crb_gk_name'];
$gk_plane =  $gk['crb_gk_plan'];
$gk_description = $gk['crb_gk_description'];
$gk_city = $gk['crb_gk_city'];
$gk_address = $gk['crb_gk_address'];
$crb_gk_permalink = $gk['crb_gk_permalink'];

$image_url = '';

if (!empty($gk_plane)) {
    $image_url = wp_get_attachment_image_src($gk_plane, 'full');
}

?>
<div class="catalog-gk__card">
    <li class="gk-card">
        <a href="<?php echo $crb_gk_permalink ?>">
            <div class="gk-card__image">
                <img src="<?php echo $image_url[0] ?>" alt="" width="380" height="195" />
            </div>
            <div class="gk-card__info info">
                <h3 class="info__title title--lg title--promo-slide"><? echo $gk_name ?></h3>
                <p class="info__description"><? echo preg_replace('/<p.*?>(.*?)<\/p>/', '$1', $gk_description)  ?></p>
                <p class="info__price">от 115 000 ₽ за м²</p>
                <p class="info__location"><span><? echo $gk_city ?></span><span>, <? echo $gk_address ?></span></p>
            </div>
        </a>
    </li>
</div>