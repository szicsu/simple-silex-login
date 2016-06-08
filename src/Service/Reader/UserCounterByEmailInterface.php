<?php

declare (strict_types = 1);

namespace Login\Service\Reader;

interface UserCounterByEmailInterface
{
    public function countByEmail(string $email) : int;
}
