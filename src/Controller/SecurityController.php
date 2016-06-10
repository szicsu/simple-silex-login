<?php

declare (strict_types = 1);

namespace Login\Controller;

use Login\Service\Security\Captcha\Util\CaptchaHelper;
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
     * @var CaptchaHelper
     */
    private $captchaHelper;

    /**
     * @param RendererServiceInterface $renderer
     * @param UrlGenerator             $router
     * @param CaptchaHelper            $captchaHelper
     * @param \Closure                 $lastErrorGuesser
     * @param \Closure                 $lastUsernameGuesser
     */
    public function __construct(
        RendererServiceInterface $renderer,
        UrlGenerator $router,
        CaptchaHelper $captchaHelper,
        \Closure $lastErrorGuesser,
        \Closure $lastUsernameGuesser
    ) {
        parent::__construct($renderer, $router);
        $this->lastErrorGuesser = $lastErrorGuesser;
        $this->lastUsernameGuesser = $lastUsernameGuesser;
        $this->captchaHelper = $captchaHelper;
    }

    public function loginAction(Request $request) : Response
    {
        $lastErrorGuesser = $this->lastErrorGuesser;
        $lastUsernameGuesser = $this->lastUsernameGuesser;
        $lastUsername = $lastUsernameGuesser();

        // TODO - use CSRF protection
        return $this->renderActionTemplate(array(
            'lastError' => $lastErrorGuesser($request),
            'lastUsername' => $lastUsername,
            'action' => $this->generateUrl('login_check'),
            'captcha' => $this->captchaHelper->getCaptcha($request, $lastUsername),
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
