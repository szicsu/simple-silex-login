<?php

namespace Login\Service;

use Login\Request\RegistrationRequest;
use Login\Service\Persister\UserPersister;
use Login\Service\Transformer\RegistrationRequestToUserTransformer;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RegistrationRequestToUserTransformer
     */
    private $transformer;

    /**
     * @var UserPersister
     */
    private $userPersister;

    /**
     * @param RegistrationRequestToUserTransformer $transformer
     * @param UserPersister                        $userPersister
     * @param ValidatorInterface                   $validator
     */
    public function __construct(RegistrationRequestToUserTransformer $transformer, UserPersister $userPersister, ValidatorInterface $validator)
    {
        $this->transformer = $transformer;
        $this->userPersister = $userPersister;
        $this->validator = $validator;
    }

    public function register(RegistrationRequest $registrationRequest)
    {
        $errors = $this->validator->validate($registrationRequest);

        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors); //TODO create domain specific exception
        }

        $userEntity = $this->userPersister->createNew();
        $this->transformer->transform($registrationRequest, $userEntity);
        $this->userPersister->persist($userEntity);
    }
}
