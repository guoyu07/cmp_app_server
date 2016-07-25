#!/bin/bash

echo start php-fpm locally on port 9000
sh start_fpm_docker_local.sh &

sleep 2

ps aux |grep php

php cmp_app_server_docker.php 
