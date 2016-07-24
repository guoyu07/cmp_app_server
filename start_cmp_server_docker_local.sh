#!/bin/bash

ln -s /usr/sbin/php-fpm7 /usr/sbin/php-fpm

echo start php-fpm locally on port 9000
sh start_fpm_docker_local.sh &

sleep 2

ps aux |grep php

echo start cmp swoole app server locally
php cmp_app_server.php 
