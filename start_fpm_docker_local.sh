dt=`date +%Y%m%d%H%M%S`
echo $dt

dd=$(cd `dirname $0`; pwd)
echo ${dd}/webroot

php-fpm -d STARTDT=$dt -y php-fpm-docker-local.conf -p /app_root/webroot -F &

sleep 2
ps aux |grep $dt
