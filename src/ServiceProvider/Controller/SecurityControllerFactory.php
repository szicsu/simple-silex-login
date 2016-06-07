<?php

declare (strict_types = 1);

namespace Login\ServiceProvider\Controller;

use Login\Controller\SecurityController;

class SecurityControllerFactory  extends AbstractControllerFactory
{
    public function __invoke() : SecurityController
    {
        return new SecurityController(
            $this->getRenderService(),
            $this->getRouterService(),
            $this->getSecurityLastErrorGuesser(),
            $this->getSecurityLastUsernameGuesser()
        );
    }

    private function getSecurityLastErrorGuesser() : \Closure
    {
        return $this->getApp()['security.last_error'];
    }

    private function getSecurityLastUsernameGuesser() : \Closure
    {
        return function () {
            return $this->getApp()['session']->get('_security.last_username');
        };
    }
}
