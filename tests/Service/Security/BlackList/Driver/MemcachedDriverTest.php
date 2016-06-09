<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Driver;

use Login\Service\Security\BlackList\Driver\MemcachedDriver;
use Psr\Log\NullLogger;

class MemcachedDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $mc = $this->getMockBuilder(\Memcached::class)->disableOriginalConstructor()->getMock();
        $mc
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('ns-fooBar')
            )
            ->willReturn(42)
        ;

        /* @var \Memcached $mc */
        $driver = new MemcachedDriver($mc, 'ns', new NullLogger());
        $this->assertSame(42, $driver->getByKey('fooBar'));
    }

    public function testGetWithTooLongKey()
    {
        $key = implode(range(0, 500));

        $mc = $this->getMockBuilder(\Memcached::class)->disableOriginalConstructor()->getMock();
        $mc
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('ns-'.md5('ns-'.$key))
            )
            ->willReturn(42)
        ;
        /* @var \Memcached $mc */
        $driver = new MemcachedDriver($mc, 'ns', new NullLogger());
        $this->assertSame(42, $driver->getByKey($key));
    }

    public function testIncrement()
    {
        $ttl = 300;

        $mc = $this->getMockBuilder(\Memcached::class)->disableOriginalConstructor()->getMock();
        $mc
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo('ns-fooBar'),
                $this->equalTo(1),
                $this->equalTo(0),
                $this->equalTo($ttl)
            )
            ->willReturn(42)
        ;

        /* @var \Memcached $mc */
        $driver = new MemcachedDriver($mc, 'ns', new NullLogger());
        $driver->increment('fooBar', $ttl);
    }

    public function testIncrementWithNewKey()
    {
        $ttl = 300;

        $mc = $this->getMockBuilder(\Memcached::class)->disableOriginalConstructor()->getMock();
        $mc
            ->expects($this->once())
            ->method('increment')
            ->with(
                $this->equalTo('ns-fooBar'),
                $this->equalTo(1),
                $this->equalTo(0),
                $this->equalTo($ttl)
            )
            ->willReturn(false)
        ;

        $mc
            ->expects($this->once())
            ->method('getResultCode')
            ->willReturn(\Memcached::RES_NOTFOUND)
        ;

        $mc
            ->expects($this->once())
            ->method('add')
            ->with(
                $this->equalTo('ns-fooBar'),
                $this->equalTo(1),
                $this->equalTo($ttl)
            )
            ->willReturn(true)
        ;

        /* @var \Memcached $mc */
        $driver = new MemcachedDriver($mc, 'ns', new NullLogger());
        $driver->increment('fooBar', $ttl);
    }
}
