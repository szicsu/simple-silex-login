<?php

namespace Login\ServiceProvider\Domain;

use Doctrine\ORM\EntityManager;
use Login\Request\LoginRequestFactory;
use Login\Service\Reader\UserReader;
use Login\Service\Security\LoginBridge;
use Login\Service\Security\UserLoginProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LoginServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['login.request.login.factory'] = function () {
            return new LoginRequestFactory();
        };

        $app['login.user.provider.bridge'] = function ($app) {
            return new LoginBridge(
                $app['login.request.login.factory'],
                $app['request_stack'],
                $app['login.service.login.provider.default'] //TODO - change for real
            );
        };

        $app['login.service.reader.user'] = function ($app) {

            /** @var EntityManager $em */
            $em = $app['orm.em'];

            return new UserReader($em->getRepository(UserReader::getEntityClass()));
        };

        $app['login.service.login.provider.default'] = function ($app) {
            return new UserLoginProvider($app['login.service.reader.user']);
        };
    }
}
