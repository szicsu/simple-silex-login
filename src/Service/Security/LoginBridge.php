<?php

declare (strict_types = 1);

namespace Login\Service\Security;

use Login\Entity\User;
use Login\Request\LoginRequestFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginBridge implements UserProviderInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var LoginRequestFactory
     */
    private $loginRequestFactory;

    /**
     * @var UserLoginProviderInterface
     */
    private $userLoginProvider;

    /**
     * @param LoginRequestFactory $loginRequestFactory
     * @param RequestStack        $requestStack
     * @param UserLoginProviderInterface   $userLoginProvider
     */
    public function __construct(LoginRequestFactory $loginRequestFactory, RequestStack $requestStack, UserLoginProviderInterface $userLoginProvider)
    {
        $this->loginRequestFactory = $loginRequestFactory;
        $this->requestStack = $requestStack;
        $this->userLoginProvider = $userLoginProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $loginRequest = $this->loginRequestFactory->createByEmailAndRequest($username, $this->requestStack->getCurrentRequest());

        try{
            return $this->userLoginProvider->loadUserByLoginRequest($loginRequest);
        } catch( \Exception $ex ){
            throw new UsernameNotFoundException('Login failed!', 0, $ex);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        // It don't refresh user in this sample solution
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class || is_subclass_of($class, User::class);
    }
}
