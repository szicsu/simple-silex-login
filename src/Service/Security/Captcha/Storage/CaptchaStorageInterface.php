<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha\Storage;

interface CaptchaStorageInterface
{
    public function set(string $value);
    public function get() : string;
}
