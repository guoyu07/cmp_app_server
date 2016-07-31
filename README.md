# cmp app server

In short, the "cmp-app-server" is a webserver built base on latest php +swoole extension.

* By using swoole_http_server to forward web request to php-fpm, we now have a full engine web engine in pure php
* It's dockerized, which means we can have a very flexible and scable application server deployment.
* CMP (cmpTech.inf) and many other PHP framework is tested and run fluently
* for more information, please refer to [CmpAppServer Wiki](https://github.com/cmptech/cmp_app_server/wiki)

## Docker Mode

```
sh start_cmp_server_docker.sh
```

## Local Mode

```
sh start_cmp_server_local.sh
```

# LINKS

* https://github.com/cmptech/cmp_app_server/wiki
* https://github.com/wanjochan/misctools/
* https://github.com/swoole/swoole-src/
* https://github.com/swoole/framework/tree/master/examples

# [TIPS] Besides the optional docker environment, it's supported to install php in non-root-user

```
wget --no-cache -q https://github.com/cmptech/cmp_app_server/raw/master/install-php-fpm-swoole-one-click.sh -O - | sh
```

# [TIPS] Run In One Line

```
docker pull cmptech/cmp_app_server && \
docker run --name $(date +%Y%m%d%H%M%S) -p 9502:9501 \
-v `pwd`/app_root/:/app_root/ \
-w /root/ -ti cmptech/cmp_app_server sh start_cmp_server_docker_local.sh
```

or

```
#!/bin/bash
slc_port=9555
echo please test at port ${slc_port}
dd=$(cd `dirname $0`; pwd) && docker run --name $(date +%Y%m%d%H%M%S) -p ${slc_port}:9501 -v $dd/webroot:/app_root/webroot/ -w /root/ -ti cmptech/cmp_app_server:latest sh start_cmp_server_docker_local.sh
```
