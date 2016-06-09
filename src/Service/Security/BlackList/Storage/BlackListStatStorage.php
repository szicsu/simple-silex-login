<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Storage;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\TimeKeyExtractorInterface;

class BlackListStatStorage implements BlackListStatStorageInterface
{
    /**
     * @var TimeKeyExtractorInterface
     */
    private $keyExtractor;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var BlackListConfig
     */
    private $config;

    /**
     * @param BlackListConfig           $config
     * @param DriverInterface           $driver
     * @param TimeKeyExtractorInterface $keyExtractor
     */
    public function __construct(BlackListConfig $config, DriverInterface $driver, TimeKeyExtractorInterface $keyExtractor)
    {
        $this->config = $config;
        $this->driver = $driver;
        $this->keyExtractor = $keyExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByIpLevel(int $level)
    {
        $this->doIncrement('ip-'.$level);
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByEmail()
    {
        $this->doIncrement('email');
    }

    private function doIncrement(string $keyPrefix)
    {
        $key = $keyPrefix.'-'.$this->keyExtractor->extract(new \DateTime(), $this->config->getStatWindowSize());
        $this->driver->increment($key, $this->config->getKeyTTL());
    }
}
