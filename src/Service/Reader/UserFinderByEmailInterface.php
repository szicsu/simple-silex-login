<?php

namespace Login\Service\Reader;

use Login\Entity\User;

interface UserFinderByEmailInterface
{
    public function findOneByEmail(string $email): User;
}
