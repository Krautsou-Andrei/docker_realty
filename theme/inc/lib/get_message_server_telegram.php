<?php

require_once get_template_directory() . '/vendor/autoload.php';

function get_message_server_telegram($type, $message_invite = '', $region = '', $gk = '')
{
    $current_url = home_url($_SERVER['REQUEST_URI']);
    $domain = parse_url($current_url, PHP_URL_HOST);

    $message = '';

    $message = "<b>Статус $type ' ' $domain</b>\n";
    if (!empty($message_invite)) {
        $message .= "<b>Сообщение</b>: <code>$message_invite\n</code>";
    }
    if (!empty($region)) {
        $message .= "<b>Регион</b>: <code>$region\n</code>";
    }
    if (!empty($gk)) {
        $message .= "<b>Жилой комплекс</b>: <code>$gk\n</code>";
    }

    $telegramToken = carbon_get_theme_option('crb_telegram_bot_server_token');
    $chatId = carbon_get_theme_option('crb_telegram_bot_server_chat_id');

    if (!empty($telegramToken) && !empty($chatId)) {
        $telegram = new \TelegramBot\Api\BotApi($telegramToken);
        $telegram->sendMessage($chatId, $message, 'HTML');
    }
}
