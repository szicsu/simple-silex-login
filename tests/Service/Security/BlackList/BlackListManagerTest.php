<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\BlackListManager;
use Login\Service\Security\BlackList\Storage\BlackListStorageInterface;

class BlackListManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleBadLogin()
    {
        $email = 'foo@bar.com';
        $ip = '1.2.3.4';

        $storage = $this->createMock(BlackListStorageInterface::class);
        $storage->expects($this->once())->method('incrementByEmail')->with($this->equalTo($email));
        $storage->expects($this->once())->method('incrementByIp')->with($this->equalTo($ip));

        $loginRequest = new LoginRequest($email, $ip, 'FooBar');
        $manager = new BlackListManager($storage);
        $manager->handleBadLogin($loginRequest);
    }

    /**
     * @param bool $expected
     * @param bool $isInEmail
     * @param bool $isInIp
     *
     * @dataProvider provideIsInBlackList
     */
    public function testIsInBlackList(bool $expected, bool $isInEmail, bool $isInIp)
    {
        $email = 'foo@bar.com';
        $ip = '1.2.3.4';

        $storage = $this->createMock(BlackListStorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('isInByEmail')
            ->with($this->equalTo($email))
            ->willReturn($isInEmail);

        $storage
            ->expects($isInEmail ? $this->never() : $this->once())
            ->method('isInByIp')
            ->with($this->equalTo($ip))
            ->willReturn($isInIp);

        $loginRequest = new LoginRequest($email, $ip, 'FooBar');
        $manager = new BlackListManager($storage);
        $this->assertSame($expected, $manager->isInBlackList($loginRequest));
    }

    public function provideIsInBlackList()
    {
        #                      $expected,   $isInEmail,     $isInIp
        return array(
            'true && true' => [true,       true,           true],
            'true && false' => [true,       false,          true],
            'false && true' => [true,       true,           false],
            'false && false' => [false,       false,         false],
        );
    }
}
