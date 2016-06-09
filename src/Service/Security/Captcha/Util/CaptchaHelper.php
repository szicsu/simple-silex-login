<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha\Util;

use Login\Request\LoginRequestFactory;
use Login\Service\Security\Captcha\CaptchaManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CaptchaHelper
{
    /**
     * @var CaptchaManagerInterface
     */
    private $captchaManager;

    /**
     * @var LoginRequestFactory
     */
    private $loginRequestFactory;

    /**
     * @param CaptchaManagerInterface $captchaManager
     * @param LoginRequestFactory $loginRequestFactory
     */
    public function __construct(CaptchaManagerInterface $captchaManager, LoginRequestFactory $loginRequestFactory)
    {
        $this->captchaManager = $captchaManager;
        $this->loginRequestFactory = $loginRequestFactory;
    }

    /**
     * @param Request $request
     * @param string|null $lastEmail
     * @return string|null
     */
    public function getCaptcha( Request $request, $lastEmail = NULL )
    {
        $loginRequest = $this->loginRequestFactory->createByEmailAndRequest((string)$lastEmail, $request);

        if( $this->captchaManager->isNeed($loginRequest)){
            return $this->captchaManager->getMessageForDisplay($loginRequest);
        }
        else{
            return NULL;
        }
    }
}