<?php

define('CONFIG_DIR', dirname(__DIR__).'/config');
define('SRC_DIR', dirname(__DIR__).'/src');
define('CACHE_DIR', dirname(__DIR__).'/cache');

if (true === is_readable($envSetup = CONFIG_DIR.'/envSetup.php')) {
    require $envSetup;
}

require dirname(__DIR__).'/vendor/autoload.php';

$app = new Login\Application(array(
    'debug' => (bool) getenv('SILEX_DEBUG'),
));

require CONFIG_DIR.'/_default.php';

if (
    ($env = getenv('SILEX_ENV') ?: false) &&
    is_readable($fileName = sprintf('%s/%s.php', CONFIG_DIR, $env))
) {
    require $fileName;
}

$app->finalizeInit();
