<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Storage;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\StatKeyExtractor;
use Login\Service\Security\BlackList\Storage\BlackListStatStorage;

class BlackListStatStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param int $level
     * @dataProvider providerIncrementByIpLevel
     */
    public function testIncrementByIpLevel(int $level)
    {
        $ttl = 400;
        $refKey = 'incrementByIpLevel-'.$level;

        $keyExtractor = $this->createMock(StatKeyExtractor::class);
        $keyExtractor->expects($this->once())
            ->method('extractForIpLevel')
            ->with($this->isInstanceOf(\DateTimeInterface::class))
            ->willReturn($refKey);

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo($refKey),
                $this->equalTo($ttl)
            );

        /* @var DriverInterface $driver */
        $store = new BlackListStatStorage(
            $this->createConfig($ttl),
            $driver,
            $keyExtractor
        );
        $store->incrementByIpLevel($level);
    }

    public function providerIncrementByIpLevel() : array
    {
        return array(
            array(4),
            array(3),
            array(2),
            array(1),
        );
    }

    public function testIncrementByEmail()
    {
        $ttl = 400;
        $refKey = 'incrementByEmil';

        $keyExtractor = $this->createMock(StatKeyExtractor::class);
        $keyExtractor->expects($this->once())
            ->method('extractForEmail')
            ->with($this->isInstanceOf(\DateTimeInterface::class))
            ->willReturn($refKey);

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo($refKey),
                $this->equalTo($ttl)
            );

        /* @var DriverInterface $driver */
        $store = new BlackListStatStorage(
            $this->createConfig($ttl),
            $driver,
            $keyExtractor
        );
        $store->incrementByEmail();
    }

    public function testIncrementByGlobalFailedLogin()
    {
        $ttl = 400;
        $refKey = 'incrementByGlobalFailedLogin';

        $keyExtractor = $this->createMock(StatKeyExtractor::class);
        $keyExtractor->expects($this->once())
            ->method('extractForGlobalFailedLogin')
            ->with($this->isInstanceOf(\DateTimeInterface::class))
            ->willReturn($refKey);

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo($refKey),
                $this->equalTo($ttl)
            );

        /* @var DriverInterface $driver */
        $store = new BlackListStatStorage(
            $this->createConfig($ttl),
            $driver,
            $keyExtractor
        );
        $store->incrementByGlobalFailedLogin();
    }

    private function createConfig(int $ttl) : BlackListConfig
    {
        $conf = $this->getMockBuilder(BlackListConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $conf->expects($this->any())->method('getKeyTTL')->willReturn($ttl);

        return $conf;
    }
}
