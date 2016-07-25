#!/bin/bash

# NOTES: for mac, suggest to run brew install php-swoole could be better
# TODO improve the script...  
# 1, add param handling
# 2, clean up

#dd=`pwd`
dd=$(cd `dirname $0`; pwd)
dt=`date +%Y%m%d%H%M%S`
echo $dt

# TODO make console param
port_outer=9502
port_inner=9501

#test
#docker run --name $dt -p $port_outer:$port_inner -v $dd:/root/ -w /root/ -ti cmptech/auto_cmp_php_docker_server sh

#startcmd="docker run --name $dt -p $port_outer:$port_inner -v $dd:/root/ -w /root/ -d cmptech/auto_cmp_php_docker_server sh start_cmp_server_docker_local.sh"
#OK...
#startcmd="docker run --name $dt -p $port_outer:$port_inner -v $dd/app_root/:/app_root/ -v $dd:/root/ -v $dd/php.ini:/etc/php7/php.ini -w /root/ -d cmptech/auto_cmp_php_docker_server sh start_cmp_server_docker_local.sh"
startcmd="docker run --name $dt -p $port_outer:$port_inner -v $dd/app_root/:/app_root/ -w /root/ -d cmptech/cmp_app_server sh start_cmp_server_docker_local.sh"
echo $startcmd

dkid=`$startcmd`
echo dkid=$dkid

sleep 2
docker exec -ti $dkid ps

