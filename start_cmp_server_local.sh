#!/bin/bash

echo make PATH helping find the built php-fpm
export PATH="~/opt/php7/bin:~/opt/php7/sbin:$PATH"

echo start fpm locally at 9000

killall php-fpm
bash start_fpm_local.sh

echo start cmp-server at 9501
cd app_root/
php cmp_app_server.php 

