<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Storage;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\EmailKeyExtractorInterface;
use Login\Service\Security\BlackList\Extractor\IpKeyExtractorInterface;
use Login\Service\Security\BlackList\Util\IpLevelUtil;

class BlackListStorage implements BlackListStorageInterface
{
    /**
     * @var BlackListConfig
     */
    private $config;

    /**
     * @var IpKeyExtractorInterface
     */
    private $ipKeyExtractor;

    /**
     * @var EmailKeyExtractorInterface
     */
    private $emailKeyExtractor;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param BlackListConfig            $config
     * @param DriverInterface            $driver
     * @param EmailKeyExtractorInterface $emailKeyExtractor
     * @param IpKeyExtractorInterface    $ipKeyExtractor
     */
    public function __construct(
        BlackListConfig $config,
        DriverInterface $driver,
        EmailKeyExtractorInterface $emailKeyExtractor,
        IpKeyExtractorInterface $ipKeyExtractor
    ) {
        $this->config = $config;
        $this->driver = $driver;
        $this->emailKeyExtractor = $emailKeyExtractor;
        $this->ipKeyExtractor = $ipKeyExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByEmail(string $email)
    {
        $this->doIncrement($this->emailKeyExtractor->extract($email));
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByIp(string $ip)
    {
        $this->doIncrement($this->ipKeyExtractor->extract($ip));
    }

    /**
     * {@inheritdoc}
     */
    public function isInByEmail(string $email) : bool
    {
        $key = $this->emailKeyExtractor->extractPrimary($email);
        $value = $this->driver->getByKey($key);

        return $value > $this->config->getLimitSameUser();
    }

    /**
     * {@inheritdoc}
     */
    public function isInByIp(string $ip) : bool
    {
        return false !== $this->getIpLevelThatIsInByIp($ip);
    }

    /**
     * {@inheritdoc}
     */
    public function getIpLevelThatIsInByIp(string $ip)
    {
        $levels = array(IpLevelUtil::LEVEL_4, IpLevelUtil::LEVEL_3, IpLevelUtil::LEVEL_2);
        foreach ($levels as $level) {
            $key = $this->ipKeyExtractor->extractForLevel($ip, $level);
            $value = $this->driver->getByKey($key);

            if ($value > $this->config->getLimitIpForLevel($level)) {
                return $level;
            }
        }

        return false;
    }

    private function doIncrement(array $keys)
    {
        $ttl = $this->config->getKeyTTL();
        foreach ($keys as $key) {
            $this->driver->increment($key, $ttl);
        }
    }
}
