<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Reader;

use Login\Service\Security\BlackList\BlackListConfig;
use Login\Service\Security\BlackList\Driver\DriverInterface;
use Login\Service\Security\BlackList\Extractor\StatKeyExtractorInterface;
use Login\Service\Security\BlackList\Util\IpLevelUtil;

class BlackListStatReader implements BlackListStatReaderInterface
{
    /**
     * @var BlackListConfig
     */
    private $config;

    /**
     * @var StatKeyExtractorInterface
     */
    private $keyExtractor;

    /**
     * @var DriverInterface
     */
    private $driver;

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
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate) : \SplObjectStorage
    {
        $dates = $this->generateStatDates($startDate, $endDate);
        $result = new \SplObjectStorage();

        foreach ($dates as $date) {
            $result->attach($date, $this->findByDate($date));
        }

        return $result;
    }

    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     *
     * @return \DateTimeInterface[]
     */
    private function generateStatDates(\DateTimeInterface $startDate, \DateTimeInterface $endDate) : array
    {
        $windowSize = $this->config->getStatWindowSize();
        $baseDate = new \DateTime($startDate->format('Y-m-d H:i:s'), $startDate->getTimezone());
        $dates = array(clone $baseDate);

        do {
            $baseDate->modify(sprintf('+%d seconds', $windowSize));
            $dates[] = clone $baseDate;
        } while ($baseDate < $endDate);

        return $dates;
    }

    private function findByDate(\DateTimeInterface $date) : BlackListStatItem
    {
        return new BlackListStatItem(
            $this->doReadData($this->keyExtractor->extractForEmail($date)),
            $this->doReadIpData($date),
            $this->doReadData($this->keyExtractor->extractForGlobalFailedLogin($date))
        );
    }

    private function doReadIpData(\DateTimeInterface $date) : array
    {
        return array(
            IpLevelUtil::LEVEL_4 => $this->doReadData($this->keyExtractor->extractForIpLevel($date, IpLevelUtil::LEVEL_4)),
            IpLevelUtil::LEVEL_3 => $this->doReadData($this->keyExtractor->extractForIpLevel($date, IpLevelUtil::LEVEL_3)),
            IpLevelUtil::LEVEL_2 => $this->doReadData($this->keyExtractor->extractForIpLevel($date, IpLevelUtil::LEVEL_2)),
        );
    }

    private function doReadData($key) : int
    {
        return $this->driver->getByKey($key);
    }
}
