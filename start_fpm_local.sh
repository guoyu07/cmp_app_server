#sudo ln /usr/local/sbin/php-fpm /opt/local/bin/php-fpm
#alias php-fpm=/usr/local/sbin/php-fpm

dt=`date +%Y%m%d%H%M%S`
echo dt=$dt

dd=$(cd `dirname $0`; pwd)
echo dd=${dd}

# NOTES:
# -p => the prefix, see php-fpm conf
#echo "php-fpm -d STARTDT=$dt -y php-fpm-local.conf -p ${dd}/app_root/webroot -F &"
#php-fpm -d STARTDT=$dt -y php-fpm-local.conf -p ${dd}/app_root/webroot -F &
#php-fpm -y php-fpm.conf -p . -F
php-fpm -d STARTDT=$dt -y php-fpm-local.conf -p ${dd}/app_root -F &

sleep 2

ps aux |grep $dt
