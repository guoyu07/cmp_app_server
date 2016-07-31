#!/bin/bash

# TODO exist if not in docker

echo start php-fpm locally on port 9000 in docker
sh start_fpm_docker_local.sh &

sleep 2

ps aux |grep php

cd /app_root/
php cmp_app_server_docker.php 
