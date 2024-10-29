<?php

require_once get_template_directory() . '/vendor/autoload.php';

function get_message_server_telegram($type, $message_invite = '', $region = '', $gk = '')
{

    $message = '';

    $message = "<b>Статус $type</b>\n";
    if (!empty($message_invite)) {
        $message .= "<b>Сообщение</b>: <code>$message_invite\n</code>";
    }
    if (!empty($name)) {
        $message .= "<b>Регион</b>: <code>$region\n</code>";
    }
    if (!empty($gk)) {
        $message .= "<b>Жилой комплекс</b>: <code>$gk\n</code>";
    }

    // if (!empty($link)) {
    //     $message .= "<b>Ссылка</b>: <a href=\"$link\">$link</a>\n";
    // }

    $telegramToken = '8195375751:AAGMgbTLGX0Kicj1VQlNgHq5kmCWXD4IHx4';
    $chatId = '-1002324374984';

    if (!empty($telegramToken) && !empty($chatId)) {
        $telegram = new \TelegramBot\Api\BotApi($telegramToken);
        $telegram->sendMessage($chatId, $message, 'HTML');
    }
}
