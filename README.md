# cmp app server

## Local Mode

```
start_cmp_server_local.sh
```

## Docker Mode

based on github/cmptech/auto_cmp_php_docker_server

```
start_cmp_server_docker.sh
```

## After server launch

act like normal web build

## Testers

# LINKS

* https://github.com/swoole/framework/tree/master/examples

# CHN: 中文说明
适合用于制作api server
* 原理：用 swoole-http-server 做http服务器，把动态请求转发给php-fpm处理，其它允许的静态文件则直接处理
* 支持docker直接运行，便于快速开发甚至生产环境部署
* 目标并不是要取代nginx，而是想成为一个容易快速部署的纯php自治的运行环境，而且利用swoole的众多优点，焕发php新生

