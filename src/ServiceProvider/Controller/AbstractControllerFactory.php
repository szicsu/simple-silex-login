<?php

declare (strict_types = 1);

namespace Login\ServiceProvider\Controller;

use Login\Application;
use Login\Service\Util\RendererServiceInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;

abstract class AbstractControllerFactory
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function getApp() : Application
    {
        return $this->app;
    }

    protected function getRenderService() : RendererServiceInterface
    {
        return $this->app['login.service.util.renderer'];
    }

    protected function getRouterService() : UrlGenerator
    {
        return $this->app['url_generator'];
    }
}
