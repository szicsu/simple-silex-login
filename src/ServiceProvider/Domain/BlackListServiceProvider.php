<?php

namespace Login\ServiceProvider\Domain;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\BlackListManager;
use Login\Service\Security\BlackList\Driver\MemcachedDriver;
use Login\Service\Security\BlackList\Extractor\EmailKeyExtractor;
use Login\Service\Security\BlackList\Extractor\IpKeyExtractor;
use Login\Service\Security\BlackList\Extractor\StatKeyExtractor;
use Login\Service\Security\BlackList\Extractor\TimeKeyExtractor;
use Login\Service\Security\BlackList\Storage\BlackListStatStorage;
use Login\Service\Security\BlackList\Storage\BlackListStorage;
use Login\Service\Security\BlackList\Util\IpLevelUtil;
use Login\Service\Util\MemcachedFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class BlackListServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $this->registerConfig($app);
        $this->registerDriver($app);
        $this->registerExtractor($app);
        $this->registerStorage($app);
        $this->registerManager($app);
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

    private function registerExtractor(Container $app)
    {
        $app['login.service.security.blacklist.extractor.email'] = function ($app) {
            return new EmailKeyExtractor();
        };
        $app['login.service.security.blacklist.extractor.ip'] = function ($app) {
            return new IpKeyExtractor();
        };
        $app['login.service.security.blacklist.extractor.stat'] = function ($app) {
            return new StatKeyExtractor(
                $app['login.service.security.blacklist.config'],
                $app['login.service.security.blacklist.extractor.time']
            );
        };
        $app['login.service.security.blacklist.extractor.time'] = function ($app) {
            return new TimeKeyExtractor();
        };
    }

    private function registerStorage(Container $app)
    {
        $app['login.service.security.blacklist.storage.stat'] = function ($app) {
            return new BlackListStatStorage(
                $app['login.service.security.blacklist.config'],
                $app['login.service.security.blacklist.driver.memcached'],
                $app['login.service.security.blacklist.extractor.stat']
            );
        };

        $app['login.service.security.blacklist.storage'] = function ($app) {
            return new BlackListStorage(
                $app['login.service.security.blacklist.config'],
                $app['login.service.security.blacklist.driver.memcached'],
                $app['login.service.security.blacklist.extractor.email'],
                $app['login.service.security.blacklist.extractor.ip'],
                $app['login.service.security.blacklist.storage.stat']
            );
        };
    }

    private function registerConfig(Container $app)
    {
        $app['login.service.security.blacklist.config'] = function ($app) {
            $conf = $app['login.blacklist.config'];

            return new BlackListConfig(
                $conf['keyTTL'],
                $conf['limitForIpMap'],
                $conf['limitSameUser'],
                $conf['statWindowSize']
            );
        };

        $app['login.blacklist.config'] = array(
            'keyTTL' => 3600,
            'limitSameUser' => 3,
            'limitForIpMap' => array(
                IpLevelUtil::LEVEL_4 => 3,
                IpLevelUtil::LEVEL_3 => 500,
                IpLevelUtil::LEVEL_2 => 1000,
            ),
            'statWindowSize' => 300,
        );
    }

    private function registerManager(Container $app)
    {
        $app['login.service.security.blacklist.manager'] = function ($app) {
            return new BlackListManager($app['login.service.security.blacklist.storage']);
        };
    }
}
