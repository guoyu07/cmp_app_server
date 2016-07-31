FROM cmptech/auto_cmp_php_docker_server

Maintainer Wanjo Chan ( http://github.com/wanjochan/ )

#RUN ln -sf /usr/sbin/php-fpm7 /usr/sbin/php-fpm

ADD php.ini /etc/php7/

ADD start_cmp_server_docker_local.sh /root/
ADD start_fpm_docker_local.sh /root/
ADD php-fpm-docker-local.conf /root/
#ADD PhpfpmClient.php /root/
#ADD cmp_app_server_docker.php /root/
#ADD cmp_app_server.php /root/
ADD app_root /app_root/

