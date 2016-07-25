#!/bin/bash

dd=$(cd `dirname $0`; pwd) && docker run --name $(date +%Y%m%d%H%M%S) -p 9503:9501 -v $dd/app_root/:/app_root/ -w /root/ -ti cmptech/cmp_app_server:latest sh start_cmp_server_docker_local.sh

