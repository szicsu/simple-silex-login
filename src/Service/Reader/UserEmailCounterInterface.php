<?php

declare (strict_types = 1);

namespace Login\Service\Reader;

interface UserEmailCounterInterface
{
    public function countByEmail(string $email) : int;
}
