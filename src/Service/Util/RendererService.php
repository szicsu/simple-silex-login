<?php

declare (strict_types = 1);

namespace Login\Service\Util;

use Login\Application;
use Symfony\Component\HttpFoundation\Response;

class RendererService implements RendererServiceInterface
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $view, array $parameters = array(), Response $response = null): Response
    {
        return $this->app->render($view, $parameters, $response);
    }
}
