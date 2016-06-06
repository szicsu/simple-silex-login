<?php

namespace Login\ServiceProvider\Domain;

use Login\Form\Factory\RegistrationFormFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RegistrationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['login.form.factory.registration'] = function ($app) {
            return new RegistrationFormFactory($app['form.factory']);
        };
    }
}
