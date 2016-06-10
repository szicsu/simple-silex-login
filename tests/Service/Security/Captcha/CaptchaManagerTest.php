<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Security\Captcha;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\BlacklistManagerInterface;
use Login\Service\Security\Captcha\CaptchaDescriptor;
use Login\Service\Security\Captcha\CaptchaManager;
use Login\Service\Security\Captcha\Generator\CaptchaGeneratorInterface;
use Login\Service\Security\Captcha\Storage\CaptchaStorageInterface;

class CaptchaManagerTest  extends \PHPUnit_Framework_TestCase
{
    public function testIsNeed()
    {
        $loginRequest = $this->createLoginRequest('foo', 0);
        $manager = new CaptchaManager(
            $this->createBlacklistManager(true),
            $this->createCaptchaGenerator('Foo?', 'Bar!', 0),
            $this->createCaptchaStorage('bar', 0)
        );

        $this->assertTrue($manager->isNeed($loginRequest));

        $manager = new CaptchaManager(
            $this->createBlacklistManager(false),
            $this->createCaptchaGenerator('Foo?', 'Bar!', 0),
            $this->createCaptchaStorage('bar', 0)
        );

        $this->assertFalse($manager->isNeed($loginRequest));
    }

    public function testIsValid()
    {
        $manager = new CaptchaManager(
            $this->createBlacklistManager(true, 0),
            $this->createCaptchaGenerator('Foo?', 'Bar!', 0),
            $this->createCaptchaStorage('foo', 2)
        );

        $this->assertTrue($manager->isValid($this->createLoginRequest('foo', 1)));
        $this->assertFalse($manager->isValid($this->createLoginRequest('Bar', 1)));
    }

    public function testGetMessageForDisplay()
    {
        $storage = $this->createMock(CaptchaStorageInterface::class);
        $storage->expects($this->once())->method('set')->with($this->equalTo('Bar!'));

        $manager = new CaptchaManager(
            $this->createBlacklistManager(true, 0),
            $this->createCaptchaGenerator('Foo?', 'Bar!', 1),
            $storage
        );

        $this->assertSame('Foo?', $manager->getMessageForDisplay($this->createLoginRequest('foo', 0)));
    }

    private function createBlacklistManager(bool $isInBlackList, int $callCount = 1) : BlacklistManagerInterface
    {
        $blackList = $this->createMock(BlacklistManagerInterface::class);
        $blackList->expects($this->exactly($callCount))->method('isInBlackList')->willReturn($isInBlackList);

        return $blackList;
    }

    private function createCaptchaGenerator(string $question, string  $answer, int $callCount = 1) : CaptchaGeneratorInterface
    {
        $generator = $this->createMock(CaptchaGeneratorInterface::class);
        $generator->expects($this->exactly($callCount))->method('generate')->willReturn(new CaptchaDescriptor($question, $answer));

        return $generator;
    }

    private function createCaptchaStorage(string  $value, int $callCount = 1) : CaptchaStorageInterface
    {
        $storage = $this->createMock(CaptchaStorageInterface::class);
        $storage->expects($this->exactly($callCount))->method('get')->willReturn($value);

        return $storage;
    }

    private function createLoginRequest(string  $value, int $callCount = 1)
    {
        $loginRequest = $this->getMockBuilder(LoginRequest::class)->disableOriginalConstructor()->getMock();
        $loginRequest->expects($this->exactly($callCount))->method('getCaptchaValue')->willReturn($value);

        return $loginRequest;
    }
}
