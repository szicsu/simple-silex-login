<?php

namespace Login\ServiceProvider;

use Login\Application as LoginApplication;
use Login\ServiceProvider\Controller\HomeControllerFactory;
use Login\ServiceProvider\Controller\RegistrationControllerFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class ControllerProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    /**
     * @var \Closure
     */
    private $routingLoader;

    /**
     * @param \Closure $routingLoader
     */
    public function __construct(\Closure $routingLoader)
    {
        $this->routingLoader = $routingLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $loader = $this->routingLoader;
        $loader($controllers);

        return $controllers;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['login.controller.provider'] = $this;

        /* @var LoginApplication $pimple */
        $pimple['login.controller.home'] = new HomeControllerFactory($pimple);
        $pimple['login.controller.registration'] = new RegistrationControllerFactory($pimple);
    }
}
