<?php

namespace Login\ServiceProvider\Domain;

use Doctrine\ORM\EntityManager;
use Login\Request\LoginRequestFactory;
use Login\Service\Reader\UserReader;
use Login\Service\Security\LoginBridge;
use Login\Service\Security\LoginBridgeProxy;
use Login\Service\Security\LoginFailureHandler;
use Login\Service\Security\UserLoginProvider;
use Login\Service\Security\UserLoginProviderWithCaptcha;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;

class LoginServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $this->registerFactory($app);
        $this->registerBridge($app);
        $this->registerProvider($app);
        $this->registerHandler($app);
    }

    /**
     * @param Container $app
     */
    private function registerBridge(Container $app)
    {
        $app['login.user.provider.bridge.proxy'] = function ($app) {
            return new LoginBridgeProxy(function () use ($app) {
                return $app['login.user.provider.bridge'];
            });
        };

        $app['login.user.provider.bridge'] = function ($app) {
            return new LoginBridge(
                $app['login.request.login.factory'],
                $app['request_stack'],
                $app['login.service.login.provider']
            );
        };
    }

    /**
     * @param Container $app
     */
    private function registerProvider(Container $app)
    {
        $app['login.service.reader.user'] = function ($app) {

            /** @var EntityManager $em */
            $em = $app['orm.em'];

            return new UserReader($em->getRepository(UserReader::getEntityClass()));
        };

        $app['login.service.login.provider.default'] = function ($app) {
            return new UserLoginProvider($app['login.service.reader.user']);
        };

        $app['login.service.login.provider'] = function ($app) {
            return new UserLoginProviderWithCaptcha(
                $app['login.service.security.captcha.manager'],
                $app['login.service.login.provider.default']
            );
        };
    }

    /**
     * @param Container $app
     */
    private function registerHandler(Container $app)
    {
        $app['security.authentication.failure_handler.secure'] = function ($app) {

            $inner = new DefaultAuthenticationFailureHandler(
                $app,
                $app['security.http_utils'],
                array(),
                $app['logger']
            );

            return new LoginFailureHandler(
                $app['login.service.security.blacklist.manager'],
                $inner,
                $app['login.request.login.factory']
            );
        };
    }

    /**
     * @param Container $app
     */
    private function registerFactory(Container $app)
    {
        $app['login.request.login.factory'] = function () {
            return new LoginRequestFactory();
        };
    }
}
