<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Extractor;

use Login\Service\Security\BlackList\Extractor\TimeKeyExtractor;

class TimeKeyExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $extractor = $this->createExtractor();

        $this->assertSame('time-0', $extractor->extract(new \DateTime('@'. 20), 30));
        $this->assertSame('time-1', $extractor->extract(new \DateTime('@'. 40), 30));
        $this->assertSame('time-1', $extractor->extract(new \DateTime('@'. 50), 30));
        $this->assertSame('time-2', $extractor->extract(new \DateTime('@'. 70), 30));
    }

    private function createExtractor(): TimeKeyExtractor
    {
        return new TimeKeyExtractor();
    }
}
