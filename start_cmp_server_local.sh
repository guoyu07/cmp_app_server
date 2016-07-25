#!/bin/bash

sh start_fpm_local.sh &

sleep 2

ps aux |grep php

php cmp_app_server.php 
