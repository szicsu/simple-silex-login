<?php

namespace Login\Service\Security\BlackList;

//TODO move all values to config
use Login\Service\Security\BlackList\Util\IpLevelUtil;

class BlackListConfig
{
    public function getKeyTTL(): int
    {
        return 3600;
    }

    public function getLimitSameUser() : int
    {
        return 3;
    }

    public function getLimitIpForLevel(int $level) : int
    {
        IpLevelUtil::checkValue($level);

        switch ($level) {
            case IpLevelUtil::LEVEL_4:
                $limit = 3;
                break;

            case IpLevelUtil::LEVEL_3:
                $limit = 500;
                break;

            default:
                $limit = 1000;
                break;
        }

        return $limit;
    }

    public function getStatWindowSize() : int
    {
        return 300;
    }
}
