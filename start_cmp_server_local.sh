#!/bin/bash

echo start fpm locally at 9000

killall php-fpm
sh start_fpm_local.sh

echo start cmp-server at 9501
php cmp_app_server.php 

