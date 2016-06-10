<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha\Generator;

use Login\Service\Security\Captcha\CaptchaDescriptor;

/**
 * Simple impl. for captcha generator.
 */
class NumericCaptchaGenerator implements CaptchaGeneratorInterface
{
    /**
     * @var callable
     */
    private $randomGenerator;

    /**
     * @param callable $randomGenerator
     */
    public function __construct(callable $randomGenerator)
    {
        $this->randomGenerator = $randomGenerator;
    }

    /**
     * @return CaptchaDescriptor
     */
    public function generate() : CaptchaDescriptor
    {
        $parts = array($this->genRand(), $this->genRand(), $this->genRand());

        return new CaptchaDescriptor(
            implode(' + ', $parts).' = ',
            (string) array_sum($parts)
        );
    }

    /**
     * @return int
     */
    private function genRand() :int
    {
        $generator = $this->randomGenerator;

        return (int) ($generator instanceof \Closure ? $generator() : call_user_func($generator));
    }
}
