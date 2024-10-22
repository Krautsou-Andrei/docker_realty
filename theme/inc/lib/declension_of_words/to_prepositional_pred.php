<?php
function to_prepositional_pred($str)
{
    // Обработка специальных окончаний
    $replacements = [
        'Новороссийск' => 'Новороссийске',
        'Москва' => 'Москве',
        'Сочи' => 'Сочи',
        'Петербург' => 'Петербурге',
        'Краснодар' => 'Краснодаре',
    ];

    // Проверяем, есть ли слово в списке замен
    if (array_key_exists($str, $replacements)) {
        return $replacements[$str];
    }

    // Общие правила для предложного падежа
    if (mb_substr($str, -2) === 'ск') {
        return $str . 'е';
    }

    // Если слово заканчивается на "а", "я" или "о", добавляем "е"
    if (in_array(mb_substr($str, -1), ['а', 'я', 'о'])) {
        return mb_substr($str, 0, -1, 'UTF-8') . 'е';
    }

    if (mb_substr($str, -1) === 'ь') {
        return mb_substr($str, 0, -1, 'UTF-8') . 'и';
    }

    if (in_array(mb_substr($str, -1), ['й', 'и'])) {
        return $str . 'е';
    }

    return $str . 'е';
}
