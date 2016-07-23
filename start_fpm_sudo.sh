
dt=`date +%Y%m%d%H%M%S`
echo $dt

dd=$(cd `dirname $0`; pwd)
echo ${dd}/webroot

# NOTES:
# -p => the prefix, see php-fpm conf
php-fpm -d STARTDT=$dt -y php-fpm-sudo.conf -p ${dd}/webroot -F &
#php-fpm -y php-fpm.conf -p . -F

sleep 2
ps aux |grep $dt
