<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\Storage\BlackListStorageInterface;

class BlackListManager implements BlacklistManagerInterface
{
    /**
     * @var BlackListStorageInterface
     */
    private $storage;

    /**
     * @param BlackListStorageInterface $storage
     */
    public function __construct(BlackListStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function handleBadLogin(LoginRequest $loginRequest)
    {
        if ('' !== $loginRequest->getEmail()) {
            $this->storage->incrementByEmail($loginRequest->getEmail());
        }

        $this->storage->incrementByIp($loginRequest->getClientIp());
    }

    public function isInBlackList(LoginRequest $loginRequest) : bool
    {
        return
            $this->storage->isInByEmail($loginRequest->getEmail()) ||
            $this->storage->isInByIp($loginRequest->getClientIp());
    }
}
