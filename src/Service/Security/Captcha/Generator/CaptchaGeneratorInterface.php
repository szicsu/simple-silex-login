<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha\Generator;

use Login\Service\Security\Captcha\CaptchaDescriptor;

interface CaptchaGeneratorInterface
{
    public function generate() : CaptchaDescriptor;
}
