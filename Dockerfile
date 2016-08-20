FROM cmptech/auto_cmp_php_docker_server

Maintainer Wanjo Chan ( http://github.com/wanjochan/ )

# use my php.ini
ADD php.ini /etc/php7/

# the script to launch cmp_root_controller after launch php-fpm inside docker container
ADD start_cmp_server_docker_local.sh /root/

# the php-fpm launch script to run inside the docker container
ADD php-fpm-docker-local.conf /root/
ADD start_fpm_docker_local.sh /root/

# use my app_root/
ADD app_root /app_root/

