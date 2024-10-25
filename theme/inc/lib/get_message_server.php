<?php
function get_message_server($message, $is_error = false)
{
    $to = 'andreysv2006@yandex.by';
    $subject = !empty($is_error) ? 'Полученные данные  с ошибкой' : 'Полученные данные успешны';
    $message = (!empty($is_error) ? 'Ошибка ' : 'Успех') . $message;

    wp_mail($to, $subject, $message, []);
}
