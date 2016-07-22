#!/bin/bash
docker run -v `pwd`:/root/ -w /root/ -ti cmptech/auto_alpine_php7_runtime_with_swoole php $1 $2 $3 $4 $5 $6 $7 $8 $9
