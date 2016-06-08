<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Storage;

interface BlackListStorageInterface
{
    public function incrementByEmail(string $email);
    public function incrementByIp(string $ip);
    public function isInByEmail(string $email): bool;
    public function isInByIp(string $ip) : bool;
}
