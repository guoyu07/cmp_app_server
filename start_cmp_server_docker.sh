#!/bin/bash

# NOTES: for mac, suggest to run brew install php-swoole could be better

#dd=`pwd`
dd=$(cd `dirname $0`; pwd)
dt=`date +%Y%m%d%H%M%S`
echo $dt

port_outer=9501
port_inner=9501

#TODO make the port inner as param for launch...

#startcmd="docker run --name $dt --net=host -v $dd:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole_latest php cmp_app_server.php"
#startcmd="docker run --name $dt --net=host --privileged=true -p $port_outer:$port_inner -v $dd:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole_latest php cmp_app_server.php"
startcmd="docker run --name $dt -p $port_outer:$port_inner -v $dd:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole_latest php cmp_app_server.php"
echo $startcmd

dkid=`$startcmd`
echo dkid=$dkid

sleep 2
docker exec -ti $dkid ps

