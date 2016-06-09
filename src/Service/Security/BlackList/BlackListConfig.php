<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList;

use Login\Service\Security\BlackList\Util\IpLevelUtil;

class BlackListConfig
{
    /**
     * @var int
     */
    private $keyTTL;

    /**
     * @var int
     */
    private $limitSameUser;

    /**
     * @var array
     */
    private $limitForIpMap;

    /**
     * @var int
     */
    private $statWindowSize;

    /**
     * @param int   $keyTTL
     * @param array $limitForIpMap
     * @param int   $limitSameUser
     * @param int   $statWindowSize
     */
    public function __construct(int $keyTTL, array $limitForIpMap, int $limitSameUser, int $statWindowSize)
    {
        $this->keyTTL = $keyTTL;
        $this->limitForIpMap = $limitForIpMap;
        $this->limitSameUser = $limitSameUser;
        $this->statWindowSize = $statWindowSize;
    }

    public function getKeyTTL(): int
    {
        return $this->keyTTL;
    }

    public function getLimitSameUser() : int
    {
        return $this->limitSameUser;
    }

    public function getLimitIpForLevel(int $level) : int
    {
        IpLevelUtil::checkValue($level);
        if (isset($this->limitForIpMap[$level])) {
            return $this->limitForIpMap[$level];
        } else {
            throw new \DomainException(sprintf(
                'The level(%s) is not configured!',
                $level
            ));
        }
    }

    public function getStatWindowSize() : int
    {
        return $this->statWindowSize;
    }
}
