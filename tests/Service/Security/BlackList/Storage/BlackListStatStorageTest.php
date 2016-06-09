<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Storage;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\TimeKeyExtractorInterface;
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
        $windowSize = 10;

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo('ip-'.$level.'-mock'),
                $this->equalTo($ttl)
            );

        /* @var DriverInterface $driver */
        $store = new BlackListStatStorage(
            $this->createConfig($windowSize, $ttl),
            $driver,
            $this->createKeyExtractor($windowSize)
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
        $windowSize = 10;

        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo('email-mock'),
                $this->equalTo($ttl)
            );

        /* @var DriverInterface $driver */
        $store = new BlackListStatStorage(
            $this->createConfig($windowSize, $ttl),
            $driver,
            $this->createKeyExtractor($windowSize)
        );
        $store->incrementByEmail();
    }

    private function createKeyExtractor(int $windowSize) : TimeKeyExtractorInterface
    {
        $keyEx = $this->createMock(TimeKeyExtractorInterface::class);
        $keyEx
            ->expects($this->any())
            ->method('extract')
            ->willReturnCallback(function (\DateTimeInterface $date, $winSize) use ($windowSize) {
                $this->assertSame($windowSize, $winSize);
                $this->assertEquals(time(), $date->getTimestamp(), '', 2);

                return 'mock';
            });

        return $keyEx;
    }

    private function createConfig(int $windowSize, int $ttl) : BlackListConfig
    {
        $conf = $this->getMockBuilder(BlackListConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $conf->expects($this->any())->method('getKeyTTL')->willReturn($ttl);
        $conf->expects($this->any())->method('getStatWindowSize')->willReturn($windowSize);

        return $conf;
    }
}
