<?php

namespace Login\Tests\Service\Security\BlackList\Extractor;

use Login\Service\Security\BlackList\Extractor\IpKeyExtractor;
use Login\Service\Security\BlackList\Util\IpLevelUtil;

class IpKeyExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $this->assertSame([
            '1.2.3.4',
            '1.2.3',
            '1.2',
        ], $this->createExtractor()->extract('1.2.3.4'));
    }

    public function testExtractForLevel()
    {
        $ipKeyExtractor = $this->createExtractor();
        $this->assertSame('1.2.3.4', $ipKeyExtractor->extractForLevel('1.2.3.4', IpLevelUtil::LEVEL_4));
        $this->assertSame('1.2.3', $ipKeyExtractor->extractForLevel('1.2.3.4', IpLevelUtil::LEVEL_3));
        $this->assertSame('1.2', $ipKeyExtractor->extractForLevel('1.2.3.4', IpLevelUtil::LEVEL_2));
    }

    private function createExtractor() : IpKeyExtractor
    {
        return new IpKeyExtractor();
    }
}
