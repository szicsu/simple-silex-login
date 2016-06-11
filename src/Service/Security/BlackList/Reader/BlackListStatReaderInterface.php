<?php


declare (strict_types = 1);

namespace Login\Service\Security\BlackList\Reader;

interface BlackListStatReaderInterface
{
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate) : \SplObjectStorage;
}
