<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Reader;

final class BlackListStatItem
{
    /**
     * @var int
     */
    private $globalFailedLoginCount;

    /**
     * @var int
     */
    private $captchaByIpCountMap;

    /**
     * @var int
     */
    private $captchaByEmailCount;

    /**
     * @param int   $captchaByEmailCount
     * @param array $captchaByIpCountMap
     * @param int   $globalFailedLoginCount
     */
    public function __construct(int $captchaByEmailCount, array $captchaByIpCountMap, int $globalFailedLoginCount)
    {
        $this->captchaByEmailCount = $captchaByEmailCount;
        $this->captchaByIpCountMap = $captchaByIpCountMap;
        $this->globalFailedLoginCount = $globalFailedLoginCount;
    }

    /**
     * @return int
     */
    public function getCaptchaByEmailCount() : int
    {
        return $this->captchaByEmailCount;
    }

    /**
     * @param int $level
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getCaptchaByIpCountByIpLevel(int $level) : int
    {
        if (isset($this->captchaByIpCountMap[$level])) {
            return $this->captchaByIpCountMap[$level];
        }

        throw new \InvalidArgumentException(sprintf(
            'The level(%s) not found in the map!',
            $level
        ));
    }

    /**
     * @return int
     */
    public function getGlobalFailedLoginCount() : int
    {
        return $this->globalFailedLoginCount;
    }
}
