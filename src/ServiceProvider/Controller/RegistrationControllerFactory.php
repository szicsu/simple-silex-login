<?php

namespace Login\ServiceProvider\Controller;

use Login\Controller\RegistrationController;
use Login\Form\Factory\RegistrationFormFactory;

class RegistrationControllerFactory extends AbstractControllerFactory
{
    public function __invoke() : RegistrationController
    {
        return new RegistrationController(
            $this->getRenderService(),
            $this->getRouterService(),
            $this->getFormFactory()
        );
    }

    private function getFormFactory() : RegistrationFormFactory
    {
        return $this->getApp()['login.form.factory.registration'];
    }
}
