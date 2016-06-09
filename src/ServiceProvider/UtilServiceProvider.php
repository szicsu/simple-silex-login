<?php

namespace Login\ServiceProvider;

use Login\Service\Util\MemcachedFactory;
use Login\Service\Util\RendererService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UtilServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['login.service.util.renderer'] = function ($app) {
            return new RendererService($app);
        };

        $app['login.service.util.memcached.factory'] = function ($app) {
            return new MemcachedFactory($app['login.service.util.memcached.default.options']);
        };

        $app['login.service.util.memcached.default.options'] = array(
            \Memcached::OPT_LIBKETAMA_COMPATIBLE => true,
            \Memcached::OPT_DISTRIBUTION => \Memcached::DISTRIBUTION_CONSISTENT,
            \Memcached::OPT_POLL_TIMEOUT => 300000, // 300s
            \Memcached::OPT_RETRY_TIMEOUT => 60, //60s
            \Memcached::OPT_BINARY_PROTOCOL => TRUE
        );
    }
}
