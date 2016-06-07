<?php

namespace Login\Service\Security;

use Login\Request\LoginRequest;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserLoginProviderInterface
{
    public function loadUserByLoginRequest(LoginRequest $loginRequest) : UserInterface;
}
