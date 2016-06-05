<?php

namespace Login\ServiceProvider\Controller;

use Login\Controller\HomeController;

class HomeControllerFactory extends AbstractControllerFactory
{
    public function __invoke() : HomeController
    {
        return new HomeController(
            $this->getRenderService(),
            $this->getRouterService()
        );
    }
}
