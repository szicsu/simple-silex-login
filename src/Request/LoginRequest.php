<?php

declare (strict_types = 1);

namespace Login\Request;

class LoginRequest
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @var string;
     */
    private $captchaValue;

    /**
     * @param string $captchaValue
     * @param string $clientIp
     * @param string $email
     */
    public function __construct(string $email, string $clientIp, string $captchaValue)
    {
        $this->captchaValue = $captchaValue;
        $this->clientIp = $clientIp;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCaptchaValue() : string
    {
        return $this->captchaValue;
    }

    /**
     * @return string
     */
    public function getClientIp() : string
    {
        return $this->clientIp;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }
}
