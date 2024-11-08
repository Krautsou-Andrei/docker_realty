#!/bin/bash
set -e

# Ждем, пока файл wp-config.php будет создан
while [ ! -f /var/www/html/wp-config.php ]; do
    sleep 1
done

# Добавляем строку в начало файла, если она еще не добавлена
if ! grep -q "define('WP_MEMORY_LIMIT', '1512M');" /var/www/html/wp-config.php; then
    sed -i "2s/^/define('WP_MEMORY_LIMIT', '1512M');\n/" /var/www/html/wp-config.php
fi

# Завершаем выполнение скрипта, чтобы передать управление Docker
exec "$@"