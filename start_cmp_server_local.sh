echo start php-fpm locally
sh start_fpm_local.sh &

echo start cmp swoole app server locally
php cmp_app_server.php
