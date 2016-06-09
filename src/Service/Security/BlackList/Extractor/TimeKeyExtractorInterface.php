<?php

namespace Login\Service\Security\BlackList\Extractor;

interface TimeKeyExtractorInterface
{
    public function extract(\DateTimeInterface $date, int $windowSize) : string;
}
