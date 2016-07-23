#!/bin/bash

dd=$(cd `dirname $0`; pwd)
dt=`date +%Y%m%d%H%M%S`
echo $dt

#NOTES: using net=host to get the max performance...

#linux
#docker run -ti --net=host  -v `pwd`:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole_latest php test_swoole_server.php

#but failed for mac ...
#https://forums.docker.com/t/should-docker-run-net-host-work/14215/12
# macos work around
# link outer 8080 to inner 9501
#docker run -p 8080:9501 -v ${dd}:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole php test_swoole_server.php
#docker run -p 8080:9501 -v `pwd`:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole php test_swoole_server.php

dkid=$(docker run -p 8080:9501 -v `pwd`:/root/ -w /root/ -d cmptech/auto_alpine_php7_runtime_with_swoole_latest php test_swoole_server_9501.php)
echo dkid=$dkid
docker exec -ti $dkid ps

sleep 1

#old version of docker in mac:
#siege -c 10 -b -q -t 10s http://192.168.99.100:8080/
#siege -c 10 -b -q -t 10s http://127.0.0.1:8080/
siege -c 100 -b -q -t 10s http://127.0.0.1:8080/

#echo if ok now go on with
#echo wget https://github.com/swoole/swoole-src/raw/master/examples/proxy.php
