<?php

namespace Login\Service\Transformer;

use Login\Entity\User;
use Login\Request\RegistrationRequest;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class RegistrationRequestToUserTransformer
{
    /**
     * @var EncoderFactoryInterface
     */
    private $passwordEncoderFactory;

    /**
     * @param EncoderFactoryInterface $passwordEncoderFactory
     */
    public function __construct(EncoderFactoryInterface $passwordEncoderFactory)
    {
        $this->passwordEncoderFactory = $passwordEncoderFactory;
    }

    public function transform(RegistrationRequest $registrationRequest, User $user)
    {
        $user->setUsername($registrationRequest->getUsername());
        $user->setEmail($registrationRequest->getEmail());

        $password = $this->getPasswordEncoder($user)->encodePassword($registrationRequest->getPassword(), $user->getSalt());
        $user->setPassword($password);
    }

    private function getPasswordEncoder(User $user): PasswordEncoderInterface
    {
        return $this->passwordEncoderFactory->getEncoder($user);
    }
}
