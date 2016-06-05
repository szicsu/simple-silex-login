<?php

declare (strict_types = 1);

namespace Login\Service\Util;

use Symfony\Component\HttpFoundation\Response;

interface RendererServiceInterface
{
    /**
     * Render the template with params to response.
     *
     * @param string        $view
     * @param array         $parameters
     * @param Response|null $response
     *
     * @return Response
     */
    public function render(string $view, array $parameters = array(), Response $response = null): Response;
}
