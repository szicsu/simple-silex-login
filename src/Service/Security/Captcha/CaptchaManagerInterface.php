<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha;

use Login\Request\LoginRequest;

interface CaptchaManagerInterface
{
    public function isNeed(LoginRequest $loginRequest) : bool;
    public function getMessageForDisplay(LoginRequest $loginRequest): string;
    public function isValid(LoginRequest $loginRequest) : bool;
}
