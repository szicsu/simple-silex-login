<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList;

use Login\Request\LoginRequest;

interface BlacklistManagerInterface
{
    public function handleBadLogin(LoginRequest $loginRequest);
    public function isInBlackList(LoginRequest $loginRequest) : bool;
}
