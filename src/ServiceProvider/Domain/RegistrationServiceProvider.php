<?php

namespace Login\ServiceProvider\Domain;

use Login\Form\Factory\RegistrationFormFactory;
use Login\Service\Persister\UserPersister;
use Login\Service\RegistrationService;
use Login\Service\Transformer\RegistrationRequestToUserTransformer;
use Login\Validator\UniqueEmailValidator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RegistrationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['login.form.factory.registration'] = function ($app) {
            return new RegistrationFormFactory($app['form.factory']);
        };

        $app['login.service.persister.user'] = function ($app) {
            return new UserPersister($app['orm.em']);
        };

        $app['login.service.transformer.registration2user'] = function ($app) {
            return new RegistrationRequestToUserTransformer($app['security.encoder_factory']);
        };

        $app['login.service.registration'] = function ($app) {
            return new RegistrationService(
                $app['login.service.transformer.registration2user'],
                $app['login.service.persister.user'],
                $app['validator']
            );
        };

        $app['login.service.validator.unique.email'] = function ($app) {
            return new UniqueEmailValidator($app['login.service.reader.user']);
        };
    }
}
