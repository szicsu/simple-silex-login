FROM debian:jessie
MAINTAINER Tamas Szijarto <szijarto.tamas.developer@gmail.com>

ENV BUILD_VERSION 1

# install system req
RUN apt-get update -y && apt-get install -y wget ca-certificates curl make

#install nginx and memcahced
RUN apt-get install -y nginx-light memcached


# install PHP
RUN mkdir -p /etc/apt/sources.list.d && \
	echo "deb http://packages.dotdeb.org jessie all" > /etc/apt/sources.list.d/dotdeb.list && \
	wget https://www.dotdeb.org/dotdeb.gpg && apt-key add dotdeb.gpg && \
    apt-get update -y && \
	apt-get install -y php-cli php-common php-intl  php-opcache php7.0-sqlite3 php7.0-memcached  php7.0-fpm && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/*

# setup PHP
RUN echo " \n\
short_open_tag = Off \n\
error_reporting = E_ALL \n\
display_errors = Off \n\
display_startup_errors = Off \n\
log_errors = On \n\
track_errors = On \n\
html_errors = On \n\
opcache.memory_consumption=128 \n\
opcache.interned_strings_buffer=8 \n\
opcache.max_accelerated_files=4000 \n\
opcache.enable_cli=0 \n\
date.timezone='Europe/Budapest' \n\
session.name=SESSIONID \n\
session.cookie_httponly=1 \n\
expose_php = Off \n\
\n\
; Workaround: Segmentation fault gc_possible_root (ref=0x7fffdb7513f0) at /usr/src/builddir/Zend/zend_gc.c:262 \n\
zend.enable_gc=Off\n\
; https://github.com/igbinary/igbinary/issues/60 \n\
memcached.serializer=php \n\
" > /etc/php/7.0/fpm/conf.d/99-login.ini

# install composer + phpunit
RUN	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer global require "phpunit/phpunit=@stable" && \
	ln -s  /root/.composer/vendor/bin/phpunit /usr/local/bin/phpunit


RUN rm -f /etc/nginx/sites-enabled/default && \
    echo "\n\
    \n\
    server{ \n\
        listen 80; \n\
        root  /srv/simple-silex-login/web; \n\
        location / { \n\
            fastcgi_pass unix:/run/php/php7.0-fpm.sock; \n\
            fastcgi_param SCRIPT_FILENAME   "\$document_root/index.php";  \n\
            fastcgi_param PHP_ADMIN_VALUE   "error_log=syslog";  \n\
            include fastcgi_params; \n\
            include proxy_params; \n\
        } \n\
    } \n\
" > /etc/nginx/sites-enabled/login

EXPOSE 80

VOLUME [ "/srv/simple-silex-login" ]

ADD "bin/server-start-in-docker.sh" "/usr/local/bin/server-start-in-docker.sh"
RUN chmod +x "/usr/local/bin/server-start-in-docker.sh"

ENTRYPOINT ["/usr/local/bin/server-start-in-docker.sh"]
