<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Extractor;

use Login\Service\Security\BlackList\Util\IpLevelUtil;
use Login\Service\Security\BlackList\Util\IpUtil;

class IpKeyExtractor implements IpKeyExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function extract(string $ip) : array
    {
        $levels = IpUtil::extractForLevels($ip);

        return array(
            $levels[ IpLevelUtil::LEVEL_4 ],
            $levels[ IpLevelUtil::LEVEL_3 ],
            $levels[ IpLevelUtil::LEVEL_2 ],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function extractForLevel(string $ip, int $level) : string
    {
        IpLevelUtil::checkValue($level);
        $levels = IpUtil::extractForLevels($ip);

        return $levels[ $level ];
    }
}
