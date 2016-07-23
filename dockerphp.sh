#!/bin/bash

#docker run -v `pwd`:/root/ -w /root/ -ti cmptech/auto_alpine_php7_runtime_with_swoole php $1 $2 $3 $4 $5 $6 $7 $8 $9
# TODO all other params?
#docker run -v `pwd`:/root/ -w /root/ -ti cmptech/auto_alpine_php7_runtime_with_swoole_latest php $*
#docker run -v `pwd`:/root/ -w /root/ -ti cmptech/auto_alpine_php7_runtime_with_swoole php $1 $2 $3 $4 $5 $6 $7 $8 $9
dt=`date +%Y%m%d%H%M%S`
docker run --name $dt --net=host --privileged=true -v `pwd`:/root/ -w /root/ -ti cmptech/auto_alpine_php7_runtime_with_swoole_latest php $*
