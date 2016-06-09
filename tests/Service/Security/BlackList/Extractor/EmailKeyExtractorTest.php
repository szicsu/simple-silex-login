<?php


declare (strict_types = 1);

namespace Login\Tests\Service\Security\BlackList\Extractor;

use Login\Service\Security\BlackList\Extractor\EmailKeyExtractor;

class EmailKeyExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $extractor = $this->createExtractor();

        $this->assertSame(['foo@bar.com'], $extractor->extract('foo@bar.com'));
        $this->assertSame(['foo@bar.com'], $extractor->extract('Foo@Bar.com'));
        $this->assertSame(['foo@bar.com'], $extractor->extract(' foo@bar.com '));
    }

    public function testExtractPrimary()
    {
        $extractor = $this->createExtractor();

        $this->assertSame('foo@bar.com', $extractor->extractPrimary('foo@bar.com'));
        $this->assertSame('foo@bar.com', $extractor->extractPrimary('Foo@Bar.com'));
        $this->assertSame('foo@bar.com', $extractor->extractPrimary(' foo@bar.com '));
    }

    private function createExtractor(): EmailKeyExtractor
    {
        return new EmailKeyExtractor();
    }
}
