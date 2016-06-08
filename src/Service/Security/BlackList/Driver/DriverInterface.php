<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Driver;

interface DriverInterface
{
    public function increment(string $key, int $ttl);
    public function getByKey(string $key) : int;
}
