<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Security\Captcha\Storage;

use Login\Service\Security\Captcha\Storage\FlashCaptchaStorage;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class FlashCaptchaStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $storage = new FlashCaptchaStorage(new FlashBag());
        $storage->set('fooBar');
        $this->assertSame('fooBar', $storage->get());
    }
}
