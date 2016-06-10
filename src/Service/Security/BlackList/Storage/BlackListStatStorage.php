<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Storage;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\StatKeyExtractorInterface;

class BlackListStatStorage implements BlackListStatStorageInterface
{
    /**
     * @var StatKeyExtractorInterface
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
     * @param StatKeyExtractorInterface $keyExtractor
     */
    public function __construct(BlackListConfig $config, DriverInterface $driver, StatKeyExtractorInterface $keyExtractor)
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
        $this->doIncrement($this->keyExtractor->extractForIpLevel(new \DateTime(), $level));
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByEmail()
    {
        $this->doIncrement($this->keyExtractor->extractForEmail(new \DateTime()));
    }

    public function incrementByGlobalFailedLogin()
    {
        $this->doIncrement($this->keyExtractor->extractForGlobalFailedLogin(new \DateTime()));
    }

    private function doIncrement(string $key)
    {
        $this->driver->increment($key, $this->config->getKeyTTL());
    }
}
