#!/bin/sh

echo "<?php \n putenv('SILEX_DEBUG=true'); \n putenv('SILEX_ENV=docker'); \n putenv('DATA_SOURCE=/var/tmp/login.db');\n" > /srv/simple-silex-login/config/envSetup.php

cd /srv/simple-silex-login;
php bin/console  orm:schema-tool:update --force;
chown www-data /var/tmp/login.db;

/etc/init.d/memcached start && /etc/init.d/nginx start && service php7.0-fpm start && bash