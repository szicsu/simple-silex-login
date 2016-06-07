<?php

declare (strict_types = 1);

namespace Login\Controller;

use Login\Service\Util\RendererServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Abstract controller for project.
 */
abstract class AbstractController
{
    /**
     * @var RendererServiceInterface
     */
    private $renderer;

    /**
     * @var UrlGenerator
     */
    private $router;

    public function __construct(RendererServiceInterface $renderer, UrlGenerator $router)
    {
        $this->renderer = $renderer;
        $this->router = $router;
    }

    protected function render(string $templateName, array $params = array(), Response $response = null): Response
    {
        if (strpos('.', $templateName) === false) {
            $templateName .= '.html.twig';
        }

        return $this->renderer->render($templateName, $params, $response);
    }

    protected function renderJson(\Traversable $data, int $status = 200, array $headers = array()): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function renderActionTemplate(array $params = array(), Response $response = null) : Response
    {
        $back = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        $path = str_replace('Controller', '', substr($back['class'], strrpos($back['class'], '\\') + 1));
        $file = str_replace('Action', '', $back['function']);

        return $this->render($path.DIRECTORY_SEPARATOR.$file, $params, $response);
    }

    protected function generateUrl(string $routeName, array $parameters = array(),  int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH) : string
    {
        return $this->router->generate($routeName, $parameters, $referenceType);
    }

    protected function redirect(string $url, int $status = 302, array $headers = array()): RedirectResponse
    {
        return new RedirectResponse($url, $status, $headers);
    }
}
