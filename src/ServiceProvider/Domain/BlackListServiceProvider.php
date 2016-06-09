<?php

namespace Login\ServiceProvider\Domain;

use Login\Service\Security\BlackList\Driver\MemcachedDriver;
use Login\Service\Security\BlackList\Extractor\EmailKeyExtractor;
use Login\Service\Security\BlackList\Extractor\IpKeyExtractor;
use Login\Service\Util\MemcachedFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class BlackListServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $this->registerDriver($app);
        $this->registerExtractor($app);
    }

    /**
     * @param Container $app
     */
    private function registerDriver(Container $app)
    {
        $app['login.service.security.blacklist.driver.memcached'] = function ($app) {
            /** @var MemcachedFactory $factory */
            $factory = $app['login.service.util.memcached.factory'];

            $memcached = $factory->create(
                $app['login.service.security.blacklist.driver.memcached.servers'],
                'login.service.security.blacklist.driver.memcached',
                $app['login.service.security.blacklist.driver.memcached.options']
            );
            $namespace = $app['login.service.security.blacklist.driver.memcached.namespace'];

            return new MemcachedDriver($memcached, $namespace, $app['logger']);
        };

        $app['login.service.security.blacklist.driver.memcached.servers'] = array();
        $app['login.service.security.blacklist.driver.memcached.options'] = array();
        $app['login.service.security.blacklist.driver.memcached.namespace'] = 'loginBlackList';
    }

    private function registerExtractor($app)
    {
        $app['login.service.security.blacklist.extractor.email'] = function ($app) {
            return new EmailKeyExtractor();
        };
        $app['login.service.security.blacklist.extractor.ip'] = function ($app) {
            return new IpKeyExtractor();
        };
    }
}
