<?php

namespace Login\Service\Security;

use Login\Request\LoginRequest;
use Login\Service\Security\Captcha\CaptchaManagerInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLoginProviderWithCaptcha implements UserLoginProviderInterface
{
    /**
     * @var CaptchaManagerInterface
     */
    private $captchaManager;

    /**
     * @var UserLoginProviderInterface
     */
    private $innerUserLoginProvider;

    /**
     * @param CaptchaManagerInterface    $captchaManager
     * @param UserLoginProviderInterface $innerUserLoginProvider
     */
    public function __construct(CaptchaManagerInterface $captchaManager, UserLoginProviderInterface $innerUserLoginProvider)
    {
        $this->captchaManager = $captchaManager;
        $this->innerUserLoginProvider = $innerUserLoginProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByLoginRequest(LoginRequest $loginRequest) : UserInterface
    {
        if (
            $this->captchaManager->isNeed($loginRequest) &&
            false === $this->captchaManager->isValid($loginRequest)
        ) {
            throw new LockedException();
        }

        return $this->innerUserLoginProvider->loadUserByLoginRequest($loginRequest);
    }
}
