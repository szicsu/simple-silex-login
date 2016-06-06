<?php

define('CONFIG_DIR', dirname(__DIR__).'/config');
define('SRC_DIR', dirname(__DIR__).'/src');
define('CACHE_DIR', dirname(__DIR__).'/cache');
define('DATA_DIR', dirname(__DIR__).'/data');

if (true === is_readable($envSetup = CONFIG_DIR.'/envSetup.php')) {
    require $envSetup;
}

$loader = require dirname(__DIR__).'/vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

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
