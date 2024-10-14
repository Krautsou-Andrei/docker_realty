<?php

function send_telegram_form()
{
    $email = !empty($_POST['email']) ? $_POST['email'] : '';
    $phone = !empty($_POST['phone']) ? $_POST['phone'] : "";
    $name = !empty($_POST['name']) ? $_POST['name'] : "";
    $link = !empty($_POST['link']) ? $_POST['link'] : '';


    if (!isset($_SESSION['telegram_info_sent'])) {

        $message = '';

        $message = "<b>Перезвоните мне</b>\n";
        if (!empty($name)) {
            $message .= "<b>ФИО</b>: <code>$name\n</code>";
        }
        if (!empty($phone)) {
            $message .= "<b>Телефон</b>: <code>$phone\n</code>";
        }
        if (!empty($email)) {
            $message .= "<b>E-mail</b>: <code>$email\n</code>";
        }
        if (!empty($link)) {
            $message .= "<b>Ссылка</b>: <code>$link\n</code>";
        }

        $telegramToken = carbon_get_theme_option('crb_telegram_bot_order_token');
        $chatId = carbon_get_theme_option('crb_telegram_bot_order_chat_id');

        if (!empty($telegramToken) && !empty($chatId)) {
            $telegram = new \TelegramBot\Api\BotApi($telegramToken);
            $telegram->sendMessage($chatId, $message, 'HTML');
        }

        $_SESSION['telegram_info_sent'] = true;
    }

    $response = array(
        'link' => $link,
    );

    wp_send_json($response);

    wp_die();
}
add_action('wp_ajax_send_telegram_form', 'send_telegram_form');
add_action('wp_ajax_nopriv_send_telegram_form', 'send_telegram_form');
