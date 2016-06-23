#!/bin/bash
unset TMOUT

NGINX_VERSION='1.11.1'
PHP_VERSION='7.0.7'
cd /tmp
wget http://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz
tar xzf nginx-${NGINX_VERSION}.tar.gz
wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.38.tar.gz
tar xzf pcre-8.38.tar.gz
git clone https://github.com/FRiCKLE/ngx_cache_purge.git
git clone https://github.com/yaoweibin/ngx_http_substitutions_filter_module.git
cd ${OPENSHIFT_TMP_DIR}nginx-${NGINX_VERSION}
./configure \
--prefix=$OPENSHIFT_DATA_DIR \
--with-pcre=${OPENSHIFT_TMP_DIR}pcre-8.38 \
--with-pcre-jit \
--with-threads \
--with-http_realip_module \
--with-http_sub_module \
--with-http_stub_status_module \
--with-http_v2_module \
--with-http_ssl_module \
--with-http_gzip_static_module \
--with-http_auth_request_module \
--with-http_geoip_module \
--with-http_secure_link_module \
--without-mail_pop3_module
--without-mail_imap_module \
--without-mail_smtp_module \
--add-module=${OPENSHIFT_TMP_DIR}ngx_http_substitutions_filter_module \
--add-module=${OPENSHIFT_TMP_DIR}ngx_cache_purge
make -j4 && make install
cd /tmp
rm -rf *
wget -O libmcrypt-2.5.8.tar.gz http://downloads.sourceforge.net/mcrypt/libmcrypt-2.5.8.tar.gz?big_mirror=0
tar xzf libmcrypt-2.5.8.tar.gz
cd libmcrypt-2.5.8
./configure \
--prefix=${OPENSHIFT_DATA_DIR}usr/local
make -j4 && make install
cd libltdl
./configure \
--prefix=${OPENSHIFT_DATA_DIR}usr/local \
--enable-ltdl-install
make -j4 && make install
cd ../..
wget -O mhash-0.9.9.9.tar.gz http://downloads.sourceforge.net/mhash/mhash-0.9.9.9.tar.gz?big_mirror=0
tar zxvf mhash-0.9.9.9.tar.gz
cd mhash-0.9.9.9
./configure \
--prefix=${OPENSHIFT_DATA_DIR}usr/local
make -j4 && make install
cd ..
wget  --no-check-certificate -O re2c-0.13.7.5.tar.gz https://www.kooker.jp/re2c-0.13.7.5.tar.gz
tar xzf re2c-0.13.7.5.tar.gz
cd re2c-0.13.7.5
./configure \
--prefix=${OPENSHIFT_DATA_DIR}usr/local
make -j4 && make install
cd ..
wget -O mcrypt-2.6.8.tar.gz http://downloads.sourceforge.net/mcrypt/mcrypt-2.6.8.tar.gz?big_mirror=0
tar xzf mcrypt-2.6.8.tar.gz
cd mcrypt-2.6.8
export LDFLAGS="-L${OPENSHIFT_DATA_DIR}usr/local/lib -L/usr/lib"
export CFLAGS="-I${OPENSHIFT_DATA_DIR}usr/local/include -I/usr/include"
export LD_LIBRARY_PATH="/usr/lib/:${OPENSHIFT_DATA_DIR}usr/local/lib"
export PATH="/bin:/usr/bin:/usr/sbin:${OPENSHIFT_DATA_DIR}usr/local/bin:${OPENSHIFT_DATA_DIR}bin:${OPENSHIFT_DATA_DIR}sbin"
touch malloc.h
./configure \
--prefix=${OPENSHIFT_DATA_DIR}usr/local \
--with-libmcrypt-prefix=${OPENSHIFT_DATA_DIR}usr/local
make -j4 && make install
cd ..
wget -O php-${PHP_VERSION}.tar.gz http://us3.php.net/get/php-${PHP_VERSION}.tar.gz/from/this/mirror
tar xzf php-${PHP_VERSION}.tar.gz
cd php-${PHP_VERSION}
./configure \
--prefix=$OPENSHIFT_DATA_DIR \
--with-config-file-path=${OPENSHIFT_DATA_DIR}etc \
--with-layout=GNU \
--with-mcrypt=${OPENSHIFT_DATA_DIR}usr/local \
--with-pear \
--with-pgsql \
--with-mysqli \
--with-pdo-mysql \
--with-pdo-pgsql \
--enable-pdo \
--with-pdo-sqlite \
--with-sqlite3 \
--with-openssl \
--with-zlib-dir \
--with-iconv-dir \
--with-freetype-dir \
--with-jpeg-dir \
--with-png-dir \
--with-zlib \
--with-bz2 \
--with-libxml-dir \
--with-curl \
--with-gd \
--with-xsl \
--with-xmlrpc \
--with-mhash \
--with-gettext \
--with-readline \
--with-kerberos \
--with-pcre-regex \
--enable-json \
--enable-bcmath \
--enable-cli \
--enable-calendar \
--enable-wddx \
--enable-inline-optimization \
--enable-simplexml \
--enable-filter \
--enable-ftp \
--enable-tokenizer \
--enable-dom \
--enable-exif \
--enable-mbregex \
--enable-fpm \
--enable-mbstring \
--enable-gd-native-ttf \
--enable-xml \
--enable-xmlwriter \
--enable-xmlreader \
--enable-sockets \
--enable-zip \
--enable-intl \
--enable-maintainer-zts \
--enable-opcache \
--disable-phar \
--disable-debug \
--disable-fileinfo \
--disable-ipv6 \
--disable-rpath
make -j4 && make install
cp ${OPENSHIFT_TMP_DIR}php-${PHP_VERSION}/php.ini-development ${OPENSHIFT_DATA_DIR}etc/php.ini
cp ${OPENSHIFT_DATA_DIR}etc/php-fpm.conf.default ${OPENSHIFT_DATA_DIR}etc/php-fpm.conf
cp ${OPENSHIFT_DATA_DIR}etc/php-fpm.d/www.conf.default ${OPENSHIFT_DATA_DIR}etc/php-fpm.d/www.conf
mkdir ${OPENSHIFT_REPO_DIR}www
echo "<?php phpinfo();" > ${OPENSHIFT_REPO_DIR}www/info.php
sed -i "s/default_type  application\/octet-stream;/default_type  application\/octet-stream;\n    port_in_redirect off;\n    server_tokens off;/g;s/listen       80;/listen       ${OPENSHIFT_DIY_IP}:8080;/g;s/            root   html;/            root   \/var\/lib\/openshift\/${OPENSHIFT_APP_UUID}\/app-root\/runtime\/repo\/www;/g;s/index  index.html index.htm;/index  index.php index.html index.htm;/g;s/# deny access to .htaccess files, if Apache's document root/location ~ \\\.php\$ {\n            root           \/var\/lib\/openshift\/${OPENSHIFT_APP_UUID}\/app-root\/runtime\/repo\/www;\n            #fastcgi_pass   ${OPENSHIFT_DIY_IP}:9000;\n          fastcgi_pass  unix:\/dev\/shm\/php-fpm.sock;\n            fastcgi_index  index.php;\n            fastcgi_param   SCRIPT_FILENAME    \$document_root\$fastcgi_script_name;\n            fastcgi_param   SCRIPT_NAME        \$fastcgi_script_name;\n            include        fastcgi_params;\n        }\n\n        # deny access to .htaccess files, if Apache's document root/g" ${OPENSHIFT_DATA_DIR}conf/nginx.conf
sed -i "s/nginx\/\$nginx_version;/nginx;/g" ${OPENSHIFT_DATA_DIR}conf/fastcgi.conf
sed -i "s/user = nobody/;user = nobody/g;s/group = nobody/;group = nobody/g;s/listen = 127.0.0.1:9000/;listen = ${OPENSHIFT_DIY_IP}:9000\nlisten = \/dev\/shm\/php-fpm.sock/g;s/pm.max_children = 5/pm.max_children = 20/g;s/pm.start_servers = 2/pm.start_servers = 10/g;s/pm.min_spare_servers = 1/pm.min_spare_servers = 6/g;s/pm.max_spare_servers = 3/pm.max_spare_servers = 20/g" ${OPENSHIFT_DATA_DIR}etc/php-fpm.d/www.conf
sed -i "s/short_open_tag = Off/short_open_tag = On/g;s/upload_max_filesize = 2M/upload_max_filesize = 38M/g;s/max_file_uploads = 20/max_file_uploads = 38/g;s/post_max_size = 8M/post_max_size = 38M/g;s/max_input_time = 60/max_input_time = 300/g;s/default_socket_timeout = 60/default_socket_timeout = 300/g;s/max_execution_time = 30/max_execution_time = 180/g;s/;date.timezone =/date.timezone = Asia\/Taipei/g;s/disable_functions =/disable_functions = passthru,exec,system,chroot,chgrp,shell_exe,putenv/g;s/; End:/; End:\n\nzend_extension=opcache.so\nopcache.enable=1\nopcache.enable_cli=1\nopcache.file_cache=\/tmp\n/g" ${OPENSHIFT_DATA_DIR}etc/php.ini
cd /tmp
rm -rf *
cd ${OPENSHIFT_REPO_DIR}.openshift/action_hooks
rm -rf start
wget --no-check-certificate https://www.kooker.jp/start
chmod +x start
cd ${OPENSHIFT_REPO_DIR}.openshift/cron/minutely
rm -rf restart.sh
wget --no-check-certificate https://www.kooker.jp/restart.sh
chmod +x restart.sh
rm -rf delete_log.sh
wget --no-check-certificate https://www.kooker.jp/delete_log.sh
chmod +x delete_log.sh
cd ${OPENSHIFT_REPO_DIR}www
echo "<!DOCTYPE html><html><head><meta charset=\"utf-8\" /><title>Openshift Nginx-${NGINX_VERSION} + PHP${PHP_VERSION} 环境安装【编译】成功！</title><style>body {width: 35em;margin: 0 auto;font-family: Tahoma, Verdana, Arial, sans-serif;}.code{color: #f8f8f2;background:#272822;padding:1.2em 1.2em 0.8em;margin:1em 0;word-break: break-all; word-wrap:break-word;border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px;padding-top:0.8em;}</style></head><body><h1>Openshift Nginx + PHP 环境安装【编译】成功！</h1><p>这是使用 <a href=\"https://www.kooker.jp/\">Hi kooker Proxy</a> 提供的脚本安装【编译】 Nginx-${NGINX_VERSION} + PHP${PHP_VERSION} </p><p> 依赖于 <cite>OpenShift DIY 0.1 + Cron 1.4</cite><br />此脚本仅供参考测试，请勿直接用于生产环境，生产环境需自行优化。Nginx 配置文件在 <cite>app-root/data/conf</cite>，PHP7 配置文件在 app-root/data/etc，网站目录在 <cite>app-root/runtime/repo/www</cite>， 此脚本仅适用于首次安装【编译】！<br />不再直接编译 <cite>Memcache Redis GeoIP 扩展</cite>，需要请自行编译。<br />Nginx 升级请参考<cite> https://www.kooker.jp/nginx-update.sh</cite><br />PHP7升级请参考 <cite>https://www.kooker.jp/p7u.sh</cite><br />WordPress中文安装【已添加 Nginx 伪静态规则】请参考 <cite>https://www.kooker.jp/wp-cn.sh</cite></p><p class="code">cd /tmp<br />wget --no-check-certificate https://www.kooker.jp/p7.sh<br />chmod +x p7.sh<br />./p7.sh<br /></p><p><a href=\"https://www.kooker.jp/p7.sh\">脚本下载</a> | <a href=\"tz.php\">雅黑探针</a> | <a href=\"info.php\">phpinfo</a></p><p><em><a href=\"https://blog.kooker.jp/\">Hi kooker</a></em></p></body></html>" > ${OPENSHIFT_REPO_DIR}www/index.html
wget http://www.yahei.net/tz/tz.zip
unzip tz.zip
rm -rf tz.zip
sed -i "s/\$_SERVER\['REMOTE_ADDR'\];/\$_SERVER\['HTTP_X_FORWARDED_FOR'\];/g;s/\$_SERVER\[PHP_SELF\]/\$_SERVER\['PHP_SELF'\]/g;s/\$_SERVER\['PHP_SELF'\]/htmlentities(\$_SERVER\[‘PHP_SELF’\])/g;s/eregi(\"phpinfo\",\$disFuns)/preg_match(\"phpinfo\/i\",\$disFuns)/g;s/mcrypt_cbc/mcrypt_encrypt/g;s/mysql_/mysqli_/g" ${OPENSHIFT_REPO_DIR}www/tz.php
gear stop
gear start
