<?php

declare (strict_types = 1);

namespace Login\Service\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginBridgeProxy implements UserProviderInterface
{
    /**
     * @var UserProviderInterface|null
     */
    private $innerProvider;

    /**
     * @var \Closure
     */
    private $factory;

    /**
     * @param \Closure $factory
     */
    public function __construct(\Closure $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->getInnerProvider()->loadUserByUsername($username);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->getInnerProvider()->refreshUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $this->getInnerProvider()->supportsClass($class);
    }

    private function getInnerProvider() : UserProviderInterface
    {
        if (null === $this->innerProvider) {
            $factory = $this->factory;
            $this->innerProvider = $factory();
        }

        return $this->innerProvider;
    }
}
