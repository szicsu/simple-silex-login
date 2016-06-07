<?php

declare (strict_types = 1);

namespace Login\Request;

use Symfony\Component\HttpFoundation\Request;

class LoginRequestFactory
{
    public function createByEmailAndRequest(string $email, Request $request) : LoginRequest
    {
        return new LoginRequest(
            $email,
            $request->getClientIp(),
            (string) $request->request->get('captcha')
        );
    }
}
