<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha\Util;

class CaptchaRandomGenerator
{
    /**
     * @var int
     */
    private $minVal;

    /**
     * @var int
     */
    private $maxVal;

    /**
     * @param int $minVal
     * @param int $maxVal
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $minVal, int $maxVal)
    {
        if ($minVal > $maxVal) {
            throw  new \InvalidArgumentException(sprintf(
                'MinValue(%d) must be less than or equal to the maxValue(%d)!',
                $minVal,
                $maxVal
            ));
        }

        $this->minVal = $minVal;
        $this->maxVal = $maxVal;
    }

    public function __invoke()
    {
        return random_int($this->minVal, $this->maxVal);
    }
}
