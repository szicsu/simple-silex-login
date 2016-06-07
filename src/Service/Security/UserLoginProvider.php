<?php

declare (strict_types = 1);

namespace Login\Service\Security;

use Login\Request\LoginRequest;
use Login\Service\Reader\UserReader;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLoginProvider implements UserLoginProviderInterface
{
    /**
     * @var UserReader
     */
    private $userReader;

    /**
     * @param UserReader $userReader
     */
    public function __construct(UserReader $userReader)
    {
        $this->userReader = $userReader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByLoginRequest(LoginRequest $loginRequest) : UserInterface
    {
        return $this->userReader->findOneByEmail($loginRequest->getEmail());
    }
}
