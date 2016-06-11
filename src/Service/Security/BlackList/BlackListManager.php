<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\Storage\BlackListStatStorageInterface;
use Login\Service\Security\BlackList\Storage\BlackListStorageInterface;

class BlackListManager implements BlacklistManagerInterface
{
    /**
     * @var BlackListStorageInterface
     */
    private $storage;

    /**
     * @var BlackListStatStorageInterface
     */
    private $statStore;

    /**
     * @param BlackListStorageInterface     $storage
     * @param BlackListStatStorageInterface $statStore
     */
    public function __construct(BlackListStorageInterface $storage, BlackListStatStorageInterface $statStore)
    {
        $this->storage = $storage;
        $this->statStore = $statStore;
    }

    /**
     * {@inheritdoc}
     */
    public function handleBadLogin(LoginRequest $loginRequest)
    {
        // FIXME - decrease data read
        $this->statStore->incrementByGlobalFailedLogin();

        if ('' !== $email = $loginRequest->getEmail()) {
            $this->incrementByEmail($email);
        }

        $this->incrementByIp($loginRequest->getClientIp());
    }

    /**
     * {@inheritdoc}
     */
    public function isInBlackList(LoginRequest $loginRequest) : bool
    {
        return
            $this->storage->isInByEmail($loginRequest->getEmail()) ||
            $this->storage->isInByIp($loginRequest->getClientIp());
    }

    /**
     * @param $email
     */
    private function incrementByEmail(string $email)
    {
        $this->storage->incrementByEmail($email);

        if (true === $isInByEmail = $this->storage->isInByEmail($email)) {
            $this->statStore->incrementByEmail();
        }
    }

    /**
     * @param string $ip
     */
    private function incrementByIp(string $ip)
    {
        $this->storage->incrementByIp($ip);
        if (false !== $ipLevel = $this->storage->getIpLevelThatIsInByIp($ip)) {
            $this->statStore->incrementByIpLevel($ipLevel);
        }
    }
}
