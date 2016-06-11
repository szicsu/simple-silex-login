<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\BlackListManager;
use Login\Service\Security\BlackList\Storage\BlackListStatStorageInterface;
use Login\Service\Security\BlackList\Storage\BlackListStorageInterface;

class BlackListManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleBadLoginIncGlobal()
    {
        $email = 'foo@bar.com';
        $ip = '1.2.3.4';

        $storage = $this->createMock(BlackListStorageInterface::class);
        $storage->expects($this->once())->method('incrementByEmail')->with($this->equalTo($email));
        $storage->expects($this->once())->method('incrementByIp')->with($this->equalTo($ip));
        $storage->expects($this->once())->method('getIpLevelThatIsInByIp')->with($this->equalTo($ip))->willReturn(false);

        $stat = $this->createMock(BlackListStatStorageInterface::class);
        $stat->expects($this->once())->method('incrementByGlobalFailedLogin');

        $loginRequest = new LoginRequest($email, $ip, 'FooBar');
        $manager = new BlackListManager($storage, $stat);
        $manager->handleBadLogin($loginRequest);
    }

    public function testHandleBadLoginIncEmailStat()
    {
        $email = 'foo@bar.com';
        $ip = '1.2.3.4';

        $storage = $this->createMock(BlackListStorageInterface::class);
        $storage->expects($this->once())->method('getIpLevelThatIsInByIp')->with($this->equalTo($ip))->willReturn(false);
        $storage->expects($this->once())->method('incrementByEmail')->with($this->equalTo($email));
        $storage->expects($this->once())->method('isInByEmail')->with($this->equalTo($email))->willReturn(true);

        $stat = $this->createMock(BlackListStatStorageInterface::class);
        $stat->expects($this->once())->method('incrementByEmail');

        $loginRequest = new LoginRequest($email, $ip, 'FooBar');
        $manager = new BlackListManager($storage, $stat);
        $manager->handleBadLogin($loginRequest);
    }

    public function testHandleBadLoginIncIpStat()
    {
        $email = 'foo@bar.com';
        $ip = '1.2.3.4';
        $level = 1;

        $storage = $this->createMock(BlackListStorageInterface::class);
        $storage->expects($this->once())->method('getIpLevelThatIsInByIp')->with($this->equalTo($ip))->willReturn($level);
        $storage->expects($this->once())->method('incrementByIp')->with($this->equalTo($ip));

        $stat = $this->createMock(BlackListStatStorageInterface::class);
        $stat->expects($this->once())->method('incrementByIpLevel')->with($this->equalTo($level));

        $loginRequest = new LoginRequest($email, $ip, 'FooBar');
        $manager = new BlackListManager($storage, $stat);
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
        $manager = new BlackListManager($storage, $this->createMock(BlackListStatStorageInterface::class));
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
