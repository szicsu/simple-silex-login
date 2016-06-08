<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Extractor;

namespace Login\Service\Security\BlackList\Util;

final class IpLevelUtil
{
    const LEVEL_4 = 4;
    const LEVEL_3 = 3;
    const LEVEL_2 = 2;

    private function __construct()
    {
    }

    public static function getAllLevelValues() : array
    {
        return array(
            self::LEVEL_4, self::LEVEL_3, self::LEVEL_2,
        );
    }

    public static function checkValue(int $level)
    {
        if (true !== in_array($level, self::getAllLevelValues(), true)) {
            throw new \InvalidArgumentException(sprintf(
                'The level "%s" is invalid!',
                $level
            ));
        }
    }
}
