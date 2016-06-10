<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Extractor;

interface StatKeyExtractorInterface
{
    public function extractForIpLevel(\DateTimeInterface $date, int $level) : string;
    public function extractForEmail(\DateTimeInterface $date) : string;
    public function extractForGlobalFailedLogin(\DateTimeInterface $date) : string;
}
