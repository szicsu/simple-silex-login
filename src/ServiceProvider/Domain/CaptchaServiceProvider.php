<?php

namespace Login\ServiceProvider\Domain;

use Login\Service\Security\Captcha\CaptchaManager;
use Login\Service\Security\Captcha\Generator\NumericCaptchaGenerator;
use Login\Service\Security\Captcha\Storage\FlashCaptchaStorage;
use Login\Service\Security\Captcha\Util\CaptchaHelper;
use Login\Service\Security\Captcha\Util\CaptchaRandomGenerator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CaptchaServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $this->registerUtil($app);
        $this->registerGenerator($app);
        $this->registerStorage($app);
        $this->registerManager($app);
    }

    private function registerUtil(Container $app)
    {
        $app['login.service.security.captcha.util.random.generator'] = function ($app) {
            return new CaptchaRandomGenerator(
                $app['login.service.security.captcha.util.random.generator.min'],
                $app['login.service.security.captcha.util.random.generator.max']
            );
        };

        $app['login.service.security.captcha.util.random.generator.min'] = 1;
        $app['login.service.security.captcha.util.random.generator.max'] = 500;

        $app['login.service.security.captcha.util.helper'] = function ($app) {
            return new CaptchaHelper(
                $app['login.service.security.captcha.manager'],
                $app['login.request.login.factory']
            );
        };
    }

    private function registerGenerator(Container $app)
    {
        $app['login.service.security.captcha.generator.numeric'] = function ($app) {
            return new NumericCaptchaGenerator($app['login.service.security.captcha.util.random.generator']);
        };
    }

    private function registerStorage(Container $app)
    {
        $app['login.service.security.captcha.storage.flash'] = function ($app) {

            /** @var Session $session */
            $session = $app['session'];

            return new FlashCaptchaStorage($session->getFlashBag());
        };
    }

    private function registerManager(Container $app)
    {
        $app['login.service.security.captcha.manager'] = function ($app) {
            return new CaptchaManager(
                $app['login.service.security.blacklist.manager'],
                $app['login.service.security.captcha.generator.numeric'],
                $app['login.service.security.captcha.storage.flash']
            );
        };
    }
}
