<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Util;

final class IpUtil
{
    private function __construct()
    {
    }

    /**
     * @param string $ip
     *
     * @return array
     *
     * @throws \Exception
     */
    public static function extractForLevels(string $ip): array
    {
        return substr_count($ip, ':') > 1 ? self::extractIp6ForLevels($ip) : self::extractIp4ForLevels($ip);
    }

    /**
     * @param string $ip
     *
     * @return array
     */
    private static function extractIp4ForLevels(string $ip): array
    {
        if (false ===  $realIp = @inet_ntop(inet_pton($ip))) {
            throw new \InvalidArgumentException(sprintf('Invalid ip address: "%s"', $ip));
        }

        $levels = array(
            4 => IpLevelUtil::LEVEL_4,  3 => IpLevelUtil::LEVEL_3, 2 => IpLevelUtil::LEVEL_2, 1 => IpLevelUtil::LEVEL_1,
        );

        $seq = explode('.', $realIp);
        $result = array();
        for ($i = 4; $i > 0; --$i) {
            $result[$levels[$i]] = implode('.', $seq);
            array_pop($seq);
        }

        return $result;
    }

    /**
     * @param string $ip
     *
     * @return array
     *
     * @throws \Exception
     */
    private static function extractIp6ForLevels(string $ip) : array
    {
        throw new \Exception('Not implemented:'.__METHOD__);
    }
}
