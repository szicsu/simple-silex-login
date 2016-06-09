<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Storage;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\EmailKeyExtractorInterface;
use Login\Service\Security\BlackList\Extractor\IpKeyExtractorInterface;
use Login\Service\Security\BlackList\Storage\BlackListStatStorageInterface;
use Login\Service\Security\BlackList\Storage\BlackListStorage;

class BlackListStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testIncrementByEmail()
    {
        $email = 'foo@bar.com';
        $ttl = 4;
        $driverKeys = array();

        $conf = $this->getMockBuilder(BlackListConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $conf->expects($this->any())->method('getKeyTTL')->willReturn($ttl);
        /* @var BlackListConfig $conf */

        $extractor = $this->createMock(EmailKeyExtractorInterface::class);
        $extractor
            ->expects($this->once())
            ->method('extract')
            ->with($this->equalTo($email))
            ->willReturn(array('foo', 'bar'))
        ;
        /* @var EmailKeyExtractorInterface $extractor */

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->exactly(2))
            ->method('increment')
            ->willReturnCallback(function ($key, $argTtl) use (&$driverKeys, $ttl) {
                $driverKeys[] = $key;
                $this->assertSame($ttl, $argTtl);
            });
        /* @var DriverInterface $driver */

        $store = new BlackListStorage(
            $conf,
            $driver,
            $extractor,
            $this->createEmptyIpExtractorMock(),
            $this->createEmptyStatStorageMock()
        );
        $store->incrementByEmail($email);
        $this->assertEquals(array('foo', 'bar'), $driverKeys);
    }

    public function testIncrementByIp()
    {
        $ip = '1.2.3.4';
        $ttl = 4;
        $driverKeys = array();

        $conf = $this->getMockBuilder(BlackListConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $conf->expects($this->any())->method('getKeyTTL')->willReturn($ttl);
        /* @var BlackListConfig $conf */

        $extractor = $this->createMock(IpKeyExtractorInterface::class);
        $extractor
            ->expects($this->once())
            ->method('extract')
            ->with($this->equalTo($ip))
            ->willReturn(array('1.2', '1.2.3', '1.2.3.4'))
        ;
        /* @var IpKeyExtractorInterface $extractor */

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->exactly(3))
            ->method('increment')
            ->willReturnCallback(function ($key, $argTtl) use (&$driverKeys, $ttl) {
                $driverKeys[] = $key;
                $this->assertSame($ttl, $argTtl);
            });
        /* @var DriverInterface $driver */

        $store = new BlackListStorage(
            $conf,
            $driver,
            $this->createEmptyEmailExtractorMock(),
            $extractor,
            $this->createEmptyStatStorageMock()
        );
        $store->incrementByIp($ip);
        $this->assertEquals(array('1.2', '1.2.3', '1.2.3.4'), $driverKeys);
    }

    /**
     * @param bool $isInList
     * @param int  $configLimit
     *
     * @dataProvider providerIsInByEmail
     */
    public function testIsInByEmail(bool $isInList, int $configLimit)
    {
        $driverValue = 20;
        $email = 'foo@bar.com';
        $conf = $this->getMockBuilder(BlackListConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $conf->expects($this->any())->method('getLimitSameUser')->willReturn($configLimit);
        /* @var BlackListConfig $conf */

        $extractor = $this->createMock(EmailKeyExtractorInterface::class);
        $extractor
            ->expects($this->once())
            ->method('extractPrimary')
            ->with($this->equalTo($email))
            ->willReturn($email)
        ;
        /* @var EmailKeyExtractorInterface $extractor */

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->exactly(1))
            ->method('getByKey')
            ->willReturn($driverValue);
        /* @var DriverInterface $driver */

        $stat = $this->createMock(BlackListStatStorageInterface::class);
        $stat->expects($isInList ? $this->once() : $this->never())->method('incrementByEmail');

        $store = new BlackListStorage(
            $conf,
            $driver,
            $extractor,
            $this->createEmptyIpExtractorMock(),
            $stat
        );

        $this->assertSame($isInList, $store->isInByEmail($email));
    }

    public function providerIsInByEmail() : array
    {
        return array(
            '10 < 20' => array(true,  10),
            '30 < 20' => array(false, 30),
        );
    }

    /**
     * @param bool $isInList
     * @param int  $configLimit
     * @param int  $level
     *
     * @dataProvider providerIsInByIp
     */
    public function testIsInByIp(bool $isInList, int $configLimit, int $level)
    {
        $driverValue = 20;
        $driverCallCount = $isInList ? (5 - $level) : 3;
        $ip = '1.2.3.4';
        $ipMap = [4 => '1.2.3.4', 3 => '1.2.3', 2 => '1.2'];

        $conf = $this->getMockBuilder(BlackListConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $conf->expects($this->any())->method('getLimitIpForLevel')->willReturn($configLimit);
        /* @var BlackListConfig $conf */

        $extractor = $this->createMock(IpKeyExtractorInterface::class);
        $extractor
            ->expects($this->any())
            ->method('extractForLevel')
            ->willReturnCallback(function ($ip, $level) use ($ipMap) { return $ipMap[$level]; });
        /* @var IpKeyExtractorInterface $extractor */

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->exactly($driverCallCount))
            ->method('getByKey')
            ->willReturnCallback(function ($ip) use ($level, $driverValue, $ipMap) {

                return $ip === $ipMap[$level] ? $driverValue : 0;
            });
        /* @var DriverInterface $driver */

        $stat = $this->createMock(BlackListStatStorageInterface::class);
        $stat->expects($isInList ? $this->once() : $this->never())->method('incrementByIpLevel')->with($this->equalTo($level));

        $store = new BlackListStorage(
            $conf,
            $driver,
            $this->createEmptyEmailExtractorMock(),
            $extractor,
            $stat
        );
        $this->assertSame($isInList, $store->isInByIp($ip));
    }

    public function providerIsInByIp() : array
    {
        return array(
            #                        isInList   configLimit  level
            'level4 => 10 < 20' => [true,      10,         4],
            'level4 => 30 < 20' => [false,     30,         4],

            'level3 => 10 < 20' => [true,      10,         3],
            'level3 => 30 < 20' => [false,     30,         3],

            'level2 => 10 < 20' => [true,      10,         2],
            'level2 => 30 < 20' => [false,     30,         2],
        );
    }

    private function createEmptyIpExtractorMock() : IpKeyExtractorInterface
    {
        return $this->createMock(IpKeyExtractorInterface::class);
    }

    private function createEmptyStatStorageMock() : BlackListStatStorageInterface
    {
        return $this->createMock(BlackListStatStorageInterface::class);
    }

    private function createEmptyEmailExtractorMock()
    {
        return $this->createMock(EmailKeyExtractorInterface::class);
    }
}
