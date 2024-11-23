<?php
require_once('/var/www/html/wp-load.php');
require_once get_template_directory() . '/apatraments-json.php';
require_once get_template_directory() . '/inc/lib/get_message_server_telegram.php';

start(true);
get_message_server_telegram("Критическая ошибка", "Скрипт отановлен");
