# TODO
# fpm port
# swoole port

echo start php-fpm locally on port 9000
sh start_fpm_local.sh &

sleep 1

echo http://localhost:9501

echo start cmp swoole app server locally

echo OSX:
echo pkill -USR2 -o php-fpm

php cmp_app_server.php 

#tail -f _logs/php-fpm.log
