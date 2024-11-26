#!/bin/bash
set -e

# Ждем, пока файл wp-config.php будет создан
while [ ! -f /var/www/html/wp-config.php ]; do
    sleep 1
done

# Добавляем строку для WP_MEMORY_LIMIT, если она еще не добавлена
if ! grep -q "define('WP_MEMORY_LIMIT', '4052M');" /var/www/html/wp-config.php; then
    sed -i "2s/^/define('WP_MEMORY_LIMIT', '4052M');\n/" /var/www/html/wp-config.php
fi

# Добавляем строку для FS_METHOD, если она еще не добавлена
if ! grep -q "define('FS_METHOD', 'direct');" /var/www/html/wp-config.php; then
    sed -i "3s/^/define('FS_METHOD', 'direct');\n/" /var/www/html/wp-config.php
fi

# Добавляем строки для настройки Redis, если они еще не добавлены
if ! grep -q "define('WP_REDIS_HOST', 'redis');" /var/www/html/wp-config.php; then
    sed -i "4s/^/define('WP_REDIS_HOST', 'redis');\n/" /var/www/html/wp-config.php
fi

if ! grep -q "define('WP_REDIS_PORT', 6379);" /var/www/html/wp-config.php; then
    sed -i "5s/^/define('WP_REDIS_PORT', 6379);\n/" /var/www/html/wp-config.php
fi

if ! grep -q "define( 'WP_REDIS_DATABASE', 1 );" /var/www/html/wp-config.php; then
    sed -i "6s/^/define( 'WP_REDIS_DATABASE', 1 );\n/" /var/www/html/wp-config.php
fi

# Завершаем выполнение скрипта, чтобы передать управление Docker
exec "$@"