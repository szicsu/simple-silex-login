<?php

declare (strict_types = 1);

namespace Login\Controller;

use Login\Service\Util\RendererServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SecurityController extends AbstractController
{
    /**
     * @var \Closure
     */
    private $lastErrorGuesser;

    /**
     * @var \Closure
     */
    private $lastUsernameGuesser;

    /**
     * @param RendererServiceInterface $renderer
     * @param UrlGenerator             $router
     * @param \Closure                 $lastErrorGuesser
     * @param \Closure                 $lastUsernameGuesser
     */
    public function __construct(
        RendererServiceInterface $renderer,
        UrlGenerator $router,
        \Closure $lastErrorGuesser,
        \Closure $lastUsernameGuesser
    ) {
        parent::__construct($renderer, $router);
        $this->lastErrorGuesser = $lastErrorGuesser;
        $this->lastUsernameGuesser = $lastUsernameGuesser;
    }

    public function loginAction(Request $request) : Response
    {
        $lastErrorGuesser = $this->lastErrorGuesser;
        $lastUsernameGuesser = $this->lastUsernameGuesser;

        return $this->renderActionTemplate(array(
            'lastError' => $lastErrorGuesser($request),
            'lastUsername' => $lastUsernameGuesser(),
            'action' => $this->generateUrl('login_check'),
        ));
    }

    public function loginCheckAction()
    {
        throw new HttpException(500, 'Bad security configuration');
    }

    public function logoutAction()
    {
        throw new HttpException(500, 'Bad security configuration');
    }
}
