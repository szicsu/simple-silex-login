default: help

TEXT_FORMAT_BOLD=`tput bold`
TEXT_FORMAT_NORMAL=`tput sgr0`

CMD_PHP = /usr/bin/env php

PATH_VENDOR = ./vendor
PATH_SRC = ./src

cs-fix:
	$(CMD_PHP) $(PATH_VENDOR)/bin/php-cs-fixer -vvv fix  $(PATH_SRC)

test: 
	$(CMD_PHP) $(PATH_VENDOR)/bin/phpunit

help:
	@echo "$(TEXT_FORMAT_BOLD)test$(TEXT_FORMAT_NORMAL)          - run tests"
	@echo "$(TEXT_FORMAT_BOLD)cs-fix$(TEXT_FORMAT_NORMAL)          - use coding standard"
