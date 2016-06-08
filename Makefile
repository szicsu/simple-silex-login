default: help

TEXT_FORMAT_BOLD=`tput bold`
TEXT_FORMAT_NORMAL=`tput sgr0`

CMD_PHP = /usr/bin/env php

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

help:
	@echo "$(TEXT_FORMAT_BOLD)test$(TEXT_FORMAT_NORMAL)          - run tests"
	@echo "$(TEXT_FORMAT_BOLD)cs-fix$(TEXT_FORMAT_NORMAL)          - use coding standard"
