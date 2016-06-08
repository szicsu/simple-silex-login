<?php

declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Extractor;

interface EmailKeyExtractorInterface
{
    public function extract(string $email) : array;
    public function extractPrimary(string $email) : string;
}
