<?php
$update_page = $args['update_page'];
$min_price = $rags['min_price'];
$finishing = $args['finishing'];
$min_price_meter = $args['min_price_meter'];

$all_finishing = implode(', ', $finishing);
?>

<p class="agent-order__date">Информация обновлена <?php echo $update_page ?></p>
<?php if (!empty($min_price)) { ?>
    <h2 class="agent-order__price title--xl title--product-agent">от <?php echo number_format(round(floatval($min_price)), 0, '.', ' ') ?> ₽</h2>
    <div class="agent-order__label">Хорошая цена!</div>
<?php } ?>
<div class="agent-order__info">
    <?php if (!empty($all_finishing)) { ?>
        <div class="agent-order__price-one-metr agent-price-one-mert">
            <span class="agent-conditions__title">Отделка</span>
            <span class="agent-conditions__space"></span>
            <span class="agent-conditions__price"><?php echo $all_finishing ?></span>
        </div>
    <?php  } ?>
    <?php if (!empty($min_price_meter)) { ?>
        <div class="agent-order__price-one-metr agent-price-one-mert-price">
            <span class="agent-conditions__title">Цена за метр</span>
            <span class="agent-conditions__space"></span>
            <span class="agent-conditions__price">от <?php echo number_format(round(floatval($min_price_meter)), 0, '.', ' ') ?> ₽/м² </span>
        </div>
    <?php } ?>
</div>