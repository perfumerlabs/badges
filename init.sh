#!/usr/bin/env bash

set -x \
&& rm -rf /etc/nginx \
&& rm -rf /etc/supervisor \
&& mkdir /run/php

set -x \
&& cp -r "/usr/share/container_config/nginx" /etc/nginx \
&& cp -r "/usr/share/container_config/supervisor" /etc/supervisor

sed -i "s/BADGES_HOST/$BADGES_HOST/g" /etc/nginx/sites/badges.conf

sed -i "s/error_log = \/var\/log\/php7.4-fpm.log/error_log = \/dev\/stdout/g" /etc/php/7.4/fpm/php-fpm.conf
sed -i "s/;error_log = syslog/error_log = \/dev\/stdout/g" /etc/php/7.4/fpm/php.ini
sed -i "s/;error_log = syslog/error_log = \/dev\/stdout/g" /etc/php/7.4/cli/php.ini
sed -i "s/log_errors = Off/log_errors = On/g" /etc/php/7.4/cli/php.ini
sed -i "s/log_errors = Off/log_errors = On/g" /etc/php/7.4/fpm/php.ini
sed -i "s/log_errors_max_len = 1024/log_errors_max_len = 0/g" /etc/php/7.4/cli/php.ini
sed -i "s/user = www-data/user = badges/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/group = www-data/group = badges/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/pm = dynamic/pm = static/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/pm.max_children = 5/pm.max_children = ${PHP_PM_MAX_CHILDREN}/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;pm.max_requests = 500/pm.max_requests = ${PHP_PM_MAX_REQUESTS}/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/listen.owner = www-data/listen.owner = badges/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/listen.group = www-data/listen.group = badges/g" /etc/php/7.4/fpm/pool.d/www.conf
sed -i "s/;catch_workers_output = yes/catch_workers_output = yes/g" /etc/php/7.4/fpm/pool.d/www.conf

sed -i "s/BADGES_HOST/$BADGES_HOST/g" /opt/badges/src/Gateway.php
sed -i "s/BADGES_LIFETIME/$BADGES_LIFETIME/g" /opt/badges/src/Resource/config/resources_shared.php
sed -i "s/MONGO_HOST/$MONGO_HOST/g" /opt/badges/src/Resource/config/resources_shared.php
sed -i "s/MONGO_PORT/$MONGO_PORT/g" /opt/badges/src/Resource/config/resources_shared.php
sed -i "s/MONGO_DATABASE/$MONGO_DATABASE/g" /opt/badges/src/Resource/config/resources_shared.php
sed -i "s/MONGO_COLLECTIONS/$MONGO_COLLECTIONS/g" /opt/badges/src/Resource/config/resources_shared.php
sed -i "s/MONGO_DATABASE/$MONGO_DATABASE/g" /opt/badges/src/Resource/config/services_shared.php

touch /node_status_inited
