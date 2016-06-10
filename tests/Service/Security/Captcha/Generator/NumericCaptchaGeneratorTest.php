<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Security\Captcha\Generator;

use Login\Service\Security\Captcha\CaptchaDescriptor;
use Login\Service\Security\Captcha\Generator\NumericCaptchaGenerator;
use Login\Service\Security\Captcha\Util\CaptchaRandomGenerator;

class NumericCaptchaGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $generator = new NumericCaptchaGenerator(
            new CaptchaRandomGenerator(1, 1)
        );

        $desc = $generator->generate();
        $this->assertInstanceOf(CaptchaDescriptor::class, $desc);
        $this->assertSame('1 + 1 + 1 = ', $desc->getQuestion());
        $this->assertSame('3', $desc->getAnswer());
    }

    public function testGenerateWithClosure()
    {
        $i = 1;
        $generator = new NumericCaptchaGenerator(
            function () use (&$i) {
                return $i++;
            }
        );

        $desc = $generator->generate();
        $this->assertInstanceOf(CaptchaDescriptor::class, $desc);
        $this->assertSame('1 + 2 + 3 = ', $desc->getQuestion());
        $this->assertSame('6', $desc->getAnswer());
    }
}
