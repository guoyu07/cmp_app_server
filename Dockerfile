FROM cmptech/auto_cmp_php_docker_server

Maintainer Wanjo Chan ( http://github.com/wanjochan/ )

#RUN ln -sf /usr/sbin/php-fpm7 /usr/sbin/php-fpm

COPY php.ini /etc/php7/
COPY start_cmp_server_docker_local.sh /root/
COPY start_fpm_docker_local.sh /root/
COPY php-fpm-docker-local.conf /root/
COPY cmp_app_server.php /root/
COPY PhpfpmClient.php /root/

