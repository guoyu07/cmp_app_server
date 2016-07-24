echo TODO this is test only, the start/stop/reload is not yet sync from ...you know...

#sudo ln /usr/local/sbin/php-fpm /opt/local/bin/php-fpm
#alias php-fpm=/usr/local/sbin/php-fpm

dt=`date +%Y%m%d%H%M%S`
echo $dt

dd=$(cd `dirname $0`; pwd)
echo ${dd}/webroot

# NOTES:
# -p => the prefix, see php-fpm conf
echo "php-fpm -d STARTDT=$dt -y php-fpm-docker-local.conf -p ${dd}/webroot -F &"
php-fpm -d STARTDT=$dt -y php-fpm-docker-local.conf -p ${dd}/webroot -F &
#php-fpm -y php-fpm.conf -p . -F

sleep 2
ps aux |grep $dt
