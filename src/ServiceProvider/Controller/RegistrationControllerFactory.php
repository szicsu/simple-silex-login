<?php

namespace Login\ServiceProvider\Controller;

use Login\Controller\RegistrationController;
use Login\Form\Factory\RegistrationFormFactory;
use Login\Service\RegistrationService;

class RegistrationControllerFactory extends AbstractControllerFactory
{
    public function __invoke() : RegistrationController
    {
        return new RegistrationController(
            $this->getRenderService(),
            $this->getRouterService(),
            $this->getFormFactory(),
            $this->getRegistrationService()
        );
    }

    private function getFormFactory() : RegistrationFormFactory
    {
        return $this->getApp()['login.form.factory.registration'];
    }

    private function getRegistrationService(): RegistrationService
    {
        return $this->getApp()['login.service.registration'];
    }
}
