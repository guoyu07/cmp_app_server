#!/bin/bash

# start the php-fpm inside the docker...

#ln -s /usr/sbin/php-fpm7 /usr/sbin/php-fpm

echo start php-fpm locally on port 9000
sh start_fpm_docker_local.sh &

sleep 2

ps aux |grep php

echo start cmp swoole app server locally
echo OSX:
echo pkill -USR2 -o php-fpm
php cmp_app_server.php 
#tail -f _logs/php-fpm.log
