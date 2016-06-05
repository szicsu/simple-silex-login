<?php

namespace Login;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Console for Login.
 */
class Console extends ConsoleApplication
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        parent::__construct('Login');

        set_time_limit(0);

        $this->app = $app;
        $this->setDispatcher($app['dispatcher']);
    }
}
