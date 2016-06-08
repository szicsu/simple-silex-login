<?php

namespace Login\Service\Security\BlackList;

interface BlackListStorageInterface
{
    public function incrementByEmail(string $email);
    public function incrementByIp(string $ip);
    public function isInByEmail(string $email);
    public function isInByIp(string $ip);
}
