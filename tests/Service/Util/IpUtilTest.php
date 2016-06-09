<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Reader;

use Login\Service\Security\BlackList\Util\IpLevelUtil;
use Login\Service\Security\BlackList\Util\IpUtil;

class IpUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractForLevels()
    {
        $this->assertSame([
            IpLevelUtil::LEVEL_4 => '1.2.3.4',
            IpLevelUtil::LEVEL_3 => '1.2.3',
            IpLevelUtil::LEVEL_2 => '1.2',
            IpLevelUtil::LEVEL_1 => '1',
        ], IpUtil::extractForLevels('1.2.3.4'));
    }
}
