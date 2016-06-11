<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Extractor;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Extractor\StatKeyExtractor;
use Login\Service\Security\BlackList\Extractor\TimeKeyExtractorInterface;
use Login\Service\Security\BlackList\Util\IpLevelUtil;

class StatKeyExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $refDate = new \DateTime();
        $conf = $this->getMockBuilder(BlackListConfig::class)->disableOriginalConstructor()->getMock();
        $conf->expects($this->exactly(4))->method('getStatWindowSize')->willReturn(333);
        /* @var BlackListConfig $conf */

        $timeExtr = $this->createMock(TimeKeyExtractorInterface::class);
        $timeExtr->expects($this->exactly(4))
            ->method('extract')
            ->willReturnCallback(function (\DateTimeInterface $date, $winSize) use ($refDate) {
                $this->assertSame($refDate, $date);
                $this->assertSame(333, $winSize);

                return 'time-333';
            });
        /* @var TimeKeyExtractorInterface $timeExtr */

        $extractor = new StatKeyExtractor($conf, $timeExtr);
        $this->assertEquals('email-stat-time-333', $extractor->extractForEmail($refDate));
        $this->assertEquals('global-stat-time-333', $extractor->extractForGlobalFailedLogin($refDate));
        $this->assertEquals('ip-3-stat-time-333', $extractor->extractForIpLevel($refDate, IpLevelUtil::LEVEL_3));
        $this->assertEquals('ip-4-stat-time-333', $extractor->extractForIpLevel($refDate, IpLevelUtil::LEVEL_4));
    }
}
