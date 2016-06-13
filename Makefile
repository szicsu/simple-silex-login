default: help

TEXT_FORMAT_BOLD=`tput bold`
TEXT_FORMAT_NORMAL=`tput sgr0`

CMD_PHP = /usr/bin/env php
CMD_DOCKER = /usr/bin/env docker

PATH_VENDOR = ./vendor
PATH_SRC = ./src
PATH_TESTS = ./tests

cs-fix: cs-fix-src cs-fix-tests

cs-fix-src:
	$(CMD_PHP) $(PATH_VENDOR)/bin/php-cs-fixer -vvv fix  $(PATH_SRC)
cs-fix-tests:
	$(CMD_PHP) $(PATH_VENDOR)/bin/php-cs-fixer -vvv fix  $(PATH_TESTS)
test: 
	$(CMD_PHP) $(PATH_VENDOR)/bin/phpunit

docker-build:
	$(CMD_DOCKER) build -t simple-silex-login .
docker-console:
	$(CMD_DOCKER) run -p 8080:80 -it -v $(shell pwd):/srv/simple-silex-login simple-silex-login

help:
	@echo "$(TEXT_FORMAT_BOLD)test$(TEXT_FORMAT_NORMAL)				- run tests"
	@echo "$(TEXT_FORMAT_BOLD)cs-fix$(TEXT_FORMAT_NORMAL)			- use coding standard"
	@echo "$(TEXT_FORMAT_BOLD)docker-build$(TEXT_FORMAT_NORMAL)		- Build the Docker container"
	@echo "$(TEXT_FORMAT_BOLD)docker-console$(TEXT_FORMAT_NORMAL)	- Run the Docker container and run services and run bash for debug"
