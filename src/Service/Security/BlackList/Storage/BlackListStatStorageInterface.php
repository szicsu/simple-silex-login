<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Storage;

interface BlackListStatStorageInterface
{
    public function incrementByIpLevel(int $level);
    public function incrementByEmail();
}
