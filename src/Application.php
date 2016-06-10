<?php

namespace Login;

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Login\ServiceProvider\ControllerProvider;
use Login\ServiceProvider\Domain\BlackListServiceProvider;
use Login\ServiceProvider\Domain\CaptchaServiceProvider;
use Login\ServiceProvider\Domain\LoginServiceProvider;
use Login\ServiceProvider\Domain\RegistrationServiceProvider;
use Login\ServiceProvider\UtilServiceProvider;
use Monolog\Handler\SyslogHandler;
use Silex\Application as SilexApplication;
use Silex\Application\MonologTrait;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;
use Silex\ControllerCollection;
use Silex\Provider;

/**
 * Application for Login.
 */
class Application extends SilexApplication
{
    use MonologTrait;
    use UrlGeneratorTrait;
    use TwigTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        //TODO - move configuratuion
        umask(0000);
        date_default_timezone_set('Europe/Budapest');

        $this->registerSilexServices();
        $this->registerLoginApplicationServices();
    }

    /**
     * Called after the app init.
     */
    public function finalizeInit()
    {
        $this->mount('', $this['login.controller.provider']);
    }

    private function registerSilexServices()
    {
        $this->registerMonologService();

        $this->register(new Provider\ServiceControllerServiceProvider());
        $this->register(new Provider\RoutingServiceProvider());
        $this->register(new Provider\HttpFragmentServiceProvider());
        $this->register(new Provider\FormServiceProvider());
        $this->register(new Provider\DoctrineServiceProvider());
        $this->register(new Provider\TranslationServiceProvider());
        $this->register(new Provider\CsrfServiceProvider());
        $this->register(new Provider\ValidatorServiceProvider());
        $this->register(new Provider\SecurityServiceProvider());
        $this->register(new Provider\SessionServiceProvider());
        $this->register(new Provider\MonologServiceProvider());

        $this->register(new DoctrineOrmServiceProvider());

        $this->registerTwigService();

        $this->configureDebugMode();
    }

    private function registerLoginApplicationServices()
    {
        $this->register(new UtilServiceProvider());
        $this->register(new ControllerProvider(function (ControllerCollection $controllers) {
            require CONFIG_DIR.'/routing.php';
        }));
        $this->register(new RegistrationServiceProvider());
        $this->register(new LoginServiceProvider());
        $this->register(new BlackListServiceProvider());
        $this->register(new CaptchaServiceProvider());
    }

    private function registerMonologService()
    {
        $this->register(new Provider\MonologServiceProvider(), array(
            'monolog.ident' => 'Login',
            'monolog.facility' => LOG_USER,
        ));

        $this['monolog.handler'] = $this->factory(function ($app) {
            return new SyslogHandler($app['monolog.ident'], $app['monolog.facility'], $app['monolog.level']);
        });
    }

    private function registerTwigService()
    {
        $this->register(new Provider\TwigServiceProvider(), array(
            'twig.path' => SRC_DIR.'/Resources/views',
            'twig.options' => array(
                'cache' => CACHE_DIR.'/twig',
            ),
        ));
    }

    private function configureDebugMode()
    {
        if ($this['debug']) {
            ini_set('display_errors', 'on');
            $this->registerWebProfilerService();
        } else {
            ini_set('display_errors', 'off');
        }
    }

    private function registerWebProfilerService()
    {
        $this->register(new Provider\WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => CACHE_DIR.'/profiler',
            'profiler.mount_prefix' => '/_profiler',
        ));
    }
}
