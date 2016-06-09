<?php

namespace Login\Service\Security\BlackList\Extractor;

class TimeKeyExtractor implements TimeKeyExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function extract(\DateTimeInterface $date, int $windowSize) : string
    {
        return 'time-'.floor($date->getTimestamp() / $windowSize);
    }
}
