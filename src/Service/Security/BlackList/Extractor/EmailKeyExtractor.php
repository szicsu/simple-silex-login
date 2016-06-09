<?php

namespace Login\Service\Security\BlackList\Extractor;

/**
 * Simple implementation for EmailKeyExtractorInterface.
 */
class EmailKeyExtractor implements EmailKeyExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function extract(string $email) : array
    {
        return array(
            $this->extractPrimary($email),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function extractPrimary(string $email) : string
    {
        return mb_strtolower(trim($email));
    }
}
