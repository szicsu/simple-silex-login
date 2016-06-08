<?php

declare (strict_types = 1);

namespace Login\Service\Security;

use Login\Request\LoginRequest;
use Login\Service\Reader\UserFinderByEmailInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLoginProvider implements UserLoginProviderInterface
{
    /**
     * @var UserFinderByEmailInterface
     */
    private $userFinder;

    /**
     * @param UserFinderByEmailInterface $userFinder
     */
    public function __construct(UserFinderByEmailInterface $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByLoginRequest(LoginRequest $loginRequest) : UserInterface
    {
        return $this->userFinder->findOneByEmail($loginRequest->getEmail());
    }
}
