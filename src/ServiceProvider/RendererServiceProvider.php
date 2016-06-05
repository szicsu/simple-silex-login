<?php

namespace Login\ServiceProvider;

use Login\Service\Util\RendererService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RendererServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['login.renderer.service'] = function ($app) {
            return new RendererService($app);
        };
    }
}
