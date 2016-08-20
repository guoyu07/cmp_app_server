#!/bin/bash

echo start php-fpm locally on port 9000 inside docker
sh start_fpm_docker_local.sh &

#wait for the fpm boot-up
sleep 2

ps aux |grep php

#run the server
cd /app_root/ && php cmp_app_server_docker.php 
