<?php

namespace Login\ServiceProvider\Controller;

use Login\Controller\UserProfileController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserProfileControllerFactory  extends AbstractControllerFactory
{
    public function __invoke() : UserProfileController
    {
        return new UserProfileController(
            $this->getRenderService(),
            $this->getRouterService(),
            $this->getTokenStorage()
        );
    }

    private function getTokenStorage() : TokenStorageInterface
    {
        return $this->getApp()['security.token_storage'];
    }
}
