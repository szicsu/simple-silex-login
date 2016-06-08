<?php


declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Extractor;

interface IpKeyExtractorInterface
{
    public function extract(string $ip) : array;
    public function extractForLevel(string $ip, int $level) : string;
}
