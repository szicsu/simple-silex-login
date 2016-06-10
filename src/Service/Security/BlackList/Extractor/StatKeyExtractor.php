<?php

namespace Login\Service\Security\BlackList\Extractor;

use Login\Service\Security\BlackList\BlackListConfig;

class StatKeyExtractor implements StatKeyExtractorInterface
{
    /**
     * @var TimeKeyExtractorInterface
     */
    private $timeKeyExtractor;

    /**
     * @var BlackListConfig
     */
    private $config;

    /**
     * @param BlackListConfig           $config
     * @param TimeKeyExtractorInterface $timeKeyExtractor
     */
    public function __construct(BlackListConfig $config, TimeKeyExtractorInterface $timeKeyExtractor)
    {
        $this->config = $config;
        $this->timeKeyExtractor = $timeKeyExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function extractForIpLevel(\DateTimeInterface $date, int $level) : string
    {
        return $this->doExtract($date, 'ip-'.$level);
    }

    /**
     * {@inheritdoc}
     */
    public function extractForEmail(\DateTimeInterface $date) : string
    {
        return $this->doExtract($date, 'email');
    }

    /**
     * {@inheritdoc}
     */
    public function extractForGlobalFailedLogin(\DateTimeInterface $date) : string
    {
        return $this->doExtract($date, 'global');
    }

    /**
     * @param \DateTimeInterface $date
     * @param string             $keyPrefix
     *
     * @return string
     */
    private function doExtract(\DateTimeInterface $date, string $keyPrefix) : string
    {
        return $keyPrefix.'-'.$this->timeKeyExtractor->extract($date, $this->config->getStatWindowSize());
    }
}
