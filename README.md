#Simple Silex Login Application

## Requirements
1. PHP 7.0+
2. Memcached

##Install

####Git clone
```
$ git clone https://github.com/szicsu/simple-silex-login.git
$ cd simple-silex-login
```
#### Composer install
```
$ composer install
```

#### Configure enviroment
```
$ mv config/envSetup.php.sample config/envSetup.php 
```

#### Init schema
```
$ php bin/console orm:schema-tool:create
```
 
#### Create host
```
# echo '127.0.0.1 login.local' >> /etc/hosts
```

#### Start server
```
$ php -S 0.0.0.0:8080 -t web/
```
#### Open in browser
http://login.local:8080/

#### Display stat
```
$ php bin/console login:black-list:stat:show
```


##Docker

#### Build container
```
$ make docker-build
```

#### Run container for debug
```
$ make docker-console
```
The command create database file in container and start all service ( nginx, php-fpm, memcached ) and finally start the bash for debug and console 

