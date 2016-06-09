<?php

declare (strict_types = 1);

namespace Login\Service\Security;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\BlacklistManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLoginProviderWithBlackList implements UserLoginProviderInterface
{
    /**
     * @var BlacklistManagerInterface
     */
    private $blacklistManager;

    /**
     * @var UserLoginProviderInterface
     */
    private $innerUserLoginProvider;

    /**
     * @param BlacklistManagerInterface $blacklistManager
     * @param UserLoginProviderInterface $innerUserLoginProvider
     */
    public function __construct(BlacklistManagerInterface $blacklistManager, UserLoginProviderInterface $innerUserLoginProvider)
    {
        $this->blacklistManager = $blacklistManager;
        $this->innerUserLoginProvider = $innerUserLoginProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByLoginRequest(LoginRequest $loginRequest) : UserInterface
    {
        try{
            return $this->innerUserLoginProvider->loadUserByLoginRequest($loginRequest);
        } catch ( \Exception $ex ){
            $this->blacklistManager->handleBadLogin($loginRequest);
            throw  $ex;
        }
    }
}